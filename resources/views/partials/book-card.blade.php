@php
    $isBookmarked = auth()->check() && auth()->user()->bookmarks()->where('book_id', $book->id)->exists();
    $summary = $book->localized_short_description ?: Str::limit(strip_tags($book->localized_description), 130);
@endphp
<article class="flex h-full flex-col overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
    <a href="{{ route('books.show', $book) }}" class="block">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->localized_title }}" class="h-52 w-full object-cover">
        @else
            <div class="grid h-52 place-items-center bg-emerald-800 px-6 text-center text-lg font-bold text-white">{{ $book->localized_title }}</div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-4">
        <div class="mb-3 flex flex-wrap gap-2">
            @if($book->is_featured)<span class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">{{ __('messages.common.featured') }}</span>@endif
            @if($book->is_latest)<span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">{{ __('messages.common.latest') }}</span>@endif
            @if($book->localized_language)<span class="rounded bg-stone-100 px-2 py-1 text-xs font-semibold text-stone-700">{{ $book->localized_language }}</span>@endif
        </div>
        <h3 class="text-lg font-bold leading-snug text-emerald-950"><a href="{{ route('books.show', $book) }}">{{ $book->localized_title }}</a></h3>
        <p class="mt-1 text-sm text-stone-600">{{ collect([$book->author?->localized_name, $book->category?->localized_name])->filter()->implode(__('messages.common.separator')) }}</p>
        @if($summary)
            <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-700">{{ $summary }}</p>
        @endif
        <div class="mt-auto flex flex-wrap gap-2 pt-5">
            <a href="{{ route('books.reader', $book) }}" class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.read_online') }}</a>
            @if($book->download_allowed && $book->localized_pdf_url)
                <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">{{ __('messages.common.download_pdf') }}</a>
            @endif
            <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">{{ __('messages.common.details') }}</a>
            @auth
                <button type="button"
                    data-bookmark-button
                    data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                    data-store-url="{{ route('books.bookmark.store', $book) }}"
                    data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                    class="rounded-md px-3 py-2 text-sm font-semibold text-white {{ $isBookmarked ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-700 hover:bg-emerald-800' }}">
                    {{ $isBookmarked ? __('messages.books.remove_saved') : __('messages.books.save') }}
                </button>
            @else
                <a href="{{ route('login') }}" class="rounded-md border border-amber-200 px-3 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-50">{{ __('messages.books.login_to_save') }}</a>
            @endauth
        </div>
    </div>
</article>
