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
        <div class="live-player__status"><span class="live-dot" aria-hidden="true"></span> {{ __('messages.jamia.on_air') }}</div>
        <div class="live-player__body">
            <button type="button" class="live-play-button" data-live-toggle aria-pressed="false" data-start-label="{{ __('messages.jamia.start_live_animation') }}" data-pause-label="{{ __('messages.jamia.pause_live_animation') }}" aria-label="{{ __('messages.jamia.toggle_live_animation') }}"><span data-live-icon aria-hidden="true">▶</span></button>
            <div class="min-w-0 flex-1">
                <h2 class="live-player__title">{{ $live['title'] }}</h2>
                <p class="live-player__meta">{{ $live['speaker'] }} · {{ __('messages.jamia.started', ['time' => $live['started']]) }} · {{ $live['listeners'] }}</p>
            </div>
            <div class="equalizer" data-equalizer aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
        </div>
    </div>

    <div class="mt-12">
        <h2 class="section-heading">{{ __('messages.jamia.past_recordings') }}</h2>
        <div class="mt-8 grid gap-4">
            @foreach($recordings as $recording)
                <article class="recording-card">
                    <a href="{{ route('audios.index') }}" class="recording-card__button" aria-label="{{ __('messages.jamia.browse_audio_label') }}"><span aria-hidden="true">▶</span></a>
                    <div class="recording-card__content">
                        <h3 class="recording-card__title">{{ $recording['title'] }}</h3>
                        <p class="recording-card__detail">{{ $recording['speaker'] }} · {{ $recording['date'] }} · {{ $recording['duration'] }}</p>
                    </div>
                    <span class="schedule-city">{{ $recording['duration'] }}</span>
                </article>
            @endforeach
        </div>
        <a href="{{ route('audios.index') }}" class="btn btn-secondary mt-7">{{ __('messages.jamia.browse_audio') }}</a>
    </div>
</section>
@endsection
