@extends('layouts.app')

@section('title', ($settings['madrasa_name'] ?? __('messages.meta.site_name')).' - '.__('messages.home.title_suffix'))

@section('content')
<section class="bg-emerald-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
        <div class="self-center">
            <p class="text-sm font-semibold tracking-wide text-amber-300">{{ __('messages.home.kicker') }}</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">{{ $settings['madrasa_name'] ?? __('messages.meta.site_name') }}</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-emerald-100">{{ $settings['short_about'] ?? __('messages.home.default_intro') }}</p>
            <form action="{{ route('books.index') }}" method="GET" class="mt-8 flex flex-col gap-3 rounded-md bg-white p-3 sm:flex-row">
                <input type="search" name="q" placeholder="{{ __('messages.home.search_placeholder') }}" class="min-h-12 flex-1 rounded-md border border-emerald-100 px-4 text-stone-900 outline-none focus:border-amber-400">
                <button class="rounded-md bg-amber-500 px-6 py-3 font-semibold text-emerald-950 hover:bg-amber-400">{{ __('messages.home.search_button') }}</button>
            </form>
        </div>
        <div class="min-h-72 overflow-hidden rounded-md border border-emerald-700 bg-emerald-900">
            @if(! empty($settings['homepage_banner']))
                <img src="{{ Storage::disk('public')->url($settings['homepage_banner']) }}" alt="{{ __('messages.home.banner_alt') }}" class="h-full w-full object-cover">
            @else
                <div class="grid h-full place-items-center p-8 text-center">
                    <div>
                        <p class="text-6xl font-bold text-amber-300">{{ __('messages.home.iqra') }}</p>
                        <p class="mt-4 text-xl font-semibold">{{ __('messages.home.iqra_line') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <p class="section-kicker">{{ __('messages.home.recently_added') }}</p>
            <h2 class="section-heading">{{ __('messages.common.latest_books') }}</h2>
        </div>
        <a href="{{ route('books.index', ['latest' => 1]) }}" class="text-sm font-semibold text-emerald-700">{{ __('messages.common.view_all') }}</a>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($latestBooks as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">{{ __('messages.home.empty_books') }}</p>
        @endforelse
    </div>
</section>

@if($featuredBooks->isNotEmpty())
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="section-kicker">{{ __('messages.home.madrasa_picks') }}</p>
            <h2 class="section-heading">{{ __('messages.common.featured_books') }}</h2>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($events->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <p class="section-kicker">{{ __('messages.home.event_kicker') }}</p>
        <h2 class="section-heading">{{ __('messages.home.active_collections') }}</h2>
    </div>
    <div class="grid gap-5 lg:grid-cols-2">
        @foreach($events as $event)
            <article class="overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
                @if($event->banner_url)
                    <img src="{{ $event->banner_url }}" alt="{{ $event->localized_title }}" class="h-44 w-full object-cover">
                @endif
                <div class="p-5">
                    <h3 class="text-xl font-bold text-emerald-950">{{ $event->localized_title }}</h3>
                    @if($event->localized_description)
                        <p class="mt-2 text-sm leading-6 text-stone-700">{{ $event->localized_description }}</p>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($event->books as $book)
                            <a href="{{ route('books.show', $book) }}" class="rounded bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-800">{{ $book->localized_title }}</a>
                        @endforeach
                    </div>
                    <a href="{{ route('events.show', $event) }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">{{ __('messages.home.open_collection') }}</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div>
            <p class="section-kicker">{{ __('messages.home.browse_kicker') }}</p>
            <h2 class="section-heading">{{ __('messages.home.important_categories') }}</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="rounded-md border border-emerald-100 bg-emerald-50 p-4 hover:border-emerald-300">
                        <span class="font-semibold text-emerald-950">{{ $category->localized_name }}</span>
                        <span class="mt-1 block text-sm text-emerald-700">{{ __('messages.common.books_count', ['count' => $category->books_count]) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <div class="mb-5 flex items-end justify-between">
                <div>
                    <p class="section-kicker">{{ __('messages.home.listening') }}</p>
                    <h2 class="section-heading">{{ __('messages.home.latest_audio') }}</h2>
                </div>
                <a href="{{ route('audios.index') }}" class="text-sm font-semibold text-emerald-700">{{ __('messages.home.all_audio') }}</a>
            </div>
            <div class="space-y-4">
                @forelse($audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @empty
                    <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">{{ __('messages.home.empty_audio') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-md bg-emerald-800 p-6 text-white sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">{{ __('messages.home.help_title') }}</h2>
            <p class="mt-2 text-emerald-100">{{ __('messages.home.help_text') }}</p>
        </div>
        <div class="mt-5 flex flex-wrap gap-3 sm:mt-0">
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="rounded-md bg-amber-400 px-4 py-2 font-semibold text-emerald-950">{{ __('messages.common.whatsapp') }}</a>
            @endif
            <a href="{{ route('contact') }}" class="rounded-md border border-white/30 px-4 py-2 font-semibold text-white">{{ __('messages.common.contact') }}</a>
        </div>
    </div>
</section>
@endsection
