@php($site = \App\Models\Setting::pairs())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="border-b border-slate-200 bg-white lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between px-5 py-5 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-emerald-950">{{ $site['madrasa_name'] ?? 'Madrasa Admin' }}</a>
                <button id="admin-menu-button" class="rounded-md border px-3 py-2 text-sm lg:hidden" type="button">Menu</button>
            </div>
            <nav id="admin-nav" class="hidden px-3 pb-5 lg:block">
                @php($links = [
                    ['Dashboard', route('admin.dashboard')],
                    ['Books', route('admin.books.index')],
                    ['Categories', route('admin.categories.index')],
                    ['Authors', route('admin.authors.index')],
                    ['Islamic Events', route('admin.islamic-events.index')],
                    ['Audios', route('admin.audios.index')],
                    ['Users', route('admin.users.index')],
                    ['Settings', route('admin.settings.index')],
                    ['Public Website', route('home')],
                ])
                <div class="space-y-1">
                    @foreach($links as [$label, $url])
                        <a href="{{ $url }}" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-800">{{ $label }}</a>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-5">
                    @csrf
                    <button class="w-full rounded-md border border-slate-200 px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-50" type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <main class="flex-1">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @include('partials.flash')
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Admin Panel</p>
                        <h1 class="text-2xl font-bold text-slate-950">@yield('heading', 'Dashboard')</h1>
                    </div>
                    @yield('actions')
                </div>
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        $('#admin-menu-button').on('click', function () {
            $('#admin-nav').toggleClass('hidden');
        });

        $('[data-confirm-delete]').on('submit', function (event) {
            if (! confirm('Delete this item?')) {
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
