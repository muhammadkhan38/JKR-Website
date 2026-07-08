@extends('layouts.app')

@section('title', 'My Bookmarks')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">Saved</p>
    <h1 class="section-heading">My Bookmarked Books</h1>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">You have not bookmarked any books yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
