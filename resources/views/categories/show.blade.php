@extends('layouts.app')

@section('title', $category->localized_name)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">{{ __('messages.books.category') }}</p>
    <h1 class="section-heading">{{ $category->localized_name }}</h1>
    @if($category->localized_description)
        <p class="mt-3 max-w-3xl leading-7 text-stone-700">{{ $category->localized_description }}</p>
    @endif
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">{{ __('messages.books.category_empty') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
