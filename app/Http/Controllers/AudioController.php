<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AudioController extends Controller
{
    public function index(Request $request): View
    {
        $audios = Audio::active()
            ->with(['book', 'category', 'islamicEvent'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where('title', 'like', $term)
                    ->orWhere('speaker', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('audios.index', compact('audios'));
    }
}
