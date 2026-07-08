<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\LocalizedColumns;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('books')->orderByRaw(LocalizedColumns::orderExpression('name'))->paginate(20)]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
        ]);

        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Category::class, $data['name']);
        $data['is_active'] = $request->boolean('is_active');

        Category::create($data);

        return back()->with('success', __('messages.flash.category_created'));
    }

    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('books')->orderByRaw(LocalizedColumns::orderExpression('name'))->paginate(20),
            'editing' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
        ]);

        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Category::class, $data['name'], $category->id);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', __('messages.flash.category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', __('messages.flash.category_deleted'));
    }

    private function setLegacyFields(array &$data): void
    {
        $data['name'] = $data['name_en'] ?: ($data['name_ur'] ?? '');
        $data['description'] = $data['description_en'] ?: ($data['description_ur'] ?? null);
    }
}
