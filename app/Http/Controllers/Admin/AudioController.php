<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AudioController extends Controller
{
    public function index(): View
    {
        return view('admin.audios.index', [
            'audios' => Audio::with(['book', 'category', 'islamicEvent'])->latest()->paginate(15),
            'books' => Book::active()->orderBy('title')->get(),
            'categories' => Category::active()->orderBy('name')->get(),
            'events' => IslamicEvent::orderBy('title')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.audios.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Slug::unique(Audio::class, $data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['audio_file'] = $request->file('audio_file')?->store('audios', 'public');

        Audio::create($data);

        return back()->with('success', 'آڈیو شامل کر دی گئی۔');
    }

    public function show(Audio $audio): RedirectResponse
    {
        return redirect()->route('admin.audios.index');
    }

    public function edit(Audio $audio): View
    {
        return view('admin.audios.index', [
            'audios' => Audio::with(['book', 'category', 'islamicEvent'])->latest()->paginate(15),
            'books' => Book::active()->orderBy('title')->get(),
            'categories' => Category::active()->orderBy('name')->get(),
            'events' => IslamicEvent::orderBy('title')->get(),
            'editing' => $audio,
        ]);
    }

    public function update(Request $request, Audio $audio): RedirectResponse
    {
        $data = $this->validated($request, false);
        $data['slug'] = Slug::unique(Audio::class, $data['title'], $audio->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('audio_file')) {
            if ($audio->audio_file) {
                Storage::disk('public')->delete($audio->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('audios', 'public');
        }

        $audio->update($data);

        return redirect()->route('admin.audios.index')->with('success', 'آڈیو اپ ڈیٹ کر دی گئی۔');
    }

    public function destroy(Audio $audio): RedirectResponse
    {
        if ($audio->audio_file) {
            Storage::disk('public')->delete($audio->audio_file);
        }

        $audio->delete();

        return back()->with('success', 'آڈیو حذف کر دی گئی۔');
    }

    private function validated(Request $request, bool $requireFile = true): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:50'],
            'book_id' => ['nullable', 'exists:books,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'islamic_event_id' => ['nullable', 'exists:islamic_events,id'],
            'audio_file' => [$requireFile ? 'required' : 'nullable', 'file', 'mimes:mp3,wav,m4a', 'max:51200'],
        ]);
    }
}
