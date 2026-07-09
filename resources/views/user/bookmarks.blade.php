@extends('layouts.app')

@section('title', __('messages.bookmarks.title'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="section-kicker">{{ __('messages.bookmarks.kicker') }}</p>
        <h1 class="section-heading">{{ __('messages.bookmarks.heading') }}</h1>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.bookmarks.empty') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
