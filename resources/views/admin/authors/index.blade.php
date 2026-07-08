@extends('layouts.admin')

@section('title', 'Manage Authors')
@section('heading', 'Manage Authors')

@section('content')
@php($authorForm = $editing ?? new \App\Models\Author())
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <form method="POST" action="{{ $authorForm->exists ? route('admin.authors.update', $authorForm) : route('admin.authors.store') }}" class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @if($authorForm->exists) @method('PUT') @endif
        <h2 class="mb-4 text-lg font-bold">{{ $authorForm->exists ? 'Edit Author' : 'Add Author' }}</h2>
        <label class="form-label" for="name">Name</label>
        <input class="form-input" id="name" name="name" value="{{ old('name', $authorForm->name) }}" required>
        <label class="form-label mt-4" for="bio">Bio</label>
        <textarea class="form-input" id="bio" name="bio" rows="6">{{ old('bio', $authorForm->bio) }}</textarea>
        <button class="mt-5 rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">{{ $authorForm->exists ? 'Update' : 'Create' }}</button>
    </form>
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Books</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->books_count }}</td>
                        <td class="flex gap-2">
                            <a class="admin-action" href="{{ route('admin.authors.edit', $author) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.authors.destroy', $author) }}" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button class="admin-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $authors->links() }}</div>
    </div>
</div>
@endsection
