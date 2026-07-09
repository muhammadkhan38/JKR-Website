@extends('layouts.app')

@section('title', $book->localized_title.' '.__('messages.reader.title_suffix'))

@section('content')
@php
    $hasPdf = filled($pdf);
    $nativePreviewUrl = $hasPdf ? route('books.pdf', $book) : null;
    $startsWithPdfJs = false;
    $primaryPreviewUrl = $nativePreviewUrl;
    $drivePreviewUrl = $hasPdf && $pdf['is_google_drive'] ? $pdf['preview_url'] : null;
@endphp

<section class="mx-auto max-w-[1500px] px-3 py-5 sm:px-6 lg:px-8">
    <div class="mb-4 flex flex-col gap-4 rounded-2xl border border-emerald-900/10 bg-white p-4 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <p class="section-kicker">{{ __('messages.reader.kicker') }}</p>
            <h1 class="mt-1 break-words text-2xl font-extrabold leading-tight text-emerald-950 sm:text-3xl">{{ $book->localized_title }}</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('books.show', $book) }}" class="btn btn-secondary btn-sm">{{ __('messages.reader.back_to_details') }}</a>
            <a href="{{ route('books.index') }}" class="btn btn-muted btn-sm">{{ __('messages.reader.back_to_books') }}</a>
            @if($hasPdf)
                <button type="button" data-use-pdfjs class="btn btn-muted btn-sm">{{ __('messages.reader.fallback_reader') }}</button>
            @endif
            @if($drivePreviewUrl)
                <button type="button" data-use-drive-preview class="btn btn-muted btn-sm">{{ __('messages.reader.drive_preview') }}</button>
            @endif
            @if($hasPdf && $book->download_allowed)
                <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">{{ __('messages.common.download_pdf') }}</a>
            @endif
        </div>
    </div>

    @if($book->isUsingFallbackPdf())
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-900">{{ __('messages.reader.urdu_unavailable') }}</div>
    @endif

    @if($hasPdf)
        <div
            data-pdf-reader
            data-fallback-url="{{ $pdfJsUrl }}"
            data-native-url="{{ $nativePreviewUrl }}"
            data-drive-preview-url="{{ $drivePreviewUrl }}"
            data-starts-pdfjs="{{ $startsWithPdfJs ? 1 : 0 }}"
            class="reader-shell"
        >
            <div data-pdfjs-toolbar class="{{ $startsWithPdfJs ? '' : 'hidden' }} reader-toolbar">
                <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" data-pdf-command="previous-page" class="btn btn-muted btn-sm">{{ __('messages.reader.previous_page') }}</button>
                        <button type="button" data-pdf-command="next-page" class="btn btn-muted btn-sm">{{ __('messages.reader.next_page') }}</button>
                        <form data-pdf-page-form class="flex items-center gap-2">
                            <label for="reader-page-input" class="sr-only">{{ __('messages.reader.page') }}</label>
                            <input id="reader-page-input" data-pdf-page-input type="number" min="1" value="1" aria-label="{{ __('messages.reader.page') }}" class="form-input-sm h-11 w-24">
                            <span class="text-sm font-bold text-slate-600">/ <span data-pdf-total-pages>...</span></span>
                        </form>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" data-pdf-command="zoom-out" class="btn btn-muted btn-sm">{{ __('messages.reader.zoom_out') }}</button>
                        <button type="button" data-pdf-command="fit-page" class="btn btn-muted btn-sm">{{ __('messages.reader.fit_width') }}</button>
                        <button type="button" data-pdf-command="zoom-in" class="btn btn-muted btn-sm">{{ __('messages.reader.zoom_in') }}</button>
                    </div>
                    <form data-pdf-search-form class="flex min-w-0 flex-1 gap-2 xl:max-w-md">
                        <label for="reader-search-input" class="sr-only">{{ __('messages.reader.search_placeholder') }}</label>
                        <input id="reader-search-input" data-pdf-search-input type="search" placeholder="{{ __('messages.reader.search_placeholder') }}" class="form-input-sm min-w-0 flex-1">
                        <button class="btn btn-primary btn-sm">{{ __('messages.common.search') }}</button>
                    </form>
                    @if($book->download_allowed)
                        <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="btn btn-gold btn-sm">{{ __('messages.common.download_pdf') }}</a>
                    @endif
                </div>
            </div>

            <div class="relative h-[76dvh] min-h-[460px] bg-slate-100 sm:h-[82dvh] sm:min-h-[640px]">
                <div data-pdf-loading class="absolute inset-0 z-10 grid place-items-center bg-white/95 px-6 text-center">
                    <div>
                        <div class="mx-auto h-11 w-11 animate-spin rounded-full border-4 border-emerald-100 border-t-emerald-800"></div>
                        <p class="mt-4 text-base font-extrabold text-emerald-950">{{ __('messages.reader.loading') }}</p>
                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ __('messages.reader.loading_text') }}</p>
                    </div>
                </div>

                <div data-pdf-error class="hidden absolute inset-0 z-20 grid place-items-center bg-white px-6 text-center">
                    <div class="max-w-md">
                        <p class="text-2xl font-extrabold text-emerald-950">{{ __('messages.reader.error_title') }}</p>
                        <p data-pdf-error-message class="mt-3 text-sm leading-7 text-slate-600">{{ __('messages.reader.error_text') }}</p>
                        @if($book->download_allowed)
                            <a href="{{ route('books.download', $book) }}" target="_blank" rel="noopener" class="btn btn-primary mt-5">{{ __('messages.common.download_pdf') }}</a>
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
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900">
            <p class="text-xl font-extrabold">{{ __('messages.reader.error_title') }}</p>
            <p class="mt-2 text-sm leading-7">{{ __('messages.books.pdf_unavailable') }}</p>
            <a href="{{ route('books.show', $book) }}" class="btn btn-secondary mt-5">{{ __('messages.reader.back_to_details') }}</a>
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
            const nativeUrl = reader.dataset.nativeUrl;
            const drivePreviewUrl = reader.dataset.drivePreviewUrl;
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
            const switchToNativePreview = () => {
                if (! nativeUrl || (! usingPdfJs && frame.src === nativeUrl)) {
                    return;
                }

                usingPdfJs = false;
                primaryLoaded = false;
                showLoading();
                hideError();
                toolbar?.classList.add('hidden');
                frame.src = nativeUrl;
            };
            const switchToDrivePreview = () => {
                if (! drivePreviewUrl) {
                    return;
                }

                usingPdfJs = false;
                primaryLoaded = false;
                showLoading();
                hideError();
                toolbar?.classList.add('hidden');
                frame.src = drivePreviewUrl;
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

            document.querySelectorAll('[data-use-pdfjs]').forEach((button) => {
                button.addEventListener('click', switchToPdfJs);
            });

            document.querySelectorAll('[data-use-drive-preview]').forEach((button) => {
                button.addEventListener('click', switchToDrivePreview);
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
