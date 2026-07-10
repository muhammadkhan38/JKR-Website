@extends('layouts.app')

@section('title', ($settings['madrasa_name'] ?? __('messages.meta.site_name')).' - '.__('messages.home.title_suffix'))

@section('content')
<section id="home" class="library-hero scroll-mt-24">
    <span class="library-hero__orb library-hero__orb--one" aria-hidden="true"></span>
    <span class="library-hero__orb library-hero__orb--two" aria-hidden="true"></span>
    <span class="library-hero__ring library-hero__ring--outer" aria-hidden="true"></span>
    <span class="library-hero__ring library-hero__ring--inner" aria-hidden="true"></span>

    <div class="library-hero__content">
        <p class="library-hero__eyebrow">{{ $settings['madrasa_name'] ?? 'Jamia Khulafa-e-Rashideen' }}</p>
        <span class="sr-only">{{ __('messages.home.kicker') }}</span>
        <h1 class="library-hero__title">{{ __('messages.jamia.home_title') }}</h1>
        <p class="library-hero__copy">{{ __('messages.jamia.home_copy') }}</p>
        <div class="library-hero__actions">
            <a href="{{ route('books.index') }}" class="btn btn-gold btn-lg library-hero__primary">{{ __('messages.jamia.explore_library') }}</a>
            <a href="{{ route('lectures.index') }}" class="btn btn-secondary btn-lg library-hero__secondary">{{ __('messages.jamia.lecture_schedule') }}</a>
        </div>
    </div>
</section>

<section class="library-search" aria-label="{{ __('messages.home.search_placeholder') }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <form action="{{ route('books.index') }}" method="GET" class="library-search__form">
            <label for="home-book-search" class="sr-only">{{ __('messages.home.search_placeholder') }}</label>
            <input id="home-book-search" type="search" name="q" placeholder="{{ __('messages.home.search_placeholder') }}" class="form-input flex-1">
            <button class="btn btn-primary">{{ __('messages.home.search_button') }}</button>
        </form>
    </div>
</section>

