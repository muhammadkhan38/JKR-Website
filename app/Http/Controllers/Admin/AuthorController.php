<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Support\LocalizedColumns;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(): View
    {
        return view('admin.authors.index', ['authors' => Author::withCount('books')->orderByRaw(LocalizedColumns::orderExpression('name'))->paginate(20)]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.authors.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['nullable', 'string', 'max:255'],
            'bio_en' => ['nullable', 'string'],
            'bio_ur' => ['nullable', 'string'],
        ]);

        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Author::class, $data['name']);
        Author::create($data);

        return back()->with('success', __('messages.flash.author_created'));
    }

    public function show(Author $author): RedirectResponse
    {
        return redirect()->route('admin.authors.index');
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.index', [
            'authors' => Author::withCount('books')->orderByRaw(LocalizedColumns::orderExpression('name'))->paginate(20),
            'editing' => $author,
        ]);
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['nullable', 'string', 'max:255'],
            'bio_en' => ['nullable', 'string'],
            'bio_ur' => ['nullable', 'string'],
        ]);

        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Author::class, $data['name'], $author->id);
        $author->update($data);

        return redirect()->route('admin.authors.index')->with('success', __('messages.flash.author_updated'));
    }

    public function destroy(Author $author): RedirectResponse
    {
        $author->delete();

        return back()->with('success', __('messages.flash.author_deleted'));
    }

    private function setLegacyFields(array &$data): void
    {
        $data['name'] = $data['name_en'] ?: ($data['name_ur'] ?? '');
        $data['bio'] = $data['bio_en'] ?: ($data['bio_ur'] ?? null);
    }
}
