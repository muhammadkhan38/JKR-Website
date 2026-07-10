@extends('layouts.app')

@section('title', __('messages.jamia.announcements_title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.jamia.announcements_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-white/90">{{ __('messages.jamia.announcements_copy') }}</p>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="grid gap-6">
        @foreach($announcements as $announcement)
            <article class="announcement-card">
                <div class="announcement-card__meta">
                    <span class="announcement-card__tag">{{ $announcement['type'] }}</span>
                    <span class="announcement-card__date">{{ $announcement['date'] }}</span>
                </div>
                <h2>{{ $announcement['title'] }}</h2>
                <p class="announcement-card__copy">{{ $announcement['body'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
