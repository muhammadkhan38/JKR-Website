@extends('layouts.app')

@section('title', 'All Books')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <p class="section-kicker">Library</p>
        <h1 class="section-heading">All Books</h1>
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="mb-8 grid gap-3 rounded-md border border-emerald-100 bg-white p-4 md:grid-cols-6">
        <input name="q" value="{{ request('q') }}" placeholder="Search books..." class="form-input md:col-span-2">
        <select name="category" class="form-input">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="author" class="form-input">
            <option value="">All authors</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" @selected((string) request('author') === (string) $author->id)>{{ $author->name }}</option>
            @endforeach
        </select>
        <select name="language" class="form-input">
            <option value="">All languages</option>
            @foreach($languages as $language)
                <option value="{{ $language }}" @selected(request('language') === $language)>{{ $language }}</option>
            @endforeach
        </select>
        <button class="rounded-md bg-emerald-700 px-4 py-3 font-semibold text-white hover:bg-emerald-800">Filter</button>
        <label class="flex items-center gap-2 text-sm font-medium text-stone-700"><input type="checkbox" name="latest" value="1" @checked(request()->boolean('latest'))> Latest</label>
        <label class="flex items-center gap-2 text-sm font-medium text-stone-700"><input type="checkbox" name="featured" value="1" @checked(request()->boolean('featured'))> Featured</label>
    </form>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">No books found.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
