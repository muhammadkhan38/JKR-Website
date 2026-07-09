<article class="surface-card p-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase text-amber-700">{{ __('messages.home.listening') }}</p>
            <h3 class="mt-1 text-xl font-extrabold text-emerald-950">{{ $audio->localized_title }}</h3>
            <p class="mt-1 text-sm font-semibold text-stone-600">
                {{ $audio->localized_speaker ?: __('messages.audios.default_speaker') }}
                @if($audio->duration){{ __('messages.common.separator') }}{{ $audio->duration }} @endif
            </p>
        </div>
        @if($audio->book)
            <a href="{{ route('books.show', $audio->book) }}" class="btn btn-secondary btn-sm">{{ __('messages.audios.related_book') }}</a>
        @endif
    </div>
    @if($audio->localized_description)
        <p class="mt-3 text-sm leading-7 text-stone-700">{{ $audio->localized_description }}</p>
    @endif
    @if($audio->audio_url)
        <audio controls class="mt-4 w-full">
            <source src="{{ $audio->audio_url }}">
        </audio>
    @else
        <p class="mt-4 rounded-xl bg-stone-100 px-3 py-2 text-sm font-semibold text-stone-700">{{ __('messages.audios.file_unavailable') }}</p>
    @endif
</article>
