<article class="rounded-md border border-emerald-100 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-lg font-bold text-emerald-950">{{ $audio->title }}</h3>
            <p class="text-sm text-stone-600">
                {{ $audio->speaker ?: 'مدرسہ کا بیان' }}
                @if($audio->duration)، {{ $audio->duration }} @endif
            </p>
        </div>
        @if($audio->book)
            <a href="{{ route('books.show', $audio->book) }}" class="text-sm font-semibold text-emerald-700">متعلقہ کتاب</a>
        @endif
    </div>
    @if($audio->description)
        <p class="mt-3 text-sm leading-6 text-stone-700">{{ $audio->description }}</p>
    @endif
    @if($audio->audio_url)
        <audio controls class="mt-4 w-full">
            <source src="{{ $audio->audio_url }}">
        </audio>
    @else
        <p class="mt-4 rounded-md bg-stone-100 px-3 py-2 text-sm text-stone-700">آڈیو فائل ابھی اپ لوڈ نہیں کی گئی۔</p>
    @endif
</article>
