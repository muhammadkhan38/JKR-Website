@extends('layouts.app')

@section('title', $book->localized_title.' '.__('messages.reader.title_suffix'))

@section('content')
@php
    $hasPdf = filled($pdf);
    $startsWithPdfJs = $hasPdf && ! $pdf['is_google_drive'];
    $primaryPreviewUrl = $hasPdf ? ($pdf['is_google_drive'] ? $pdf['preview_url'] : $pdfJsUrl) : null;
@endphp

<section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-5 flex flex-col gap-4 rounded-md border border-emerald-100 bg-white p-4 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <p class="section-kicker">{{ __('messages.reader.kicker') }}</p>
            <h1 class="mt-1 break-words text-2xl font-bold leading-tight text-emerald-950 sm:text-3xl">{{ $book->localized_title }}</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('books.index') }}" class="rounded-md border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">{{ __('messages.reader.back_to_books') }}</a>
            @if($hasPdf && $pdf['is_google_drive'])
                <button type="button" data-use-pdfjs class="rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('messages.reader.fallback_reader') }}</button>
            @endif
            @if($hasPdf && $book->download_allowed)
                <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.download_pdf') }}</a>
            @endif
        </div>
    </div>

    @if($book->isUsingFallbackPdf())
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900">{{ __('messages.reader.urdu_unavailable') }}</div>
    @endif

    @if($hasPdf)
        <div
            data-pdf-reader
            data-fallback-url="{{ $pdfJsUrl }}"
            data-starts-pdfjs="{{ $startsWithPdfJs ? 1 : 0 }}"
            class="overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm"
        >
            <div data-pdfjs-toolbar class="{{ $startsWithPdfJs ? '' : 'hidden' }} border-b border-slate-200 bg-slate-50 px-3 py-3">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" data-pdf-command="previous-page" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('messages.reader.previous_page') }}</button>
                        <button type="button" data-pdf-command="next-page" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('messages.reader.next_page') }}</button>
                        <form data-pdf-page-form class="flex items-center gap-2">
                            <input data-pdf-page-input type="number" min="1" value="1" aria-label="{{ __('messages.reader.page') }}" class="h-10 w-20 rounded-md border border-slate-200 bg-white px-2 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <span class="text-sm font-medium text-slate-600">/ <span data-pdf-total-pages>...</span></span>
                        </form>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" data-pdf-command="zoom-out" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('messages.reader.zoom_out') }}</button>
                        <button type="button" data-pdf-command="fit-width" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('messages.reader.fit_width') }}</button>
                        <button type="button" data-pdf-command="zoom-in" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('messages.reader.zoom_in') }}</button>
                    </div>
                    <form data-pdf-search-form class="flex min-w-0 flex-1 gap-2 lg:max-w-sm">
                        <input data-pdf-search-input type="search" placeholder="{{ __('messages.reader.search_placeholder') }}" class="h-10 min-w-0 flex-1 rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <button class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.search') }}</button>
                    </form>
                </div>
            </div>

            <div class="relative h-[72vh] min-h-[460px] bg-slate-100 sm:h-[78vh] sm:min-h-[560px]">
                <div data-pdf-loading class="absolute inset-0 z-10 grid place-items-center bg-white/95 px-6 text-center">
                    <div>
                        <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-emerald-100 border-t-emerald-700"></div>
                        <p class="mt-4 text-base font-bold text-emerald-950">{{ __('messages.reader.loading') }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ __('messages.reader.loading_text') }}</p>
                    </div>
                </div>

                <div data-pdf-error class="hidden absolute inset-0 z-20 grid place-items-center bg-white px-6 text-center">
                    <div class="max-w-md">
                        <p class="text-xl font-bold text-emerald-950">{{ __('messages.reader.error_title') }}</p>
                        <p data-pdf-error-message class="mt-2 text-sm leading-6 text-slate-600">{{ __('messages.reader.error_text') }}</p>
                        @if($book->download_allowed)
                            <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.download_pdf') }}</a>
                        @endif
                    </div>
                </div>

                <iframe
                    data-pdf-frame
                    src="{{ $primaryPreviewUrl }}"
                    class="h-full w-full bg-white"
                    title="{{ __('messages.reader.iframe_title', ['title' => $book->localized_title]) }}"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    @else
        <div class="rounded-md border border-amber-200 bg-amber-50 p-6 text-amber-900">
            <p class="font-bold">{{ __('messages.reader.error_title') }}</p>
            <p class="mt-2 text-sm leading-6">{{ __('messages.books.pdf_unavailable') }}</p>
        </div>
    @endif