<section id="books" class="mx-auto max-w-7xl scroll-mt-24 px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-kicker">{{ __('messages.home.recently_added') }}</p>
            <h2 class="section-heading">{{ __('messages.common.latest_books') }}</h2>
        </div>
        <a href="{{ route('books.index', ['latest' => 1]) }}" class="btn btn-secondary btn-sm">{{ __('messages.common.view_all') }}</a>
    </div>
    <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($latestBooks as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.home.empty_books') }}</p>
        @endforelse
    </div>
</section>

<section class="home-information">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <h2 class="section-heading">{{ __('messages.jamia.upcoming_lectures') }}</h2>
            <div class="mt-8 grid gap-4">
                @foreach($homeLectures as $lecture)
                    <article class="information-card flex items-center gap-4">
                        <div class="date-tile date-tile--soft" aria-label="{{ $lecture['day'] }} {{ $lecture['month'] }}">
                            <span class="date-tile__day">{{ $lecture['day'] }}</span>
                            <span class="date-tile__month">{{ $lecture['month'] }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-serif text-lg font-bold text-[var(--forest-900)]">{{ $lecture['topic'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-stone-500">{{ $lecture['speaker'] }} · {{ $lecture['city'] }} · {{ $lecture['time'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <a href="{{ route('lectures.index') }}" class="mt-6 inline-flex font-bold text-[var(--forest-900)] transition hover:text-[var(--forest-800)]">{{ __('messages.jamia.full_schedule') }}</a>
        </div>
        <div>
            <h2 class="section-heading">{{ __('messages.jamia.latest_announcements') }}</h2>
            <div class="mt-8 grid gap-4">
                @foreach($homeAnnouncements as $announcement)
                    <article class="information-card">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="announcement-card__tag">{{ $announcement['type'] }}</span>
                            <span class="announcement-card__date">{{ $announcement['date'] }}</span>
                        </div>
                        <h3 class="mt-3 font-serif text-lg font-bold text-[var(--forest-900)]">{{ $announcement['title'] }}</h3>
                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-stone-600">{{ $announcement['body'] }}</p>
                    </article>
                @endforeach
            </div>
            <a href="{{ route('announcements.index') }}" class="mt-6 inline-flex font-bold text-[var(--forest-900)] transition hover:text-[var(--forest-800)]">{{ __('messages.jamia.all_announcements') }}</a>
        </div>
    </div>
</section>

@if($popularBooks->isNotEmpty())
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-kicker">{{ __('messages.home.popular_kicker') }}</p>
                <h2 class="section-heading">{{ __('messages.common.popular_books') }}</h2>
            </div>
            <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.common.view_all') }}</a>
        </div>
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($popularBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featuredBooks->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="section-kicker">{{ __('messages.home.madrasa_picks') }}</p>
        <h2 class="section-heading">{{ __('messages.common.featured_books') }}</h2>
    </div>
    <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($featuredBooks as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
</section>
@endif

@if($events->isNotEmpty())
<section class="library-about">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-kicker">{{ __('messages.home.event_kicker') }}</p>
            <h2 class="section-heading">{{ __('messages.home.active_collections') }}</h2>
        </div>
        <div class="grid gap-5 lg:grid-cols-2">
            @foreach($events as $event)
                <article class="surface-card overflow-hidden border-l-4 border-l-[var(--mint-300)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    @if($event->banner_url)
                        <img src="{{ $event->banner_url }}" alt="{{ $event->localized_title }}" class="h-48 w-full object-cover">
                    @endif
                    <div class="p-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-wider text-[var(--forest-800)]">{{ __('messages.admin.events.collection') }}</p>
                                <h3 class="mt-1 font-serif text-2xl font-bold text-[var(--forest-900)]">{{ $event->localized_title }}</h3>
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm">{{ __('messages.home.open_collection') }}</a>
                        </div>
                        @if($event->localized_description)
                            <p class="mt-3 text-sm leading-7 text-stone-700">{{ $event->localized_description }}</p>
                        @endif
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach($event->books as $book)
                                <a href="{{ route('books.show', $book) }}" class="badge badge-gold">{{ $book->localized_title }}</a>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
    <div id="categories" class="scroll-mt-24">
        <p class="section-kicker">{{ __('messages.home.browse_kicker') }}</p>
        <h2 class="section-heading">{{ __('messages.home.important_categories') }}</h2>
        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="soft-panel p-5 transition duration-300 hover:-translate-y-1 hover:border-[var(--mint-300)] hover:bg-white hover:shadow-lg">
                    <span class="font-bold text-[var(--forest-900)]">{{ $category->localized_name }}</span>
                    <span class="mt-1 block text-sm font-semibold text-[var(--forest-800)]">{{ __('messages.common.books_count', ['count' => $category->books_count]) }}</span>
                </a>
            @endforeach
        </div>
    </div>
    <div>
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
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
</section>

<section id="about" class="library-about scroll-mt-24">
    <div class="library-about__panel px-4 py-16 sm:px-6 lg:px-8">
        <p class="section-kicker">{{ __('messages.nav.about') }}</p>
        <h2 class="section-heading">{{ $settings['madrasa_name'] ?? __('messages.meta.site_name') }}</h2>
        <p class="section-copy">{{ $settings['short_about'] ?? __('messages.footer.default_about') }}</p>
        <p class="mt-4 max-w-3xl text-base leading-8 text-stone-600">{{ __('messages.footer.default_about') }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="islamic-pattern rounded-2xl p-7 text-white shadow-2xl shadow-emerald-950/10 sm:flex sm:items-center sm:justify-between sm:p-10">
        <div>
            <h2 class="font-serif text-3xl font-bold">{{ __('messages.home.help_title') }}</h2>
            <p class="mt-3 max-w-2xl leading-8 text-emerald-50/90">{{ __('messages.home.help_text') }}</p>
        </div>
        <div class="mt-6 flex flex-wrap gap-3 sm:mt-0">
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="btn btn-gold" target="_blank" rel="noopener">{{ __('messages.common.whatsapp') }}</a>
            @endif
            <a href="{{ route('contact') }}" class="btn btn-secondary border-white/40 text-white hover:bg-white/10 hover:text-white">{{ __('messages.common.contact') }}</a>
        </div>
    </div>
</section>
@endsection
