@extends('layouts.app')

@section('title', $book->localized_title.' '.__('messages.reader.title_suffix'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="section-kicker">{{ __('messages.reader.kicker') }}</p>
            <h1 class="text-2xl font-bold text-emerald-950">{{ $book->localized_title }}</h1>
        </div>
        <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">{{ __('messages.reader.back_to_details') }}</a>
    </div>
    @if($book->localized_pdf_url)
        @if($book->isUsingFallbackPdf())
            <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900">{{ __('messages.reader.urdu_unavailable') }}</div>
        @endif
        <div class="overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
            <iframe src="{{ $book->localized_pdf_url }}" class="h-[75vh] min-h-[560px] w-full" title="{{ __('messages.reader.iframe_title', ['title' => $book->localized_title]) }}"></iframe>
        </div>
    @else
        <div class="rounded-md border border-amber-200 bg-amber-50 p-6 text-amber-900">{{ __('messages.books.pdf_unavailable') }}</div>
    @endif
</section>
@endsection
