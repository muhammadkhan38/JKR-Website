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
    <div class="schedule-list">
        @forelse($lectures as $lecture)
            <article class="schedule-card">
                <div class="date-tile" aria-label="{{ $lecture->created_at?->format('d M Y') }}">
                    <span class="date-tile__day">{{ $lecture->created_at?->format('d') }}</span>
                    <span class="date-tile__month">{{ $lecture->created_at?->format('M') }}</span>
                </div>
                <div class="schedule-card__content">
                    <h2 class="schedule-card__title">{{ $lecture->localized_title }}</h2>
                    <p class="schedule-card__meta">{{ $lecture->localized_speaker ?: __('messages.audios.default_speaker') }} @if($lecture->duration) · {{ $lecture->duration }} @endif</p>
                    @if($lecture->localized_description)
                        <p class="schedule-card__meta">{{ $lecture->localized_description }}</p>
                    @endif
                </div>
                @if($lecture->book?->localized_pdf_url)
                    <a href="{{ route('books.reader', $lecture->book) }}" class="btn btn-primary btn-sm">{{ __('messages.common.read_online') }}</a>
                @endif
            </article>
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.audios.empty') }}</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $lectures->links() }}</div>
</section>
@endsection
