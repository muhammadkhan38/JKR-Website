@extends('layouts.app')

@section('title', __('messages.books.all_title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-7xl px-4 py-14 text-center sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-[0.14em] text-[var(--mint-300)]">{{ __('messages.books.library') }}</p>
        <h1 class="mt-3 font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.books.all_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-white/90">{{ __('messages.home.default_intro') }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-7 flex justify-end">
        <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.common.view_all') }}</a>
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="search-panel mb-8">
        <div class="grid gap-3 md:grid-cols-6">
            <div class="md:col-span-2">
                <label for="books-search" class="sr-only">{{ __('messages.books.search_placeholder') }}</label>
                <input id="books-search" type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.books.search_placeholder') }}" class="form-input">
            </div>
            <label class="sr-only" for="category">{{ __('messages.books.all_categories') }}</label>
            <select id="category" name="category" class="form-input">
                <option value="">{{ __('messages.books.all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->localized_name }}</option>
                @endforeach
            </select>
            <label class="sr-only" for="author">{{ __('messages.books.all_authors') }}</label>
            <select id="author" name="author" class="form-input">
                <option value="">{{ __('messages.books.all_authors') }}</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected((string) request('author') === (string) $author->id)>{{ $author->localized_name }}</option>
                @endforeach
            </select>
            <label class="sr-only" for="language">{{ __('messages.books.all_languages') }}</label>
            <select id="language" name="language" class="form-input">
                <option value="">{{ __('messages.books.all_languages') }}</option>
                @foreach($languages as $language)
                    <option value="{{ $language->language }}" @selected(request('language') === $language->language)>{{ $language->localized_language }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">{{ __('messages.common.filter') }}</button>
        </div>
        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-3">
                <label class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-stone-700">
                    <input type="checkbox" name="latest" value="1" @checked(request()->boolean('latest'))>
                    {{ __('messages.books.latest_only') }}
                </label>
                <label class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-stone-700">
                    <input type="checkbox" name="featured" value="1" @checked(request()->boolean('featured'))>
                    {{ __('messages.books.featured_only') }}
                </label>
            </div>
            @if(request()->hasAny(['q', 'category', 'author', 'language', 'latest', 'featured']))
                <a href="{{ route('books.index') }}" class="btn btn-muted btn-sm">{{ __('messages.common.cancel') }}</a>
            @endif
        </div>
    </form>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.books.empty') }}</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $books->links() }}</div>
</section>
@endsection
