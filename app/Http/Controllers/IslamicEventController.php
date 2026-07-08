<?php

namespace App\Http\Controllers;

use App\Models\IslamicEvent;
use Illuminate\View\View;

class IslamicEventController extends Controller
{
    public function show(IslamicEvent $event): View
    {
        abort_unless($event->is_active, 404);

        $books = $event->books()->active()->with(['author', 'category'])->paginate(12);
        $audios = $event->audios()->active()->latest()->get();

        return view('events.show', compact('event', 'books', 'audios'));
    }
}
