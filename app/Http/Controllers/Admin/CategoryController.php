<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('books')->orderBy('name')->paginate(20)]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Slug::unique(Category::class, $data['name']);
        $data['is_active'] = $request->boolean('is_active');

        Category::create($data);

        return back()->with('success', 'Category created.');
    }

    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('books')->orderBy('name')->paginate(20),
            'editing' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Slug::unique(Category::class, $data['name'], $category->id);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
