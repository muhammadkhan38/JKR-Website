@extends('layouts.app')

@section('title', __('messages.jamia.live_title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.jamia.live_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-white/90">{{ __('messages.jamia.live_copy') }}</p>
    </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="live-player">
        <div class="live-off-air">
            <div class="live-off-air__icon" aria-hidden="true">🌙</div>
            <h2>{{ __('messages.jamia.live_unavailable') }}</h2>
            <p>{{ __('messages.jamia.live_unavailable_copy') }}</p>
        </div>
    </div>

    <div class="mt-12">
        <h2 class="section-heading">{{ __('messages.jamia.past_recordings') }}</h2>
        <div class="mt-8 grid gap-4">
            @forelse($recordings as $recording)
                <article class="recording-card">
                    @if($recording->book?->localized_pdf_url)
                        <a href="{{ route('books.reader', $recording->book) }}" class="recording-card__button" aria-label="{{ $recording->book->localized_title }}"><span aria-hidden="true">📖</span></a>
                    @endif
                    <div class="recording-card__content">
                        <h3 class="recording-card__title">{{ $recording->localized_title }}</h3>
                        <p class="recording-card__detail">{{ $recording->localized_speaker ?: __('messages.audios.default_speaker') }} @if($recording->duration) · {{ $recording->duration }} @endif</p>
                        @if($recording->audio_url)
                            <audio controls class="mt-3 w-full"><source src="{{ $recording->audio_url }}"></audio>
                        @endif
                    </div>
                </article>
            @empty
                <p class="surface-card p-6 text-stone-600">{{ __('messages.audios.empty') }}</p>
            @endforelse
        </div>
        <a href="{{ route('audios.index') }}" class="btn btn-secondary mt-7">{{ __('messages.jamia.browse_audio') }}</a>
    </div>
</section>
@endsection
