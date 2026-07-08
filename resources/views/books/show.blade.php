@extends('layouts.app')

@section('title', $book->localized_title)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[320px_1fr]">
        <div>
            @if($book->cover_url)
                <img src="{{ $book->cover_url }}" alt="{{ $book->localized_title }}" class="w-full rounded-md border border-emerald-100 object-cover shadow-sm">
            @else
                <div class="grid aspect-[3/4] place-items-center rounded-md bg-emerald-800 p-6 text-center text-2xl font-bold text-white">{{ $book->localized_title }}</div>
            @endif
        </div>
        <div>
            <div class="flex flex-wrap gap-2">
                @if($book->localized_language)<span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">{{ $book->localized_language }}</span>@endif
                @if($book->is_featured)<span class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">{{ __('messages.common.featured') }}</span>@endif
            </div>
            <h1 class="mt-4 text-3xl font-bold text-emerald-950 sm:text-4xl">{{ $book->localized_title }}</h1>
            <p class="mt-3 text-stone-700">
                {{ __('messages.books.author') }}:
                <strong>{{ $book->author?->localized_name }}</strong>,
                {{ __('messages.books.category') }}:
                <a class="font-semibold text-emerald-700" href="{{ route('categories.show', $book->category) }}">{{ $book->category?->localized_name }}</a>
            </p>
            @if($book->localized_short_description)
                <p class="mt-5 text-lg leading-8 text-stone-700">{{ $book->localized_short_description }}</p>
            @endif
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('books.reader', $book) }}" class="rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.read_online') }}</a>
                @if($book->download_allowed)
                    <a href="{{ route('books.download', $book) }}" class="rounded-md border border-emerald-200 px-5 py-3 font-semibold text-emerald-800 hover:bg-emerald-50">{{ __('messages.common.download_pdf') }}</a>
                @endif
                @auth
                    @php($isBookmarked = auth()->user()->bookmarks()->where('book_id', $book->id)->exists())
                    <button type="button"
                        data-bookmark-button
                        data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                        data-store-url="{{ route('books.bookmark.store', $book) }}"
                        data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                        class="rounded-md px-5 py-3 font-semibold text-white {{ $isBookmarked ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-700 hover:bg-emerald-800' }}">
                        {{ $isBookmarked ? __('messages.books.remove_saved') : __('messages.books.save') }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="rounded-md border border-amber-200 px-5 py-3 font-semibold text-amber-800 hover:bg-amber-50">{{ __('messages.books.login_to_save') }}</a>
                @endauth
            </div>
            @if($book->localized_description)
                <div class="prose mt-8 max-w-none text-stone-800">
                    {!! nl2br(e($book->localized_description)) !!}
                </div>
            @endif
        </div>
    </div>

    @if($book->audios->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">{{ __('messages.home.listening') }}</p>
            <h2 class="section-heading">{{ __('messages.books.related_audio') }}</h2>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
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
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedBooks as $related)
                    @include('partials.book-card', ['book' => $related])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
