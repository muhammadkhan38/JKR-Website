@php
    $site = \App\Models\Setting::localizedPairs();
    $isRtl = in_array(app()->getLocale(), ['ur', 'ar'], true);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.admin.panel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="{{ $isRtl ? 'font-urdu' : 'font-english' }} bg-slate-100 text-slate-900 antialiased">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="flex items-center justify-between gap-4 px-5 py-5 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    @if(! empty($site['logo']))
                        <img src="{{ Storage::disk('public')->url($site['logo']) }}" alt="{{ $site['madrasa_name'] ?? __('messages.admin.panel') }}" class="h-11 w-11 rounded-xl object-cover">
                    @else
                        <span class="brand-mark h-11 w-11">{{ __('messages.meta.logo_letter') }}</span>
                    @endif
                    <span class="truncate text-lg font-extrabold text-emerald-950">{{ $site['madrasa_name'] ?? __('messages.admin.panel') }}</span>
                </a>
                <div class="flex items-center gap-2 lg:hidden">
                    @include('partials.language-switcher')
                    <button id="admin-menu-button" class="mobile-menu-toggle" type="button" aria-controls="admin-nav" aria-expanded="false">{{ __('messages.nav.menu') }}</button>
                </div>
            </div>
            <nav id="admin-nav" class="hidden px-3 pb-5 lg:block">
                @php($links = [
                    [__('messages.admin.dashboard'), route('admin.dashboard')],
                    [__('messages.admin.nav.books'), route('admin.books.index')],
                    [__('messages.admin.nav.categories'), route('admin.categories.index')],
                    [__('messages.admin.nav.authors'), route('admin.authors.index')],
                    [__('messages.admin.nav.events'), route('admin.islamic-events.index')],
                    [__('messages.admin.nav.audios'), route('admin.audios.index')],
                    [__('messages.admin.nav.users'), route('admin.users.index')],
                    [__('messages.admin.nav.settings'), route('admin.settings.index')],
                    [__('messages.admin.public_site'), route('home')],
                ])
                <div class="space-y-1">
                    @foreach($links as [$label, $url])
                        <a href="{{ $url }}" class="admin-nav-link">{{ $label }}</a>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-5">
                    @csrf
                    <button class="btn btn-muted w-full justify-start" type="submit">{{ __('messages.nav.logout') }}</button>
                </form>
                <div class="mt-5 hidden lg:block">
                    @include('partials.language-switcher')
                </div>
            </nav>
        </aside>

        <main class="flex-1">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @include('partials.flash')
                <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-extrabold uppercase text-emerald-700">{{ __('messages.admin.panel') }}</p>
                        <h1 class="mt-1 text-2xl font-extrabold text-slate-950">@yield('heading', __('messages.admin.dashboard'))</h1>
                    </div>
                    @yield('actions')
                </div>
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        $('#admin-menu-button').on('click', function () {
            const nav = $('#admin-nav');
            const isOpen = nav.toggleClass('hidden').is(':visible');
            $(this).attr('aria-expanded', isOpen ? 'true' : 'false');
        });

        $('[data-confirm-delete]').on('submit', function (event) {
            if (! confirm(@json(__('messages.admin.confirm_delete')))) {
                event.preventDefault();
            }
        });

        $('[data-file-label]').on('change', function () {
            const target = $($(this).data('fileLabel'));
            target.text(this.files[0] ? this.files[0].name : '');
        });

        $('[data-cover-preview]').on('change', function () {
            const target = $($(this).data('coverPreview'));
            if (this.files[0]) {
                target.attr('src', URL.createObjectURL(this.files[0])).removeClass('hidden');
            }
        });
    </script>
</body>
</html>
