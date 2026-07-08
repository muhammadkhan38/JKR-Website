@extends('layouts.admin')

@section('title', __('messages.admin.categories.manage'))
@section('heading', __('messages.admin.categories.manage'))

@section('content')
@php($categoryForm = $editing ?? new \App\Models\Category(['is_active' => true]))
<div class="grid gap-6 lg:grid-cols-[420px_1fr]">
    <form method="POST" action="{{ $categoryForm->exists ? route('admin.categories.update', $categoryForm) : route('admin.categories.store') }}" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($categoryForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $categoryForm->exists ? __('messages.admin.categories.edit') : __('messages.admin.categories.add') }}</h2>
        <label class="form-label" for="name_en">{{ __('messages.admin.fields.name_en') }}</label>
        <input class="form-input" id="name_en" name="name_en" value="{{ old('name_en', $categoryForm->name_en ?: $categoryForm->name) }}" required>
        <label class="form-label mt-4" for="name_ur">{{ __('messages.admin.fields.name_ur') }}</label>
        <input class="form-input" id="name_ur" name="name_ur" value="{{ old('name_ur', $categoryForm->name_ur) }}" dir="rtl">
        <label class="form-label mt-4" for="description_en">{{ __('messages.admin.fields.description_en') }}</label>
        <textarea class="form-input" id="description_en" name="description_en" rows="4">{{ old('description_en', $categoryForm->description_en ?: $categoryForm->description) }}</textarea>
        <label class="form-label mt-4" for="description_ur">{{ __('messages.admin.fields.description_ur') }}</label>
        <textarea class="form-input" id="description_ur" name="description_ur" rows="4" dir="rtl">{{ old('description_ur', $categoryForm->description_ur) }}</textarea>
        <label class="mt-4 flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $categoryForm->is_active))> {{ __('messages.common.active') }}</label>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $categoryForm->exists ? __('messages.common.update') : __('messages.common.create') }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
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
                        <td>{{ $category->localized_name }}</td>
                        <td>{{ $category->books_count }}</td>
                        <td>{{ $category->is_active ? __('messages.common.active') : __('messages.common.inactive') }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.categories.edit', $category) }}">{{ __('messages.common.edit') }}</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">{{ __('messages.common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
