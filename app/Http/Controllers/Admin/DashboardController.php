<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalBooks' => Book::count(),
            'totalCategories' => Category::count(),
            'totalAuthors' => Author::count(),
            'totalUsers' => User::count(),
            'totalAudios' => Audio::count(),
            'activeEvents' => IslamicEvent::visible()->count(),
            'latestBooks' => Book::with(['author', 'category'])->latest()->take(6)->get(),
        ]);
    }
}
