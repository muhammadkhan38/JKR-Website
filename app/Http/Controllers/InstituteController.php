<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Author;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Models\Setting;
use App\Support\LocalizedColumns;
use Illuminate\View\View;

class InstituteController extends Controller
{
    public function lectures(): View
    {
        return view('institute.lectures', [
            'lectures' => Audio::active()->with(['book', 'category', 'islamicEvent'])->latest()->paginate(12),
        ]);
    }

    public function live(): View
    {
        return view('institute.live', [
            'recordings' => Audio::active()->with(['book', 'category'])->latest()->take(12)->get(),
        ]);
    }

    public function institute(): View
    {
        return view('institute.index', [
            'settings' => Setting::localizedPairs(),
            'categories' => Category::active()
                ->withCount(['books' => fn ($query) => $query->active()])
                ->orderByRaw(LocalizedColumns::orderExpression('name'))
                ->get(),
            'teachers' => Author::query()
                ->whereHas('books', fn ($query) => $query->active())
                ->withCount(['books' => fn ($query) => $query->active()])
                ->orderByRaw(LocalizedColumns::orderExpression('name'))
                ->get(),
        ]);
    }

    public function announcements(): View
    {
        return view('institute.announcements', [
            'announcements' => IslamicEvent::visible()
                ->withCount(['books' => fn ($query) => $query->active()])
                ->orderBy('display_order')
                ->get(),
        ]);
    }
}
