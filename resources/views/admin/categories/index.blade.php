@extends('layouts.admin')

@section('title', __('messages.admin.categories.manage'))
@section('heading', __('messages.admin.categories.manage'))

@section('content')
@php($categoryForm = $editing ?? new \App\Models\Category(['is_active' => true]))
<div class="grid gap-6 lg:grid-cols-[420px_1fr]">
    <form method="POST" action="{{ $categoryForm->exists ? route('admin.categories.update', $categoryForm) : route('admin.categories.store') }}" class="admin-panel p-5">
        @csrf
        @if($categoryForm->exists) @method('PUT') @endif
        <h2 class="mb-5 text-xl font-extrabold text-slate-950">{{ $categoryForm->exists ? __('messages.admin.categories.edit') : __('messages.admin.categories.add') }}</h2>
        <label class="form-label" for="name_en">{{ __('messages.admin.fields.name_en') }}</label>
        <input class="form-input" id="name_en" name="name_en" value="{{ old('name_en', $categoryForm->name_en ?: $categoryForm->name) }}" required>
        <label class="form-label mt-4" for="name_ur">{{ __('messages.admin.fields.name_ur') }}</label>
        <input class="form-input" id="name_ur" name="name_ur" value="{{ old('name_ur', $categoryForm->name_ur) }}" dir="rtl">
        <label class="form-label mt-4" for="description_en">{{ __('messages.admin.fields.description_en') }}</label>
        <textarea class="form-input" id="description_en" name="description_en" rows="4">{{ old('description_en', $categoryForm->description_en ?: $categoryForm->description) }}</textarea>
        <label class="form-label mt-4" for="description_ur">{{ __('messages.admin.fields.description_ur') }}</label>
        <textarea class="form-input" id="description_ur" name="description_ur" rows="4" dir="rtl">{{ old('description_ur', $categoryForm->description_ur) }}</textarea>
        <label class="mt-4 inline-flex min-h-12 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-slate-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $categoryForm->is_active))>
            {{ __('messages.common.active') }}
        </label>
        <button class="btn btn-primary mt-5">{{ $categoryForm->exists ? __('messages.common.update') : __('messages.common.create') }}</button>
    </form>
    <div class="admin-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.admin.fields.name') }}</th>
                        <th>{{ __('messages.nav.books') }}</th>
                        <th>{{ __('messages.admin.fields.status') }}</th>
                        <th>{{ __('messages.admin.fields.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="font-bold text-slate-950">{{ $category->localized_name }}</td>
                            <td>{{ $category->books_count }}</td>
                            <td><span class="status-badge {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">{{ $category->is_active ? __('messages.common.active') : __('messages.common.inactive') }}</span></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a class="admin-action" href="{{ route('admin.categories.edit', $category) }}">{{ __('messages.common.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm-delete>
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
        <div class="p-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
