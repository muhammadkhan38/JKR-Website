<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
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
                    $search->where('title', 'like', $term)
                        ->orWhere('language', 'like', $term)
                        ->orWhere('short_description', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('author', fn ($author) => $author->where('name', 'like', $term))
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', $term));
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
            'categories' => Category::active()->orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'languages' => Book::active()->select('language')->distinct()->orderBy('language')->pluck('language'),
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

        if (! $book->pdf_file || ! Storage::disk('public')->exists($book->pdf_file)) {
            return back()->with('error', 'The PDF file is not available yet.');
        }

        return Storage::disk('public')->download($book->pdf_file, $book->slug.'.pdf');
    }
}
