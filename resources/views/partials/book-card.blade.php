@php
    $isBookmarked = auth()->check() && auth()->user()->bookmarks()->where('book_id', $book->id)->exists();
@endphp
<article class="flex h-full flex-col overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
    <a href="{{ route('books.show', $book) }}" class="block">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="h-52 w-full object-cover">
        @else
            <div class="grid h-52 place-items-center bg-emerald-800 px-6 text-center text-lg font-bold text-white">{{ $book->title }}</div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-4">
        <div class="mb-3 flex flex-wrap gap-2">
            @if($book->is_featured)<span class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">منتخب</span>@endif
            @if($book->is_latest)<span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">نئی</span>@endif
            <span class="rounded bg-stone-100 px-2 py-1 text-xs font-semibold text-stone-700">{{ $book->language }}</span>
        </div>
        <h3 class="text-lg font-bold leading-snug text-emerald-950"><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></h3>
        <p class="mt-1 text-sm text-stone-600">{{ $book->author?->name }}، {{ $book->category?->name }}</p>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-700">{{ $book->short_description ?: Str::limit(strip_tags($book->description), 130) }}</p>
        <div class="mt-auto flex flex-wrap gap-2 pt-5">
            <a href="{{ route('books.reader', $book) }}" class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">مطالعہ کریں</a>
            <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">تفصیل</a>
            @auth
                <button type="button"
                    data-bookmark-button
                    data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                    data-store-url="{{ route('books.bookmark.store', $book) }}"
                    data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                    class="rounded-md px-3 py-2 text-sm font-semibold text-white {{ $isBookmarked ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-700 hover:bg-emerald-800' }}">
                    {{ $isBookmarked ? 'محفوظ فہرست سے نکالیں' : 'کتاب محفوظ کریں' }}
                </button>
            @else
                <a href="{{ route('login') }}" class="rounded-md border border-amber-200 px-3 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-50">محفوظ کرنے کے لیے لاگ اِن کریں</a>
            @endauth
        </div>
    </div>
</article>
