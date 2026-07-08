@extends('layouts.admin')

@section('title', __('messages.admin.events.manage'))
@section('heading', __('messages.admin.events.manage'))

@section('content')
@php($eventForm = $editing ?? new \App\Models\IslamicEvent(['is_active' => true, 'display_order' => 0]))
@php($selectedBooks = old('book_ids', $eventForm->exists ? $eventForm->books->pluck('id')->all() : []))
<div class="grid gap-6 xl:grid-cols-[460px_1fr]">
    <form method="POST" action="{{ $eventForm->exists ? route('admin.islamic-events.update', $eventForm) : route('admin.islamic-events.store') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($eventForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $eventForm->exists ? __('messages.admin.events.edit') : __('messages.admin.events.add') }}</h2>
        <div class="space-y-4">
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="title_en">{{ __('messages.admin.fields.title_en') }}</label>
                    <input class="form-input" id="title_en" name="title_en" value="{{ old('title_en', $eventForm->title_en ?: $eventForm->title) }}" required>
                </div>
                <div>
                    <label class="form-label" for="title_ur">{{ __('messages.admin.fields.title_ur') }}</label>
                    <input class="form-input" id="title_ur" name="title_ur" value="{{ old('title_ur', $eventForm->title_ur) }}" dir="rtl">
                </div>
            </div>
            <div>
                <label class="form-label" for="description_en">{{ __('messages.admin.fields.description_en') }}</label>
                <textarea class="form-input" id="description_en" name="description_en" rows="3">{{ old('description_en', $eventForm->description_en ?: $eventForm->description) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="description_ur">{{ __('messages.admin.fields.description_ur') }}</label>
                <textarea class="form-input" id="description_ur" name="description_ur" rows="3" dir="rtl">{{ old('description_ur', $eventForm->description_ur) }}</textarea>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="start_date">{{ __('messages.admin.fields.start_date') }}</label>
                    <input class="form-input" id="start_date" type="date" name="start_date" value="{{ old('start_date', optional($eventForm->start_date)->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="form-label" for="end_date">{{ __('messages.admin.fields.end_date') }}</label>
                    <input class="form-input" id="end_date" type="date" name="end_date" value="{{ old('end_date', optional($eventForm->end_date)->format('Y-m-d')) }}">
                </div>
            </div>
            <div>
                <label class="form-label" for="display_order">{{ __('messages.admin.fields.display_order') }}</label>
                <input class="form-input" id="display_order" type="number" min="0" name="display_order" value="{{ old('display_order', $eventForm->display_order) }}">
            </div>
            <div>
                <label class="form-label" for="banner_image">{{ __('messages.admin.fields.banner_image') }}</label>
                <input class="form-input" id="banner_image" type="file" name="banner_image" accept=".jpg,.jpeg,.png,.webp" data-file-label="#banner-file-name">
                <p id="banner-file-name" class="mt-2 text-sm text-slate-500">{{ $eventForm->banner_image ? __('messages.common.current_file', ['file' => $eventForm->banner_image]) : '' }}</p>
            </div>
            <div>
                <label class="form-label" for="book_ids">{{ __('messages.admin.fields.linked_books') }}</label>
                <select class="form-input min-h-44" id="book_ids" name="book_ids[]" multiple>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" @selected(in_array($book->id, $selectedBooks))>{{ $book->localized_title }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">{{ __('messages.admin.events.select_books_hint') }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $eventForm->is_active))> {{ __('messages.common.active') }}</label>
        </div>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $eventForm->exists ? __('messages.common.update') : __('messages.common.create') }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.admin.events.collection') }}</th>
                    <th>{{ __('messages.admin.events.dates') }}</th>
                    <th>{{ __('messages.nav.books') }}</th>
                    <th>{{ __('messages.admin.fields.status') }}</th>
                    <th>{{ __('messages.admin.fields.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $event->localized_title }}</div>
                            <div class="text-xs text-slate-500">{{ __('messages.admin.events.order', ['number' => $event->display_order]) }}</div>
                        </td>
                        <td>{{ optional($event->start_date)->format('d-m-Y') ?: __('messages.common.any') }} - {{ optional($event->end_date)->format('d-m-Y') ?: __('messages.common.any') }}</td>
                        <td>{{ $event->books_count }}</td>
                        <td>{{ $event->is_active ? __('messages.common.active') : __('messages.common.inactive') }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.islamic-events.edit', $event) }}">{{ __('messages.common.edit') }}</a>
                            <form method="POST" action="{{ route('admin.islamic-events.destroy', $event) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">{{ __('messages.common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">{{ __('messages.admin.events.no_events') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $events->links() }}</div>
    </div>
</div>
@endsection
