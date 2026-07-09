@extends('layouts.admin')

@section('title', __('messages.admin.authors.manage'))
@section('heading', __('messages.admin.authors.manage'))

@section('content')
@php($authorForm = $editing ?? new \App\Models\Author())
<div class="grid gap-6 lg:grid-cols-[420px_1fr]">
    <form method="POST" action="{{ $authorForm->exists ? route('admin.authors.update', $authorForm) : route('admin.authors.store') }}" class="admin-panel p-5">
        @csrf
        @if($authorForm->exists) @method('PUT') @endif
        <h2 class="mb-5 text-xl font-extrabold text-slate-950">{{ $authorForm->exists ? __('messages.admin.authors.edit') : __('messages.admin.authors.add') }}</h2>
        <label class="form-label" for="name_en">{{ __('messages.admin.fields.name_en') }}</label>
        <input class="form-input" id="name_en" name="name_en" value="{{ old('name_en', $authorForm->name_en ?: $authorForm->name) }}" required>
        <label class="form-label mt-4" for="name_ur">{{ __('messages.admin.fields.name_ur') }}</label>
        <input class="form-input" id="name_ur" name="name_ur" value="{{ old('name_ur', $authorForm->name_ur) }}" dir="rtl">
        <label class="form-label mt-4" for="bio_en">{{ __('messages.admin.fields.bio_en') }}</label>
        <textarea class="form-input" id="bio_en" name="bio_en" rows="5">{{ old('bio_en', $authorForm->bio_en ?: $authorForm->bio) }}</textarea>
        <label class="form-label mt-4" for="bio_ur">{{ __('messages.admin.fields.bio_ur') }}</label>
        <textarea class="form-input" id="bio_ur" name="bio_ur" rows="5" dir="rtl">{{ old('bio_ur', $authorForm->bio_ur) }}</textarea>
        <button class="btn btn-primary mt-5">{{ $authorForm->exists ? __('messages.common.update') : __('messages.common.create') }}</button>
    </form>
    <div class="admin-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.admin.fields.name') }}</th>
                        <th>{{ __('messages.nav.books') }}</th>
                        <th>{{ __('messages.admin.fields.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($authors as $author)
                        <tr>
                            <td class="font-bold text-slate-950">{{ $author->localized_name }}</td>
                            <td>{{ $author->books_count }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a class="admin-action" href="{{ route('admin.authors.edit', $author) }}">{{ __('messages.common.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.authors.destroy', $author) }}" data-confirm-delete>
                                        @csrf @method('DELETE')
                                        <button class="admin-danger">{{ __('messages.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $authors->links() }}</div>
    </div>
</div>
@endsection
