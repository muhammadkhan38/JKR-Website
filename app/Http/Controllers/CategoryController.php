<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $books = $category->books()->active()->with(['author', 'category'])->latest()->paginate(12);

        return view('categories.show', compact('category', 'books'));
    }
}
