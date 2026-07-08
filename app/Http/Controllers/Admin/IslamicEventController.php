<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\IslamicEvent;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IslamicEventController extends Controller
{
    public function index(): View
    {
        return view('admin.events.index', [
            'events' => IslamicEvent::withCount('books')->orderBy('display_order')->paginate(15),
            'books' => Book::active()->orderBy('title')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.events.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Slug::unique(IslamicEvent::class, $data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['banner_image'] = $request->file('banner_image')?->store('events', 'public');

        $event = IslamicEvent::create($data);
        $event->books()->sync($request->input('book_ids', []));

        return back()->with('success', 'اسلامی مجموعہ شامل کر دیا گیا۔');
    }

    public function show(IslamicEvent $islamicEvent): RedirectResponse
    {
        return redirect()->route('admin.islamic-events.index');
    }

    public function edit(IslamicEvent $islamicEvent): View
    {
        return view('admin.events.index', [
            'events' => IslamicEvent::withCount('books')->orderBy('display_order')->paginate(15),
            'books' => Book::active()->orderBy('title')->get(),
            'editing' => $islamicEvent->load('books'),
        ]);
    }

    public function update(Request $request, IslamicEvent $islamicEvent): RedirectResponse
    {
        $data = $this->validated($request, false);
        $data['slug'] = Slug::unique(IslamicEvent::class, $data['title'], $islamicEvent->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('banner_image')) {
            if ($islamicEvent->banner_image) {
                Storage::disk('public')->delete($islamicEvent->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $islamicEvent->update($data);
        $islamicEvent->books()->sync($request->input('book_ids', []));

        return redirect()->route('admin.islamic-events.index')->with('success', 'اسلامی مجموعہ اپ ڈیٹ کر دیا گیا۔');
    }

    public function destroy(IslamicEvent $islamicEvent): RedirectResponse
    {
        if ($islamicEvent->banner_image) {
            Storage::disk('public')->delete($islamicEvent->banner_image);
        }

        $islamicEvent->delete();

        return back()->with('success', 'اسلامی مجموعہ حذف کر دیا گیا۔');
    }

    private function validated(Request $request, bool $bannerNullable = true): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'banner_image' => [$bannerNullable ? 'nullable' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'book_ids' => ['nullable', 'array'],
            'book_ids.*' => ['exists:books,id'],
        ]);
    }
}
