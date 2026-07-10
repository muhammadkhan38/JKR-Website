@extends('layouts.app')

@section('title', __('messages.jamia.lecture_schedule'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.jamia.lectures_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-white/90">{{ __('messages.jamia.lectures_copy') }}</p>
    </div>
</section>

<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="schedule-tabs" role="tablist" aria-label="{{ __('messages.jamia.schedule_label') }}">
        <button type="button" class="schedule-tab" id="upcoming-tab" data-schedule-tab="upcoming" role="tab" aria-selected="true" aria-controls="upcoming-panel">{{ __('messages.jamia.upcoming') }}</button>
        <button type="button" class="schedule-tab" id="weekly-tab" data-schedule-tab="weekly" role="tab" aria-selected="false" aria-controls="weekly-panel">{{ __('messages.jamia.weekly_classes') }}</button>
        <button type="button" class="schedule-tab" id="tours-tab" data-schedule-tab="tours" role="tab" aria-selected="false" aria-controls="tours-panel">{{ __('messages.jamia.city_tours') }}</button>
    </div>

    <div id="upcoming-panel" class="schedule-panel" data-schedule-panel="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
        <div class="schedule-list">
            @foreach($upcoming as $lecture)
                <article class="schedule-card">
                    <div class="date-tile" aria-label="{{ $lecture['day'] }} {{ $lecture['month'] }}">
                        <span class="date-tile__day">{{ $lecture['day'] }}</span>
                        <span class="date-tile__month">{{ $lecture['month'] }}</span>
                    </div>
                    <div class="schedule-card__content">
                        <h2 class="schedule-card__title">{{ $lecture['topic'] }}</h2>
                        <p class="schedule-card__meta">🎙️ {{ $lecture['speaker'] }}</p>
                        <p class="schedule-card__meta">📍 {{ $lecture['venue'] }}, {{ $lecture['city'] }} · 🕐 {{ $lecture['time'] }}</p>
                    </div>
                    <span class="schedule-city">{{ $lecture['city'] }}</span>
                </article>
            @endforeach
        </div>
    </div>

    <div id="weekly-panel" class="schedule-panel" data-schedule-panel="weekly" role="tabpanel" aria-labelledby="weekly-tab" hidden>
        <div class="schedule-list">
            @foreach($weekly as $class)
                <article class="schedule-card">
                    <div class="date-tile date-tile--soft" aria-label="{{ $class['day'] }} {{ $class['time'] }}">
                        <span class="text-center text-sm font-bold leading-tight">{{ $class['day'] }}</span>
                        <span class="date-tile__month normal-case tracking-normal">{{ $class['time'] }}</span>
                    </div>
                    <div class="schedule-card__content">
                        <h2 class="schedule-card__title">{{ $class['title'] }}</h2>
                        <p class="schedule-card__meta">🎙️ {{ $class['teacher'] }}</p>
                        <p class="schedule-card__meta">📍 {{ $class['venue'] }} · {{ __('messages.jamia.open_to_all') }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    <div id="tours-panel" class="schedule-panel" data-schedule-panel="tours" role="tabpanel" aria-labelledby="tours-tab" hidden>
        <div class="schedule-list">
            @foreach($tours as $tour)
                <article class="tour-card">
                    <div class="tour-card__header">
                        <h2>📍 {{ $tour['city'] }}</h2>
                        <span class="tour-card__dates">{{ $tour['dates'] }}</span>
                    </div>
                    <div class="tour-card__body">
                        @foreach($tour['events'] as $event)
                            <div class="tour-event">
                                <span class="tour-event__time">{{ $event['when'] }}</span>
                                <div>
                                    <p class="tour-event__title">{{ $event['title'] }}</p>
                                    <p class="tour-event__detail">{{ $event['speaker'] }} · {{ $event['venue'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
