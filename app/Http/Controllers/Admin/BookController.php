<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Support\GoogleDrivePdf;
use App\Support\LocalizedColumns;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        return view('admin.books.index', [
            'books' => Book::with(['author', 'category'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'book' => new Book(['is_active' => true, 'download_allowed' => true]),
            'categories' => Category::orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'authors' => Author::orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Book::class, $data['title']);
        $data['cover_image'] = $request->file('cover_image')?->store('covers', 'public');
        $data['pdf_file'] = $request->file('pdf_file')?->store('books', 'public');
        $data['pdf_file_en'] = $request->file('pdf_file_en')?->store('books', 'public');
        $data['pdf_file_ur'] = $request->file('pdf_file_ur')?->store('books', 'public');
        $this->setBooleans($data, $request);

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', __('messages.flash.book_created'));
    }

    public function show(Book $book): RedirectResponse
    {
        return redirect()->route('admin.books.edit', $book);
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'authors' => Author::orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $this->validated($request, false);
        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Book::class, $data['title'], $book->id);

        if ($request->hasFile('cover_image')) {
            $this->deletePublicFile($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $this->deletePublicFile($book->pdf_file);
            $data['pdf_file'] = $request->file('pdf_file')->store('books', 'public');
        }

        if ($request->hasFile('pdf_file_en')) {
            $this->deletePublicFile($book->pdf_file_en);
            $data['pdf_file_en'] = $request->file('pdf_file_en')->store('books', 'public');
        }

        if ($request->hasFile('pdf_file_ur')) {
            $this->deletePublicFile($book->pdf_file_ur);
            $data['pdf_file_ur'] = $request->file('pdf_file_ur')->store('books', 'public');
        }

        $this->setBooleans($data, $request);
        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', __('messages.flash.book_updated'));
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->deletePublicFile($book->cover_image);
        $this->deletePublicFile($book->pdf_file);
        $this->deletePublicFile($book->pdf_file_en);
        $this->deletePublicFile($book->pdf_file_ur);
        $book->delete();

        return back()->with('success', __('messages.flash.book_deleted'));
    }

    private function validated(Request $request, bool $requirePdf = true): array
    {
        return $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ur' => ['nullable', 'string', 'max:255'],
            'author_id' => ['required', 'exists:authors,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'language_en' => ['required', 'string', 'max:80'],
            'language_ur' => ['nullable', 'string', 'max:80'],
            'short_description_en' => ['nullable', 'string', 'max:500'],
            'short_description_ur' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pdf_file' => [$requirePdf ? 'required_without_all:pdf_file_en,pdf_file_ur,external_pdf_url,external_pdf_url_en,external_pdf_url_ur' : 'nullable', 'file', 'mimes:pdf', 'max:20480'],
            'pdf_file_en' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'pdf_file_ur' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'external_pdf_url' => $this->externalPdfUrlRules(),
            'external_pdf_url_en' => $this->externalPdfUrlRules(),
            'external_pdf_url_ur' => $this->externalPdfUrlRules(),
        ]);
    }

    private function externalPdfUrlRules(): array
    {
        return [
            'nullable',
            'url',
            'max:2048',
            function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value && ! GoogleDrivePdf::isSupportedPdfUrl($value)) {
                    $fail(__('messages.books.invalid_pdf_link'));
                }
            },
        ];
    }

    private function setLegacyFields(array &$data): void
    {
        $data['title'] = $data['title_en'] ?: ($data['title_ur'] ?? '');
        $data['language'] = $data['language_en'] ?: ($data['language_ur'] ?? 'English');
        $data['short_description'] = $data['short_description_en'] ?: ($data['short_description_ur'] ?? null);
        $data['description'] = $data['description_en'] ?: ($data['description_ur'] ?? null);
    }

    private function setBooleans(array &$data, Request $request): void
    {
        $data['is_latest'] = $request->boolean('is_latest');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['download_allowed'] = $request->boolean('download_allowed');
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