</section>

@if($hasPdf)
    <script>
        (() => {
            const reader = document.querySelector('[data-pdf-reader]');

            if (! reader) {
                return;
            }

            const frame = reader.querySelector('[data-pdf-frame]');
            const loading = reader.querySelector('[data-pdf-loading]');
            const error = reader.querySelector('[data-pdf-error]');
            const errorMessage = reader.querySelector('[data-pdf-error-message]');
            const toolbar = reader.querySelector('[data-pdfjs-toolbar]');
            const pageInput = reader.querySelector('[data-pdf-page-input]');
            const totalPages = reader.querySelector('[data-pdf-total-pages]');
            const fallbackUrl = reader.dataset.fallbackUrl;
            let usingPdfJs = reader.dataset.startsPdfjs === '1';
            let primaryLoaded = false;

            const hideLoading = () => loading?.classList.add('hidden');
            const showLoading = () => loading?.classList.remove('hidden');
            const hideError = () => error?.classList.add('hidden');
            const showError = (message) => {
                hideLoading();
                errorMessage.textContent = message || @json(__('messages.reader.error_text'));
                error?.classList.remove('hidden');
            };
            const updatePage = (page, pages) => {
                if (pageInput && page) {
                    pageInput.value = page;
                }

                if (totalPages && pages) {
                    totalPages.textContent = pages;
                }
            };
            const switchToPdfJs = () => {
                if (! fallbackUrl || usingPdfJs) {
                    return;
                }

                usingPdfJs = true;
                showLoading();
                hideError();
                toolbar?.classList.remove('hidden');
                frame.src = fallbackUrl;
            };
            const sendPdfCommand = (command, value = null) => {
                const wasUsingPdfJs = usingPdfJs;

                if (! usingPdfJs) {
                    switchToPdfJs();
                }

                window.setTimeout(() => {
                    frame.contentWindow?.postMessage({ target: 'madrassa-pdfjs', command, value }, window.location.origin);
                }, wasUsingPdfJs ? 0 : 900);
            };

            frame.addEventListener('load', () => {
                primaryLoaded = true;

                if (! usingPdfJs) {
                    hideLoading();
                }
            });

            frame.addEventListener('error', () => {
                if (fallbackUrl && ! usingPdfJs) {
                    switchToPdfJs();

                    return;
                }

                showError(@json(__('messages.reader.error_text')));
            });

            if (! usingPdfJs && fallbackUrl) {
                window.setTimeout(() => {
                    if (! primaryLoaded) {
                        switchToPdfJs();
                    }
                }, 12000);
            }

            document.querySelectorAll('[data-use-pdfjs]').forEach((button) => {
                button.addEventListener('click', switchToPdfJs);
            });

            reader.querySelectorAll('[data-pdf-command]').forEach((button) => {
                button.addEventListener('click', () => sendPdfCommand(button.dataset.pdfCommand));
            });

            reader.querySelector('[data-pdf-page-form]')?.addEventListener('submit', (event) => {
                event.preventDefault();
                sendPdfCommand('go-to-page', pageInput?.value || 1);
            });

            reader.querySelector('[data-pdf-search-form]')?.addEventListener('submit', (event) => {
                event.preventDefault();
                sendPdfCommand('search', reader.querySelector('[data-pdf-search-input]')?.value || '');
            });

            window.addEventListener('message', (event) => {
                if (event.origin !== window.location.origin || event.data?.source !== 'madrassa-pdfjs') {
                    return;
                }

                if (event.data.type === 'loaded') {
                    hideLoading();
                    hideError();
                    toolbar?.classList.remove('hidden');
                    updatePage(event.data.page, event.data.pages);
                }

                if (event.data.type === 'page-change') {
                    updatePage(event.data.page, event.data.pages);
                }

                if (event.data.type === 'error') {
                    showError(event.data.message);
                }
            });
        })();
    </script>
@endif
@endsection
