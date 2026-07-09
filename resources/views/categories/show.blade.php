@extends('layouts.app')

@section('title', $category->localized_name)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 max-w-3xl">
        <p class="section-kicker">{{ __('messages.books.category') }}</p>
        <h1 class="section-heading">{{ $category->localized_name }}</h1>
        @if($category->localized_description)
            <p class="section-copy">{{ $category->localized_description }}</p>
        @endif
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.books.category_empty') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
