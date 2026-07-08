<article class="rounded-md border border-emerald-100 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-lg font-bold text-emerald-950">{{ $audio->localized_title }}</h3>
            <p class="text-sm text-stone-600">
                {{ $audio->localized_speaker ?: __('messages.audios.default_speaker') }}
                @if($audio->duration){{ __('messages.common.separator') }}{{ $audio->duration }} @endif
            </p>
        </div>
        @if($audio->book)
            <a href="{{ route('books.show', $audio->book) }}" class="text-sm font-semibold text-emerald-700">{{ __('messages.audios.related_book') }}</a>
        @endif
    </div>
    @if($audio->localized_description)
        <p class="mt-3 text-sm leading-6 text-stone-700">{{ $audio->localized_description }}</p>
    @endif
    @if($audio->audio_url)
        <audio controls class="mt-4 w-full">
            <source src="{{ $audio->audio_url }}">
        </audio>
    @else
        <p class="mt-4 rounded-md bg-stone-100 px-3 py-2 text-sm text-stone-700">{{ __('messages.audios.file_unavailable') }}</p>
    @endif
</article>
