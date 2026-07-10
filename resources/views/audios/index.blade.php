@extends('layouts.app')

@section('title', __('messages.audios.title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-7xl px-4 py-14 text-center sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-[0.14em] text-[var(--mint-300)]">{{ __('messages.audios.kicker') }}</p>
        <h1 class="mt-3 font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.audios.title') }}</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <form method="GET" class="search-panel mb-8 sm:flex sm:items-center sm:gap-3">
        <label for="audio-search" class="sr-only">{{ __('messages.audios.search_placeholder') }}</label>
        <input id="audio-search" type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.audios.search_placeholder') }}" class="form-input flex-1">
        <div class="mt-3 flex gap-2 sm:mt-0">
            <button class="btn btn-primary">{{ __('messages.common.search') }}</button>
            @if(request('q'))
                <a href="{{ route('audios.index') }}" class="btn btn-muted">{{ __('messages.common.cancel') }}</a>
            @endif
        </div>
    </form>
    <div class="grid gap-5 lg:grid-cols-2">
        @forelse($audios as $audio)
            @include('partials.audio-card', ['audio' => $audio])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.audios.empty') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $audios->links() }}</div>
</section>
@endsection
