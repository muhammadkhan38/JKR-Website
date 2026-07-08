<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'settings' => Setting::pairs(),
            'latestBooks' => Book::active()->with(['author', 'category'])->orderByDesc('is_latest')->latest()->take(6)->get(),
            'featuredBooks' => Book::active()->where('is_featured', true)->with(['author', 'category'])->latest()->take(6)->get(),
            'events' => IslamicEvent::visible()->with(['books' => fn ($query) => $query->active()->with(['author', 'category'])->take(4)])->orderBy('display_order')->take(4)->get(),
            'categories' => Category::active()->withCount(['books' => fn ($query) => $query->active()])->orderBy('name')->take(8)->get(),
            'audios' => Audio::active()->with(['book', 'category'])->latest()->take(4)->get(),
        ]);
    }
}
