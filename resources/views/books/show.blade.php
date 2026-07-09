@extends('layouts.app')

@section('title', $book->localized_title)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[340px_1fr]">
        <div>
            @if($book->cover_url)
                <img src="{{ $book->cover_url }}" alt="{{ $book->localized_title }}" class="w-full rounded-2xl border border-emerald-900/10 object-cover shadow-xl shadow-emerald-950/10">
            @else
                <div class="grid aspect-[3/4] place-items-center rounded-2xl bg-emerald-900 p-6 text-center text-2xl font-extrabold text-white shadow-xl shadow-emerald-950/10">{{ $book->localized_title }}</div>
            @endif
        </div>
        <div class="surface-card p-6 sm:p-8">
            <div class="flex flex-wrap gap-2">
                @if($book->localized_language)<span class="badge badge-green">{{ $book->localized_language }}</span>@endif
                @if($book->is_featured)<span class="badge badge-gold">{{ __('messages.common.featured') }}</span>@endif
                @if($book->is_latest)<span class="badge badge-muted">{{ __('messages.common.latest') }}</span>@endif
            </div>
            <h1 class="mt-5 text-3xl font-extrabold leading-tight text-emerald-950 sm:text-5xl">{{ $book->localized_title }}</h1>
            <div class="mt-5 grid gap-3 text-stone-700 sm:grid-cols-2">
                <p class="rounded-xl bg-emerald-50 px-4 py-3">
                    <span class="block text-xs font-extrabold uppercase text-emerald-700">{{ __('messages.books.author') }}</span>
                    <strong class="mt-1 block text-emerald-950">{{ $book->author?->localized_name }}</strong>
                </p>
                <p class="rounded-xl bg-amber-50 px-4 py-3">
                    <span class="block text-xs font-extrabold uppercase text-amber-700">{{ __('messages.books.category') }}</span>
                    <a class="mt-1 block font-extrabold text-emerald-900" href="{{ route('categories.show', $book->category) }}">{{ $book->category?->localized_name }}</a>
                </p>
            </div>
            @if($book->localized_short_description)
                <p class="mt-6 text-lg leading-9 text-stone-700">{{ $book->localized_short_description }}</p>
            @endif
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('books.reader', $book) }}" class="btn btn-primary btn-lg">{{ __('messages.common.read_online') }}</a>
                @if($book->download_allowed && $book->localized_pdf_url)
                    <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="btn btn-secondary btn-lg">{{ __('messages.common.download_pdf') }}</a>
                @else
                    <span class="inline-flex min-h-12 items-center rounded-xl border border-amber-200 bg-amber-50 px-4 text-sm font-bold text-amber-900">{{ __('messages.books.pdf_unavailable') }}</span>
                @endif
                @auth
                    @php($isBookmarked = auth()->user()->bookmarks()->where('book_id', $book->id)->exists())
                    <button type="button"
                        data-bookmark-button
                        data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                        data-store-url="{{ route('books.bookmark.store', $book) }}"
                        data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                        class="btn btn-lg {{ $isBookmarked ? 'btn-gold' : 'btn-primary' }}">
                        {{ $isBookmarked ? __('messages.books.remove_saved') : __('messages.books.save') }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg border-amber-200 text-amber-900 hover:bg-amber-50">{{ __('messages.books.login_to_save') }}</a>
                @endauth
            </div>
            @if($book->localized_description)
                <div class="mt-8 max-w-none border-t border-emerald-100 pt-6 text-base leading-8 text-stone-800">
                    {!! nl2br(e($book->localized_description)) !!}
                </div>
            @endif
        </div>
    </div>

    @if($book->audios->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">{{ __('messages.home.listening') }}</p>
            <h2 class="section-heading">{{ __('messages.books.related_audio') }}</h2>
            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                @foreach($book->audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @endforeach
            </div>
        </div>
    @endif

    @if($relatedBooks->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">{{ __('messages.books.same_category') }}</p>
            <h2 class="section-heading">{{ __('messages.books.related_books') }}</h2>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedBooks as $related)
                    @include('partials.book-card', ['book' => $related])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
