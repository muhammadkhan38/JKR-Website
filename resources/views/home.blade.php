@extends('layouts.app')

@section('title', ($settings['madrasa_name'] ?? 'Madrasa Islamic Books').' - Home')

@section('content')
<section class="bg-emerald-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
        <div class="self-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-300">Islamic Books Library</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">{{ $settings['madrasa_name'] ?? 'Madrasa Islamic Books' }}</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-emerald-100">{{ $settings['short_about'] ?? 'Read, download, bookmark, and listen to selected books and lessons from the Madrasa.' }}</p>
            <form action="{{ route('books.index') }}" method="GET" class="mt-8 flex flex-col gap-3 rounded-md bg-white p-3 sm:flex-row">
                <input type="search" name="q" placeholder="Search by title, author, category, language..." class="min-h-12 flex-1 rounded-md border border-emerald-100 px-4 text-stone-900 outline-none focus:border-amber-400">
                <button class="rounded-md bg-amber-500 px-6 py-3 font-semibold text-emerald-950 hover:bg-amber-400">Search Books</button>
            </form>
        </div>
        <div class="min-h-72 overflow-hidden rounded-md border border-emerald-700 bg-emerald-900">
            @if(! empty($settings['homepage_banner']))
                <img src="{{ Storage::disk('public')->url($settings['homepage_banner']) }}" alt="Madrasa banner" class="h-full w-full object-cover">
            @else
                <div class="grid h-full place-items-center p-8 text-center">
                    <div>
                        <p class="text-6xl font-bold text-amber-300">اقرأ</p>
                        <p class="mt-4 text-xl font-semibold">Read in the name of your Lord</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <p class="section-kicker">Recently Added</p>
            <h2 class="section-heading">Latest Books</h2>
        </div>
        <a href="{{ route('books.index', ['latest' => 1]) }}" class="text-sm font-semibold text-emerald-700">View all</a>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($latestBooks as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">No books have been added yet.</p>
        @endforelse
    </div>
</section>

@if($featuredBooks->isNotEmpty())
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="section-kicker">Selected By Madrasa</p>
            <h2 class="section-heading">Featured Books</h2>
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
        <p class="section-kicker">Islamic Events</p>
        <h2 class="section-heading">Active Collections</h2>
    </div>
    <div class="grid gap-5 lg:grid-cols-2">
        @foreach($events as $event)
            <article class="overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
                @if($event->banner_url)
                    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="h-44 w-full object-cover">
                @endif
                <div class="p-5">
                    <h3 class="text-xl font-bold text-emerald-950">{{ $event->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-700">{{ $event->description }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($event->books as $book)
                            <a href="{{ route('books.show', $book) }}" class="rounded bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-800">{{ $book->title }}</a>
                        @endforeach
                    </div>
                    <a href="{{ route('events.show', $event) }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Open Collection</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div>
            <p class="section-kicker">Browse</p>
            <h2 class="section-heading">Popular Categories</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="rounded-md border border-emerald-100 bg-emerald-50 p-4 hover:border-emerald-300">
                        <span class="font-semibold text-emerald-950">{{ $category->name }}</span>
                        <span class="mt-1 block text-sm text-emerald-700">{{ $category->books_count }} books</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <div class="mb-5 flex items-end justify-between">
                <div>
                    <p class="section-kicker">Listen</p>
                    <h2 class="section-heading">Latest Audio Lectures</h2>
                </div>
                <a href="{{ route('audios.index') }}" class="text-sm font-semibold text-emerald-700">All audio</a>
            </div>
            <div class="space-y-4">
                @forelse($audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @empty
                    <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">No audio lectures have been uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-md bg-emerald-800 p-6 text-white sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Need help finding a book?</h2>
            <p class="mt-2 text-emerald-100">Contact the Madrasa office for guidance or requests.</p>
        </div>
        <div class="mt-5 flex flex-wrap gap-3 sm:mt-0">
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="rounded-md bg-amber-400 px-4 py-2 font-semibold text-emerald-950">WhatsApp</a>
            @endif
            <a href="{{ route('contact') }}" class="rounded-md border border-white/30 px-4 py-2 font-semibold text-white">Contact</a>
        </div>
    </div>
</section>
@endsection
