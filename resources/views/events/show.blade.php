@extends('layouts.app')

@section('title', $event->localized_title)

@section('content')
<section class="bg-emerald-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->localized_title }}" class="mb-8 h-64 w-full rounded-md object-cover">
        @endif
        <p class="text-sm font-semibold tracking-wide text-amber-300">{{ __('messages.admin.events.collection') }}</p>
        <h1 class="mt-3 text-4xl font-bold">{{ $event->localized_title }}</h1>
        @if($event->localized_description)
            <p class="mt-4 max-w-3xl text-lg leading-8 text-emerald-100">{{ $event->localized_description }}</p>
        @endif
    </div>
</section>
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">{{ __('messages.books.event_empty') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $books->links() }}</div>

    @if($audios->isNotEmpty())
        <div class="mt-12">
            <p class="section-kicker">{{ __('messages.home.listening') }}</p>
            <h2 class="section-heading">{{ __('messages.books.related_audio') }}</h2>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                @foreach($audios as $audio)
                    @include('partials.audio-card', ['audio' => $audio])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
