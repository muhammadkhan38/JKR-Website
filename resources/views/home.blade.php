@extends('layouts.app')

@section('title', ($settings['madrasa_name'] ?? __('messages.meta.site_name')).' - '.__('messages.home.title_suffix'))

@section('content')
<section class="islamic-pattern text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-20">
        <div class="self-center">
            <p class="text-sm font-extrabold uppercase text-amber-200">{{ __('messages.home.kicker') }}</p>
            <h1 class="mt-4 max-w-4xl text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">{{ $settings['madrasa_name'] ?? __('messages.meta.site_name') }}</h1>
            <p class="mt-6 max-w-2xl text-lg leading-9 text-emerald-50/90">{{ $settings['short_about'] ?? __('messages.home.default_intro') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('books.index') }}" class="btn btn-gold btn-lg">{{ __('messages.footer.all_books') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary btn-lg border-white/25 bg-white/10 text-white hover:bg-white/15">{{ __('messages.common.contact') }}</a>
            </div>
            <form action="{{ route('books.index') }}" method="GET" class="search-panel mt-8 max-w-2xl sm:flex sm:items-center sm:gap-3">
                <label for="home-book-search" class="sr-only">{{ __('messages.home.search_placeholder') }}</label>
                <input id="home-book-search" type="search" name="q" placeholder="{{ __('messages.home.search_placeholder') }}" class="form-input sm:flex-1">
                <button class="btn btn-primary mt-3 w-full sm:mt-0 sm:w-auto">{{ __('messages.home.search_button') }}</button>
            </form>
        </div>
        <div class="relative min-h-80 overflow-hidden rounded-3xl border border-white/15 bg-emerald-900/70 shadow-2xl shadow-emerald-950/25">
            @if(! empty($settings['homepage_banner']))
                <img src="{{ Storage::disk('public')->url($settings['homepage_banner']) }}" alt="{{ __('messages.home.banner_alt') }}" class="h-full min-h-80 w-full object-cover">
            @else
                <div class="grid h-full min-h-80 place-items-center p-8 text-center">
                    <div>
                        <p class="text-6xl font-black text-amber-200 sm:text-7xl">{{ __('messages.home.iqra') }}</p>
                        <p class="mt-5 text-xl font-bold text-emerald-50">{{ __('messages.home.iqra_line') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker">{{ __('messages.home.recently_added') }}</p>
            <h2 class="section-heading">{{ __('messages.common.latest_books') }}</h2>
        </div>
        <a href="{{ route('books.index', ['latest' => 1]) }}" class="btn btn-secondary btn-sm">{{ __('messages.common.view_all') }}</a>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($latestBooks as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.home.empty_books') }}</p>
        @endforelse
    </div>
</section>

@if($featuredBooks->isNotEmpty())
<section class="bg-white/70">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-7">
            <p class="section-kicker">{{ __('messages.home.madrasa_picks') }}</p>
            <h2 class="section-heading">{{ __('messages.common.featured_books') }}</h2>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($events->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-7">
        <p class="section-kicker">{{ __('messages.home.event_kicker') }}</p>
        <h2 class="section-heading">{{ __('messages.home.active_collections') }}</h2>
    </div>
    <div class="grid gap-5 lg:grid-cols-2">
        @foreach($events as $event)
            <article class="surface-card overflow-hidden border-l-4 border-l-amber-400">
                @if($event->banner_url)
                    <img src="{{ $event->banner_url }}" alt="{{ $event->localized_title }}" class="h-48 w-full object-cover">
                @endif
                <div class="p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-extrabold uppercase text-amber-700">{{ __('messages.admin.events.collection') }}</p>
                            <h3 class="mt-1 text-2xl font-extrabold text-emerald-950">{{ $event->localized_title }}</h3>
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm">{{ __('messages.home.open_collection') }}</a>
                    </div>
                    @if($event->localized_description)
                        <p class="mt-3 text-sm leading-7 text-stone-700">{{ $event->localized_description }}</p>
                    @endif
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach($event->books as $book)
                            <a href="{{ route('books.show', $book) }}" class="badge badge-green">{{ $book->localized_title }}</a>
                        @endforeach
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="bg-white/70">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div>
            <p class="section-kicker">{{ __('messages.home.browse_kicker') }}</p>
            <h2 class="section-heading">{{ __('messages.home.important_categories') }}</h2>
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="soft-panel p-5 transition hover:border-emerald-300 hover:bg-emerald-50">
                        <span class="font-extrabold text-emerald-950">{{ $category->localized_name }}</span>
                        <span class="mt-1 block text-sm font-semibold text-emerald-700">{{ __('messages.common.books_count', ['count' => $category->books_count]) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">{{ __('messages.home.listening') }}</p>
                    <h2 class="section-heading">{{ __('messages.home.latest_audio') }}</h2>
                </div>
                <a href="{{ route('audios.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.home.all_audio') }}</a>
            </div>
            <div class="space-y-4">
                @forelse($audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @empty
                    <p class="surface-card p-6 text-stone-600">{{ __('messages.home.empty_audio') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="islamic-pattern rounded-3xl p-6 text-white shadow-2xl shadow-emerald-950/10 sm:flex sm:items-center sm:justify-between sm:p-8">
        <div>
            <h2 class="text-2xl font-extrabold">{{ __('messages.home.help_title') }}</h2>
            <p class="mt-2 max-w-2xl leading-8 text-emerald-50/90">{{ __('messages.home.help_text') }}</p>
        </div>
        <div class="mt-6 flex flex-wrap gap-3 sm:mt-0">
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="btn btn-gold">{{ __('messages.common.whatsapp') }}</a>
            @endif
            <a href="{{ route('contact') }}" class="btn btn-secondary border-white/25 bg-white/10 text-white hover:bg-white/15">{{ __('messages.common.contact') }}</a>
        </div>
    </div>
</section>
@endsection
