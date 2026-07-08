@extends('layouts.admin')

@section('title', 'Manage Audio')
@section('heading', 'Manage Audio')

@section('content')
@php($audioForm = $editing ?? new \App\Models\Audio(['is_active' => true]))
<div class="grid gap-6 xl:grid-cols-[420px_1fr]">
    <form method="POST" action="{{ $audioForm->exists ? route('admin.audios.update', $audioForm) : route('admin.audios.store') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($audioForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $audioForm->exists ? 'Edit Audio' : 'Add Audio' }}</h2>
        <div class="space-y-4">
            <div>
                <label class="form-label" for="title">Title</label>
                <input class="form-input" id="title" name="title" value="{{ old('title', $audioForm->title) }}" required>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="speaker">Speaker</label>
                    <input class="form-input" id="speaker" name="speaker" value="{{ old('speaker', $audioForm->speaker) }}">
                </div>
                <div>
                    <label class="form-label" for="duration">Duration</label>
                    <input class="form-input" id="duration" name="duration" value="{{ old('duration', $audioForm->duration) }}" placeholder="00:12:30">
                </div>
            </div>
            <div>
                <label class="form-label" for="description">Description</label>
                <textarea class="form-input" id="description" name="description" rows="3">{{ old('description', $audioForm->description) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="audio_file">Audio File</label>
                <input class="form-input" id="audio_file" type="file" name="audio_file" accept=".mp3,.wav,.m4a" data-file-label="#audio-file-name" @required(! $audioForm->exists)>
                <p id="audio-file-name" class="mt-2 text-sm text-slate-500">{{ $audioForm->audio_file ? 'Current: '.$audioForm->audio_file : '' }}</p>
            </div>
            <div>
                <label class="form-label" for="book_id">Related Book</label>
                <select class="form-input" id="book_id" name="book_id">
                    <option value="">None</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" @selected(old('book_id', $audioForm->book_id) == $book->id)>{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="category_id">Category</label>
                    <select class="form-input" id="category_id" name="category_id">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $audioForm->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="islamic_event_id">Event</label>
                    <select class="form-input" id="islamic_event_id" name="islamic_event_id">
                        <option value="">None</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @selected(old('islamic_event_id', $audioForm->islamic_event_id) == $event->id)>{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $audioForm->is_active))> Active</label>
        </div>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $audioForm->exists ? 'Update' : 'Create' }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead><tr><th>Audio</th><th>Attached To</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($audios as $audio)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $audio->title }}</div>
                            <div class="text-xs text-slate-500">{{ $audio->speaker ?: 'No speaker' }}</div>
                        </td>
                        <td>{{ $audio->book?->title ?? $audio->category?->name ?? $audio->islamicEvent?->title ?? 'General' }}</td>
                        <td>{{ $audio->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.audios.edit', $audio) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.audios.destroy', $audio) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No audio files yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $audios->links() }}</div>
    </div>
</div>
@endsection
