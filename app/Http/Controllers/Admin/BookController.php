<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
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
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Slug::unique(Book::class, $data['title']);
        $data['cover_image'] = $request->file('cover_image')?->store('covers', 'public');
        $data['pdf_file'] = $request->file('pdf_file')?->store('books', 'public');
        $this->setBooleans($data, $request);

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Book created.');
    }

    public function show(Book $book): RedirectResponse
    {
        return redirect()->route('admin.books.edit', $book);
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $this->validated($request, false);
        $data['slug'] = Slug::unique(Book::class, $data['title'], $book->id);

        if ($request->hasFile('cover_image')) {
            $this->deletePublicFile($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $this->deletePublicFile($book->pdf_file);
            $data['pdf_file'] = $request->file('pdf_file')->store('books', 'public');
        }

        $this->setBooleans($data, $request);
        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->deletePublicFile($book->cover_image);
        $this->deletePublicFile($book->pdf_file);
        $book->delete();

        return back()->with('success', 'Book deleted.');
    }

    private function validated(Request $request, bool $requirePdf = true): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'exists:authors,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'language' => ['required', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pdf_file' => [$requirePdf ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);
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
