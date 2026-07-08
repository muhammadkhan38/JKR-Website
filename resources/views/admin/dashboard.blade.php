@extends('layouts.admin')

@section('title', __('messages.admin.dashboard'))
@section('heading', __('messages.admin.dashboard'))

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        [__('messages.admin.dashboard_cards.total_books'), $totalBooks],
        [__('messages.admin.dashboard_cards.categories'), $totalCategories],
        [__('messages.admin.dashboard_cards.authors'), $totalAuthors],
        [__('messages.admin.dashboard_cards.users'), $totalUsers],
        [__('messages.admin.dashboard_cards.audio_files'), $totalAudios],
        [__('messages.admin.dashboard_cards.active_events'), $activeEvents],
    ] as [$label, $value])
        <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 rounded-md border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
        <h2 class="text-lg font-bold text-slate-950">{{ __('messages.admin.dashboard_cards.recent_books') }}</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.admin.fields.title_en') }}</th>
                    <th>{{ __('messages.admin.fields.author') }}</th>
                    <th>{{ __('messages.admin.fields.category') }}</th>
                    <th>{{ __('messages.admin.fields.status') }}</th>
                    <th>{{ __('messages.admin.fields.uploaded_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestBooks as $book)
                    <tr>
                        <td>{{ $book->localized_title }}</td>
                        <td>{{ $book->author?->localized_name }}</td>
                        <td>{{ $book->category?->localized_name }}</td>
                        <td>{{ $book->is_active ? __('messages.common.active') : __('messages.common.inactive') }}</td>
                        <td>{{ $book->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">{{ __('messages.admin.books.no_books') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
