@extends('layouts.app')

@section('title', $book->title)

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[320px_1fr]">
        <div>
            @if($book->cover_url)
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full rounded-md border border-emerald-100 object-cover shadow-sm">
            @else
                <div class="grid aspect-[3/4] place-items-center rounded-md bg-emerald-800 p-6 text-center text-2xl font-bold text-white">{{ $book->title }}</div>
            @endif
        </div>
        <div>
            <div class="flex flex-wrap gap-2">
                <span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">{{ $book->language }}</span>
                @if($book->is_featured)<span class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">منتخب</span>@endif
            </div>
            <h1 class="mt-4 text-3xl font-bold text-emerald-950 sm:text-4xl">{{ $book->title }}</h1>
            <p class="mt-3 text-stone-700">مصنف: <strong>{{ $book->author?->name }}</strong>، زمرہ: <a class="font-semibold text-emerald-700" href="{{ route('categories.show', $book->category) }}">{{ $book->category?->name }}</a></p>
            <p class="mt-5 text-lg leading-8 text-stone-700">{{ $book->short_description }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('books.reader', $book) }}" class="rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">آن لائن مطالعہ کریں</a>
                @if($book->download_allowed)
                    <a href="{{ route('books.download', $book) }}" class="rounded-md border border-emerald-200 px-5 py-3 font-semibold text-emerald-800 hover:bg-emerald-50">PDF ڈاؤن لوڈ کریں</a>
                @endif
                @auth
                    @php($isBookmarked = auth()->user()->bookmarks()->where('book_id', $book->id)->exists())
                    <button type="button"
                        data-bookmark-button
                        data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                        data-store-url="{{ route('books.bookmark.store', $book) }}"
                        data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                        class="rounded-md px-5 py-3 font-semibold text-white {{ $isBookmarked ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-700 hover:bg-emerald-800' }}">
                        {{ $isBookmarked ? 'محفوظ فہرست سے نکالیں' : 'کتاب محفوظ کریں' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="rounded-md border border-amber-200 px-5 py-3 font-semibold text-amber-800 hover:bg-amber-50">محفوظ کرنے کے لیے لاگ اِن کریں</a>
                @endauth
            </div>
            @if($book->description)
                <div class="prose mt-8 max-w-none text-stone-800">
                    {!! nl2br(e($book->description)) !!}
                </div>
            @endif
        </div>
    </div>

    @if($book->audios->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">سماعت</p>
            <h2 class="section-heading">متعلقہ آڈیو</h2>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                @foreach($book->audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @endforeach
            </div>
        </div>
    @endif

    @if($relatedBooks->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">اسی زمرے سے</p>
            <h2 class="section-heading">متعلقہ کتب</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedBooks as $related)
                    @include('partials.book-card', ['book' => $related])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
