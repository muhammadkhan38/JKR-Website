<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(): View
    {
        return view('admin.authors.index', ['authors' => Author::withCount('books')->orderBy('name')->paginate(20)]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.authors.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $data['slug'] = Slug::unique(Author::class, $data['name']);
        Author::create($data);

        return back()->with('success', 'مصنف شامل کر دیا گیا۔');
    }

    public function show(Author $author): RedirectResponse
    {
        return redirect()->route('admin.authors.index');
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.index', [
            'authors' => Author::withCount('books')->orderBy('name')->paginate(20),
            'editing' => $author,
        ]);
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $data['slug'] = Slug::unique(Author::class, $data['name'], $author->id);
        $author->update($data);

        return redirect()->route('admin.authors.index')->with('success', 'مصنف اپ ڈیٹ کر دیا گیا۔');
    }

    public function destroy(Author $author): RedirectResponse
    {
        $author->delete();

        return back()->with('success', 'مصنف حذف کر دیا گیا۔');
    }
}
