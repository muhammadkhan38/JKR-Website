@php($site = \App\Models\Setting::pairs())
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $site['madrasa_name'] ?? 'مدرسہ اسلامی کتب')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="urdu-text bg-stone-50 text-stone-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-emerald-100 bg-white/95 sticky top-0 z-40 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if(! empty($site['logo']))
                        <img src="{{ Storage::disk('public')->url($site['logo']) }}" alt="{{ $site['madrasa_name'] ?? 'مدرسہ کا لوگو' }}" class="h-11 w-11 rounded-md object-cover">
                    @else
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-emerald-700 text-lg font-bold text-white">م</span>
                    @endif
                    <span class="max-w-56 text-lg font-bold text-emerald-950">{{ $site['madrasa_name'] ?? 'مدرسہ اسلامی کتب' }}</span>
                </a>
                <button type="button" id="mobile-menu-button" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-900 md:hidden">مینو</button>
                <nav id="main-nav" class="hidden absolute left-0 right-0 top-full border-b border-emerald-100 bg-white px-4 py-4 shadow-sm md:static md:flex md:border-0 md:bg-transparent md:p-0 md:shadow-none">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-5">
                        <a class="nav-link" href="{{ route('home') }}">صفحہ اول</a>
                        <a class="nav-link" href="{{ route('books.index') }}">کتب</a>
                        <a class="nav-link" href="{{ route('audios.index') }}">آڈیو</a>
                        <a class="nav-link" href="{{ route('contact') }}">رابطہ</a>
                        @auth
                            <a class="nav-link" href="{{ route('bookmarks.index') }}">محفوظ کتب</a>
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">انتظامیہ</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link text-right" type="submit">لاگ آؤٹ</button>
                            </form>
                        @else
                            <a class="nav-link" href="{{ route('login') }}">لاگ اِن</a>
                            <a class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800" href="{{ route('register') }}">رجسٹر ہوں</a>
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
                    <h2 class="text-lg font-bold">{{ $site['madrasa_name'] ?? 'مدرسہ اسلامی کتب' }}</h2>
                    <p class="mt-3 text-sm leading-8 text-emerald-100">{{ $site['short_about'] ?? 'یہ مدرسہ کی ایک سادہ لائبریری ہے جہاں مفید اسلامی کتب پڑھی، سنی اور محفوظ کی جا سکتی ہیں۔' }}</p>
                </div>
                <div>
                    <h3 class="font-semibold">رابطہ</h3>
                    <div class="mt-3 space-y-1 text-sm text-emerald-100">
                        @if(! empty($site['contact_number'])) <p>{{ $site['contact_number'] }}</p> @endif
                        @if(! empty($site['email'])) <p>{{ $site['email'] }}</p> @endif
                        @if(! empty($site['address'])) <p>{{ $site['address'] }}</p> @endif
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold">اہم روابط</h3>
                    <div class="mt-3 flex flex-col gap-2 text-sm text-emerald-100">
                        <a href="{{ route('books.index') }}">تمام کتب</a>
                        <a href="{{ route('audios.index') }}">آڈیو بیانات</a>
                        <a href="{{ route('contact') }}">مدرسہ سے رابطہ</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-emerald-800 px-4 py-4 text-center text-sm text-emerald-100">
                {{ $site['footer_text'] ?? 'اللہ تعالیٰ اس علم کو قبول فرمائے اور نفع بخش بنائے۔' }}
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
                    button.text(response.bookmarked ? 'محفوظ فہرست سے نکالیں' : 'کتاب محفوظ کریں');
                    button.toggleClass('bg-amber-600 hover:bg-amber-700', response.bookmarked);
                    button.toggleClass('bg-emerald-700 hover:bg-emerald-800', ! response.bookmarked);
                    $('[data-ajax-message]').text(response.message).removeClass('hidden');
                },
                error: function () {
                    $('[data-ajax-message]').text('کتاب محفوظ کرنے کے لیے براہِ کرم لاگ اِن کریں۔').removeClass('hidden');
                }
            });
        });
    </script>
</body>
</html>
