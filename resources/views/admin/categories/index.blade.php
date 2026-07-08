@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('heading', 'Manage Categories')

@section('content')
@php($categoryForm = $editing ?? new \App\Models\Category(['is_active' => true]))
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <form method="POST" action="{{ $categoryForm->exists ? route('admin.categories.update', $categoryForm) : route('admin.categories.store') }}" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($categoryForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $categoryForm->exists ? 'Edit Category' : 'Add Category' }}</h2>
        <label class="form-label" for="name">Name</label>
        <input class="form-input" id="name" name="name" value="{{ old('name', $categoryForm->name) }}" required>
        <label class="form-label mt-4" for="description">Description</label>
        <textarea class="form-input" id="description" name="description" rows="5">{{ old('description', $categoryForm->description) }}</textarea>
        <label class="mt-4 flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $categoryForm->is_active))> Active</label>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $categoryForm->exists ? 'Update' : 'Create' }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Books</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->books_count }}</td>
                        <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">Delete</button>
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
