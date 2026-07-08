@extends('layouts.admin')

@section('title', 'Manage Islamic Events')
@section('heading', 'Manage Islamic Events')

@section('content')
@php($eventForm = $editing ?? new \App\Models\IslamicEvent(['is_active' => true, 'display_order' => 0]))
@php($selectedBooks = old('book_ids', $eventForm->exists ? $eventForm->books->pluck('id')->all() : []))
<div class="grid gap-6 xl:grid-cols-[420px_1fr]">
    <form method="POST" action="{{ $eventForm->exists ? route('admin.islamic-events.update', $eventForm) : route('admin.islamic-events.store') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($eventForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $eventForm->exists ? 'Edit Collection' : 'Add Collection' }}</h2>
        <div class="space-y-4">
            <div>
                <label class="form-label" for="title">Title</label>
                <input class="form-input" id="title" name="title" value="{{ old('title', $eventForm->title) }}" required>
            </div>
            <div>
                <label class="form-label" for="description">Description</label>
                <textarea class="form-input" id="description" name="description" rows="4">{{ old('description', $eventForm->description) }}</textarea>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="start_date">Start Date</label>
                    <input class="form-input" id="start_date" type="date" name="start_date" value="{{ old('start_date', optional($eventForm->start_date)->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="form-label" for="end_date">End Date</label>
                    <input class="form-input" id="end_date" type="date" name="end_date" value="{{ old('end_date', optional($eventForm->end_date)->format('Y-m-d')) }}">
                </div>
            </div>
            <div>
                <label class="form-label" for="display_order">Display Order</label>
                <input class="form-input" id="display_order" type="number" min="0" name="display_order" value="{{ old('display_order', $eventForm->display_order) }}">
            </div>
            <div>
                <label class="form-label" for="banner_image">Banner Image</label>
                <input class="form-input" id="banner_image" type="file" name="banner_image" accept=".jpg,.jpeg,.png,.webp" data-file-label="#banner-file-name">
                <p id="banner-file-name" class="mt-2 text-sm text-slate-500">{{ $eventForm->banner_image ? 'Current: '.$eventForm->banner_image : '' }}</p>
            </div>
            <div>
                <label class="form-label" for="book_ids">Attach Books</label>
                <select class="form-input min-h-44" id="book_ids" name="book_ids[]" multiple>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" @selected(in_array($book->id, $selectedBooks))>{{ $book->title }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">Hold Ctrl to select multiple books.</p>
            </div>
            <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $eventForm->is_active))> Active</label>
        </div>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $eventForm->exists ? 'Update' : 'Create' }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead><tr><th>Collection</th><th>Dates</th><th>Books</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $event->title }}</div>
                            <div class="text-xs text-slate-500">Order {{ $event->display_order }}</div>
                        </td>
                        <td>{{ optional($event->start_date)->format('d M Y') ?: 'Any' }} - {{ optional($event->end_date)->format('d M Y') ?: 'Any' }}</td>
                        <td>{{ $event->books_count }}</td>
                        <td>{{ $event->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.islamic-events.edit', $event) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.islamic-events.destroy', $event) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No events yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $events->links() }}</div>
    </div>
</div>
@endsection
