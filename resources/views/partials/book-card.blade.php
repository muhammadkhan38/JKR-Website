@php
    $isBookmarked = auth()->check() && auth()->user()->bookmarks()->where('book_id', $book->id)->exists();
    $summary = $book->localized_short_description ?: Str::limit(strip_tags($book->localized_description), 130);
@endphp
<article class="book-card">
    <a href="{{ route('books.show', $book) }}" class="block overflow-hidden" aria-label="{{ $book->localized_title }}">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->localized_title }}" loading="lazy" decoding="async" class="book-cover">
        @else
            <div class="book-cover-fallback">{{ $book->localized_title }}</div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="mb-3 flex flex-wrap gap-2">
            @if($book->is_featured)<span class="badge badge-gold">{{ __('messages.common.featured') }}</span>@endif
            @if($book->is_latest)<span class="badge badge-green">{{ __('messages.common.latest') }}</span>@endif
            @if($book->localized_language)<span class="badge badge-muted">{{ $book->localized_language }}</span>@endif
        </div>
        <h3 class="text-xl font-extrabold leading-snug text-emerald-950">
            <a href="{{ route('books.show', $book) }}">{{ $book->localized_title }}</a>
        </h3>
        <p class="mt-2 text-sm font-semibold leading-6 text-stone-600">{{ collect([$book->author?->localized_name, $book->category?->localized_name])->filter()->implode(__('messages.common.separator')) }}</p>
        @if($summary)
            <p class="mt-3 line-clamp-3 text-sm leading-7 text-stone-700">{{ $summary }}</p>
        @endif
        <div class="mt-auto flex flex-wrap gap-2 pt-6">
            <a href="{{ route('books.reader', $book) }}" class="btn btn-primary btn-sm">{{ __('messages.common.read_online') }}</a>
            @if($book->download_allowed && $book->localized_pdf_url)
                <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">{{ __('messages.common.download_pdf') }}</a>
            @endif
            <a href="{{ route('books.show', $book) }}" class="btn btn-muted btn-sm">{{ __('messages.common.details') }}</a>
            @auth
                <button type="button"
                    data-bookmark-button
                    data-bookmarked="{{ $isBookmarked ? 1 : 0 }}"
                    data-store-url="{{ route('books.bookmark.store', $book) }}"
                    data-delete-url="{{ route('books.bookmark.destroy', $book) }}"
                    class="btn btn-sm {{ $isBookmarked ? 'btn-gold' : 'btn-primary' }}"
                    aria-label="{{ $isBookmarked ? __('messages.books.remove_saved') : __('messages.books.save') }}">
                    {{ $isBookmarked ? __('messages.books.remove_saved') : __('messages.books.save') }}
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm border-amber-200 text-amber-900 hover:bg-amber-50">{{ __('messages.books.login_to_save') }}</a>
            @endauth
        </div>
    </div>
</article>
