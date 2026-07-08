@php($site = \App\Models\Setting::pairs())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $site['madrasa_name'] ?? 'Madrasa Islamic Books')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-emerald-100 bg-white/95 sticky top-0 z-40 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if(! empty($site['logo']))
                        <img src="{{ Storage::disk('public')->url($site['logo']) }}" alt="{{ $site['madrasa_name'] ?? 'Madrasa logo' }}" class="h-11 w-11 rounded-md object-cover">
                    @else
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-emerald-700 text-lg font-bold text-white">M</span>
                    @endif
                    <span class="max-w-56 text-lg font-bold text-emerald-950">{{ $site['madrasa_name'] ?? 'Madrasa Islamic Books' }}</span>
                </a>
                <button type="button" id="mobile-menu-button" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-900 md:hidden">Menu</button>
                <nav id="main-nav" class="hidden absolute left-0 right-0 top-full border-b border-emerald-100 bg-white px-4 py-4 shadow-sm md:static md:flex md:border-0 md:bg-transparent md:p-0 md:shadow-none">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-5">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                        <a class="nav-link" href="{{ route('books.index') }}">Books</a>
                        <a class="nav-link" href="{{ route('audios.index') }}">Audios</a>
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                        @auth
                            <a class="nav-link" href="{{ route('bookmarks.index') }}">Bookmarks</a>
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link text-left" type="submit">Logout</button>
                            </form>
                        @else
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                            <a class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800" href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @include('partials.flash')
            @yield('content')
        </main>

        <footer class="border-t border-emerald-100 bg-emerald-950 text-emerald-50">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-lg font-bold">{{ $site['madrasa_name'] ?? 'Madrasa Islamic Books' }}</h2>
                    <p class="mt-3 text-sm leading-6 text-emerald-100">{{ $site['short_about'] ?? 'A simple Madrasa library for reading, listening, and sharing beneficial Islamic books.' }}</p>
                </div>
                <div>
                    <h3 class="font-semibold">Contact</h3>
                    <div class="mt-3 space-y-1 text-sm text-emerald-100">
                        @if(! empty($site['contact_number'])) <p>{{ $site['contact_number'] }}</p> @endif
                        @if(! empty($site['email'])) <p>{{ $site['email'] }}</p> @endif
                        @if(! empty($site['address'])) <p>{{ $site['address'] }}</p> @endif
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold">Quick Links</h3>
                    <div class="mt-3 flex flex-col gap-2 text-sm text-emerald-100">
                        <a href="{{ route('books.index') }}">All Books</a>
                        <a href="{{ route('audios.index') }}">Audio Lectures</a>
                        <a href="{{ route('contact') }}">Contact Madrasa</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-emerald-800 px-4 py-4 text-center text-sm text-emerald-100">
                {{ $site['footer_text'] ?? 'May Allah accept and spread beneficial knowledge.' }}
            </div>
        </footer>
    </div>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' } });

        $('#mobile-menu-button').on('click', function () {
            $('#main-nav').toggleClass('hidden');
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
                    button.text(response.bookmarked ? 'Remove Bookmark' : 'Bookmark Book');
                    button.toggleClass('bg-amber-600 hover:bg-amber-700', response.bookmarked);
                    button.toggleClass('bg-emerald-700 hover:bg-emerald-800', ! response.bookmarked);
                    $('[data-ajax-message]').text(response.message).removeClass('hidden');
                },
                error: function () {
                    $('[data-ajax-message]').text('Please login to bookmark books.').removeClass('hidden');
                }
            });
        });
    </script>
</body>
</html>
