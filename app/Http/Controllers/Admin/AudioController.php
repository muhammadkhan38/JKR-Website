<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Support\LocalizedColumns;
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
            'books' => Book::active()->orderByRaw(LocalizedColumns::orderExpression('title'))->get(),
            'categories' => Category::active()->orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'events' => IslamicEvent::orderByRaw(LocalizedColumns::orderExpression('title'))->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.audios.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Audio::class, $data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['audio_file'] = $request->file('audio_file')?->store('audios', 'public');

        Audio::create($data);

        return back()->with('success', __('messages.flash.audio_created'));
    }

    public function show(Audio $audio): RedirectResponse
    {
        return redirect()->route('admin.audios.index');
    }

    public function edit(Audio $audio): View
    {
        return view('admin.audios.index', [
            'audios' => Audio::with(['book', 'category', 'islamicEvent'])->latest()->paginate(15),
            'books' => Book::active()->orderByRaw(LocalizedColumns::orderExpression('title'))->get(),
            'categories' => Category::active()->orderByRaw(LocalizedColumns::orderExpression('name'))->get(),
            'events' => IslamicEvent::orderByRaw(LocalizedColumns::orderExpression('title'))->get(),
            'editing' => $audio,
        ]);
    }

    public function update(Request $request, Audio $audio): RedirectResponse
    {
        $data = $this->validated($request, false);
        $this->setLegacyFields($data);
        $data['slug'] = Slug::unique(Audio::class, $data['title'], $audio->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('audio_file')) {
            if ($audio->audio_file) {
                Storage::disk('public')->delete($audio->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('audios', 'public');
        }

        $audio->update($data);

        return redirect()->route('admin.audios.index')->with('success', __('messages.flash.audio_updated'));
    }

    public function destroy(Audio $audio): RedirectResponse
    {
        if ($audio->audio_file) {
            Storage::disk('public')->delete($audio->audio_file);
        }

        $audio->delete();

        return back()->with('success', __('messages.flash.audio_deleted'));
    }

    private function validated(Request $request, bool $requireFile = true): array
    {
        return $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ur' => ['nullable', 'string', 'max:255'],
            'speaker_en' => ['nullable', 'string', 'max:255'],
            'speaker_ur' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:50'],
            'book_id' => ['nullable', 'exists:books,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'islamic_event_id' => ['nullable', 'exists:islamic_events,id'],
            'audio_file' => [$requireFile ? 'required' : 'nullable', 'file', 'mimes:mp3,wav,m4a', 'max:51200'],
        ]);
    }

    private function setLegacyFields(array &$data): void
    {
        $data['title'] = $data['title_en'] ?: ($data['title_ur'] ?? '');
        $data['speaker'] = $data['speaker_en'] ?: ($data['speaker_ur'] ?? null);
        $data['description'] = $data['description_en'] ?: ($data['description_ur'] ?? null);
    }
}
