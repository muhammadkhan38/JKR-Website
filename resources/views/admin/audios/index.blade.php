@extends('layouts.admin')

@section('title', __('messages.admin.audios.manage'))
@section('heading', __('messages.admin.audios.manage'))

@section('content')
@php($audioForm = $editing ?? new \App\Models\Audio(['is_active' => true]))
<div class="grid gap-6 xl:grid-cols-[460px_1fr]">
    <form method="POST" action="{{ $audioForm->exists ? route('admin.audios.update', $audioForm) : route('admin.audios.store') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($audioForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $audioForm->exists ? __('messages.admin.audios.edit') : __('messages.admin.audios.add') }}</h2>
        <div class="space-y-4">
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="title_en">{{ __('messages.admin.fields.title_en') }}</label>
                    <input class="form-input" id="title_en" name="title_en" value="{{ old('title_en', $audioForm->title_en ?: $audioForm->title) }}" required>
                </div>
                <div>
                    <label class="form-label" for="title_ur">{{ __('messages.admin.fields.title_ur') }}</label>
                    <input class="form-input" id="title_ur" name="title_ur" value="{{ old('title_ur', $audioForm->title_ur) }}" dir="rtl">
                </div>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="speaker_en">{{ __('messages.admin.fields.speaker_en') }}</label>
                    <input class="form-input" id="speaker_en" name="speaker_en" value="{{ old('speaker_en', $audioForm->speaker_en ?: $audioForm->speaker) }}">
                </div>
                <div>
                    <label class="form-label" for="speaker_ur">{{ __('messages.admin.fields.speaker_ur') }}</label>
                    <input class="form-input" id="speaker_ur" name="speaker_ur" value="{{ old('speaker_ur', $audioForm->speaker_ur) }}" dir="rtl">
                </div>
            </div>
            <div>
                <label class="form-label" for="duration">{{ __('messages.admin.fields.duration') }}</label>
                <input class="form-input" id="duration" name="duration" value="{{ old('duration', $audioForm->duration) }}" placeholder="00:12:30">
            </div>
            <div>
                <label class="form-label" for="description_en">{{ __('messages.admin.fields.description_en ') }}</label>
                <textarea class="form-input" id="description_en" name="description_en" rows="3">{{ old('description_en', $audioForm->description_en ?: $audioForm->description) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="description_ur">{{ __('messages.admin.fields.description_ur') }}</label>
                <textarea class="form-input" id="description_ur" name="description_ur" rows="3" dir="rtl">{{ old('description_ur', $audioForm->description_ur) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="audio_file">{{ __('messages.admin.fields.audio_file') }}</label>
                <input class="form-input" id="audio_file" type="file" name="audio_file" accept=".mp3,.wav,.m4a" data-file-label="#audio-file-name" @required(! $audioForm->exists)>
                <p id="audio-file-name" class="mt-2 text-sm text-slate-500">{{ $audioForm->audio_file ? __('messages.common.current_file', ['file' => $audioForm->audio_file]) : '' }}</p>
            </div>
            <div>
                <label class="form-label" for="book_id">{{ __('messages.admin.fields.related_book') }}</label>
                <select class="form-input" id="book_id" name="book_id">
                    <option value="">{{ __('messages.common.none') }}</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" @selected(old('book_id', $audioForm->book_id) == $book->id)>{{ $book->localized_title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="category_id">{{ __('messages.admin.fields.category') }}</label>
                    <select class="form-input" id="category_id" name="category_id">
                        <option value="">{{ __('messages.common.none') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $audioForm->category_id) == $category->id)>{{ $category->localized_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="islamic_event_id">{{ __('messages.admin.fields.collection') }}</label>
                    <select class="form-input" id="islamic_event_id" name="islamic_event_id">
                        <option value="">{{ __('messages.common.none') }}</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @selected(old('islamic_event_id', $audioForm->islamic_event_id) == $event->id)>{{ $event->localized_title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $audioForm->is_active))> {{ __('messages.common.active') }}</label>
        </div>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $audioForm->exists ? __('messages.common.update') : __('messages.common.create') }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.admin.audios.audio') }}</th>
                    <th>{{ __('messages.admin.audios.linked_to') }}</th>
                    <th>{{ __('messages.admin.fields.status') }}</th>
                    <th>{{ __('messages.admin.fields.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audios as $audio)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $audio->localized_title }}</div>
                            <div class="text-xs text-slate-500">{{ $audio->localized_speaker ?: __('messages.admin.audios.no_speaker') }}</div>
                        </td>
                        <td>{{ $audio->book?->localized_title ?? $audio->category?->localized_name ?? $audio->islamicEvent?->localized_title ?? __('messages.common.general') }}</td>
                        <td>{{ $audio->is_active ? __('messages.common.active') : __('messages.common.inactive') }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.audios.edit', $audio) }}">{{ __('messages.common.edit') }}</a>
                            <form method="POST" action="{{ route('admin.audios.destroy', $audio) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">{{ __('messages.common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">{{ __('messages.admin.audios.no_audios') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $audios->links() }}</div>
    </div>
</div>
@endsection
