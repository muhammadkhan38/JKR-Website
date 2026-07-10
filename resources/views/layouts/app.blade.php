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
    <meta name="description" content="@yield('meta_description', __('messages.meta.site_description'))">
    @foreach(config('app.supported_locales', ['en' => 'English']) as $locale => $label)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('language.switch', $locale) }}">
    @endforeach
    <title>@yield('title', $site['madrasa_name'] ?? __('messages.meta.site_name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="{{ $isRtl ? 'font-urdu' : 'font-english' }} antialiased">
    <a href="#main-content" class="skip-link">{{ __('messages.nav.home') }}</a>
    <div class="site-shell">
        <header class="site-header">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3" aria-label="{{ $site['madrasa_name'] ?? __('messages.meta.site_name') }}">
                    @if(! empty($site['logo']))
                        <img src="{{ Storage::disk('public')->url($site['logo']) }}" alt="{{ $site['madrasa_name'] ?? __('messages.meta.site_name') }}" class="brand-mark object-cover p-0">
                    @else
                        <span class="brand-mark">{{ __('messages.meta.logo_letter') }}</span>
                    @endif
                    <span class="min-w-0">
                        <span class="block max-w-56 truncate text-base font-extrabold leading-tight text-white sm:text-lg">{{ $site['madrasa_name'] ?? __('messages.meta.site_name') }}</span>
                        <span class="mt-0.5 block text-xs text-white/80">{{ __('messages.jamia.brand_tagline') }}</span>
                    </span>
                </a>
                <div class="flex items-center gap-2 md:hidden">
                    @include('partials.language-switcher')
                    <button type="button" id="mobile-menu-button" class="mobile-menu-toggle" aria-controls="main-nav" aria-expanded="false">
                        <span class="grid gap-1" aria-hidden="true">
                            <span class="block h-0.5 w-4 rounded bg-white"></span>
                            <span class="block h-0.5 w-4 rounded bg-white"></span>
                            <span class="block h-0.5 w-4 rounded bg-white"></span>
                        </span>
                        <span>{{ __('messages.nav.menu') }}</span>
                    </button>
                </div>
                <nav id="main-nav" class="hidden absolute left-0 right-0 top-full border-b border-white/15 px-4 py-4 shadow-xl shadow-black/15 md:static md:flex md:border-0 md:bg-transparent md:p-0 md:shadow-none" aria-label="{{ __('messages.nav.menu') }}">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-1">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('messages.nav.home') }}</a>
                        <a class="nav-link" href="{{ route('books.index') }}">{{ __('messages.nav.books') }}</a>
                        <a class="nav-link" href="{{ route('lectures.index') }}">{{ __('messages.nav.lectures') }}</a>
                        <a class="nav-link" href="{{ route('live.index') }}"><span class="live-nav-dot" aria-hidden="true"></span>{{ __('messages.nav.live') }}</a>
                        <a class="nav-link" href="{{ route('institute.index') }}">{{ __('messages.nav.institute') }}</a>
                        <a class="nav-link" href="{{ route('announcements.index') }}">{{ __('messages.nav.announcements') }}</a>
                        @auth
                            <a class="nav-link" href="{{ route('bookmarks.index') }}">{{ __('messages.nav.bookmarks') }}</a>
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('messages.nav.admin') }}</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link text-start" type="submit">{{ __('messages.nav.logout') }}</button>
                            </form>
                        @else
                            <a class="nav-link" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">{{ __('messages.nav.register') }}</a>
                        @endauth
                        <div class="hidden md:block">
                            @include('partials.language-switcher')
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <main id="main-content" class="flex-1">
            @include('partials.flash')
            @yield('content')
        </main>

        <footer class="islamic-pattern border-t border-emerald-900/30 text-emerald-50">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-[1.2fr_0.8fr_0.8fr] lg:px-8">
                <div>
                    <div class="flex items-center gap-3">
                        @if(! empty($site['logo']))
                            <img src="{{ Storage::disk('public')->url($site['logo']) }}" alt="{{ $site['madrasa_name'] ?? __('messages.meta.site_name') }}" class="h-11 w-11 rounded-xl object-cover ring-1 ring-white/20">
                        @else
                            <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10 text-lg font-black text-amber-200 ring-1 ring-white/15">{{ __('messages.meta.logo_letter') }}</span>
                        @endif
                        <h2 class="text-lg font-extrabold">{{ $site['madrasa_name'] ?? __('messages.meta.site_name') }}</h2>
                    </div>
                    <p class="mt-4 max-w-xl text-sm leading-8 text-emerald-50/85">{{ __('messages.jamia.footer_summary') }}</p>
                    @if(! empty($site['facebook_link']) || ! empty($site['youtube_link']))
                        <div class="mt-5 flex flex-wrap gap-3 text-sm font-bold">
                            @if(! empty($site['facebook_link']))
                                <a class="footer-link" href="{{ $site['facebook_link'] }}" target="_blank" rel="noopener">{{ __('messages.footer.facebook') }}</a>
                            @endif
                            @if(! empty($site['youtube_link']))
                                <a class="footer-link" href="{{ $site['youtube_link'] }}" target="_blank" rel="noopener">{{ __('messages.footer.youtube') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="font-extrabold text-amber-200">{{ __('messages.footer.quick_links') }}</h3>
                    <div class="mt-4 flex flex-col gap-2 text-sm font-semibold">
                        <a class="footer-link" href="{{ route('books.index') }}">{{ __('messages.nav.books') }}</a>
                        <a class="footer-link" href="{{ route('lectures.index') }}">{{ __('messages.jamia.lecture_schedule') }}</a>
                        <a class="footer-link" href="{{ route('live.index') }}">{{ __('messages.jamia.live_title') }}</a>
                        <a class="footer-link" href="{{ route('institute.index') }}">{{ __('messages.jamia.institute_title') }}</a>
                        @auth
                            <a class="footer-link" href="{{ route('bookmarks.index') }}">{{ __('messages.nav.bookmarks') }}</a>
                        @endauth
                    </div>
                </div>
                <div>
                    <h3 class="font-extrabold text-amber-200">{{ __('messages.footer.contact') }}</h3>
                    <div class="mt-4 space-y-2 text-sm leading-7 text-emerald-50/85">
                        @if(! empty($site['contact_number'])) <p>{{ $site['contact_number'] }}</p> @endif
                        @if(! empty($site['email'])) <p>{{ $site['email'] }}</p> @endif
                        @if(! empty($site['address'])) <p>{{ $site['address'] }}</p> @endif
                    </div>
                    <a class="footer-link mt-5 inline-flex text-sm font-semibold" href="{{ route('contact') }}">{{ __('messages.footer.contact_madrasa') }}</a>
                </div>
            </div>
            <div class="border-t border-white/10 px-4 py-5 text-center text-sm text-emerald-50/80">
                {{ $site['footer_text'] ?? __('messages.footer.default_text') }}
            </div>
        </footer>

        @if(! empty($site['whatsapp_number']))
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp_number']) }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="{{ __('messages.common.whatsapp') }}">
                <span aria-hidden="true">{{ __('messages.common.whatsapp_short') }}</span>
                <span>{{ __('messages.common.whatsapp') }}</span>
            </a>
        @endif
    </div>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' } });

        const mobileMenuButton = $('#mobile-menu-button');
        const mainNav = $('#main-nav');

        mobileMenuButton.on('click', function () {
            const isOpen = mainNav.toggleClass('hidden').is(':visible');
            mobileMenuButton.attr('aria-expanded', isOpen ? 'true' : 'false');
        });

        mainNav.on('click', 'a, button', function () {
            if (window.matchMedia('(max-width: 767px)').matches) {
                mainNav.addClass('hidden');
                mobileMenuButton.attr('aria-expanded', 'false');
            }
        });

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape') {
                mainNav.addClass('hidden');
                mobileMenuButton.attr('aria-expanded', 'false');
            }
        });

        $(document).on('click', '[data-bookmark-button]', function (event) {
            event.preventDefault();
            const button = $(this);
            const bookmarked = button.data('bookmarked') === true || button.data('bookmarked') === 1;
            $.ajax({
                url: button.data(bookmarked ? 'deleteUrl' : 'storeUrl'),
                method: 'POST',
                data: bookmarked ? { _method: 'DELETE' } : {},
                success: function (response) {
                    button.data('bookmarked', response.bookmarked);
                    button.text(response.bookmarked ? @json(__('messages.books.remove_saved')) : @json(__('messages.books.save')));
                    button.toggleClass('btn-gold', response.bookmarked);
                    button.toggleClass('btn-primary', ! response.bookmarked);
                    $('[data-ajax-message]').text(response.message).removeClass('hidden');
                },
                error: function () {
                    $('[data-ajax-message]').text(@json(__('messages.flash.login_required_save'))).removeClass('hidden');
                }
            });
        });
    </script>
</body>
</html>
