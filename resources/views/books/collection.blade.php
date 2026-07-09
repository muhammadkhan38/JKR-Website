@extends('layouts.app')

@section('title', $title)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker">{{ __('messages.books.library') }}</p>
            <h1 class="section-heading">{{ $title }}</h1>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.reader.back_to_library') }}</a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.books.empty') }}</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
