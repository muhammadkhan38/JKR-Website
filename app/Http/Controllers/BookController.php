<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Support\LocalizedColumns;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $books = Book::active()
            ->with(['author', 'category'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function ($search) use ($term): void {
                    foreach (['title', 'language', 'short_description', 'description'] as $field) {
                        foreach (LocalizedColumns::searchColumns($field) as $column) {
                            $search->orWhere($column, 'like', $term);
                        }
                    }

                    $search->orWhereHas('author', function ($author) use ($term): void {
                        $author->where(function ($authorSearch) use ($term): void {
                            foreach (LocalizedColumns::searchColumns('name') as $column) {
                                $authorSearch->orWhere($column, 'like', $term);
                            }
                        });
                    })->orWhereHas('category', function ($category) use ($term): void {
                        $category->where(function ($categorySearch) use ($term): void {
                            foreach (LocalizedColumns::searchColumns('name') as $column) {
                                $categorySearch->orWhere($column, 'like', $term);
                            }
                        });
                    });
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->filled('author'), fn ($query) => $query->where('author_id', $request->integer('author')))
            ->when($request->filled('language'), fn ($query) => $query->where('language', $request->string('language')))
            ->when($request->boolean('latest'), fn ($query) => $query->where('is_latest', true))
            ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('books.index', [
            'books' => $books,
            'categories' => Category::active()->orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'authors' => Author::orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'languages' => Book::active()->select('language', 'language_en', 'language_ur')->distinct()->orderByRaw(LocalizedColumns::orderExpression('language'))->get(),
        ]);
    }

    public function show(Book $book): View
    {
        abort_unless($book->is_active, 404);

        $book->load(['author', 'category', 'audios' => fn ($query) => $query->active()]);
        $relatedBooks = Book::active()
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->with(['author', 'category'])
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function reader(Book $book): View
    {
        abort_unless($book->is_active, 404);

        return view('books.reader', compact('book'));
    }

    public function download(Book $book): RedirectResponse|StreamedResponse
    {
        abort_unless($book->is_active && $book->download_allowed, 403);

        $pdfSource = $book->localizedPdfSource();

        if (($pdfSource['type'] ?? null) === 'external') {
            return redirect()->away($pdfSource['value']);
        }

        $pdfPath = ($pdfSource['type'] ?? null) === 'local' ? $pdfSource['value'] : null;

        if (! $pdfPath || ! Storage::disk('public')->exists($pdfPath)) {
            $externalPdfUrl = $book->localizedExternalPdfUrl();

            if ($externalPdfUrl) {
                return redirect()->away($externalPdfUrl);
            }

            return back()->with('error', __('messages.books.download_unavailable'));
        }

        return Storage::disk('public')->download($pdfPath, $book->slug.'.pdf');
    }
}
