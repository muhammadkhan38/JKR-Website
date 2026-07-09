@extends('layouts.admin')

@section('title', __('messages.admin.books.manage'))
@section('heading', __('messages.admin.books.manage'))
@section('actions')
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">{{ __('messages.admin.books.add') }}</a>
@endsection

@section('content')
<div class="admin-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.admin.books.book') }}</th>
                    <th>{{ __('messages.admin.fields.author') }}</th>
                    <th>{{ __('messages.admin.fields.category') }}</th>
                    <th>{{ __('messages.admin.books.markers') }}</th>
                    <th>{{ __('messages.admin.fields.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>
                            <div class="font-extrabold text-slate-950">{{ $book->localized_title }}</div>
                            <div class="mt-1 text-xs font-semibold text-slate-500">{{ collect([$book->localized_language, $book->created_at->format('d-m-Y')])->filter()->implode(__('messages.common.separator')) }}</div>
                        </td>
                        <td>{{ $book->author?->localized_name }}</td>
                        <td>{{ $book->category?->localized_name }}</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @if($book->is_active)<span class="status-badge bg-emerald-100 text-emerald-800">{{ __('messages.common.active') }}</span>@endif
                                @if($book->is_featured)<span class="status-badge bg-amber-100 text-amber-800">{{ __('messages.common.featured') }}</span>@endif
                                @if($book->is_latest)<span class="status-badge bg-sky-100 text-sky-800">{{ __('messages.common.latest') }}</span>@endif
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.books.edit', $book) }}" class="admin-action">{{ __('messages.common.edit') }}</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" data-confirm-delete>
                                    @csrf @method('DELETE')
                                    <button class="admin-danger" type="submit">{{ __('messages.common.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">{{ __('messages.admin.books.no_books') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $books->links() }}</div>
</div>
@endsection
