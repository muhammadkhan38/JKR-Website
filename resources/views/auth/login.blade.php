@extends('layouts.app')

@section('title', __('messages.auth.login'))

@section('content')
<section class="mx-auto grid min-h-[70dvh] max-w-6xl place-items-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <p class="section-kicker">{{ __('messages.auth.account') }}</p>
            <h1 class="section-heading">{{ __('messages.auth.login') }}</h1>
        </div>
        <form method="POST" action="{{ route('login.store') }}" class="surface-card space-y-5 p-6 sm:p-8">
            @csrf
            <div>
                <label class="form-label" for="email">{{ __('messages.auth.email') }}</label>
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            </div>
            <div>
                <label class="form-label" for="password">{{ __('messages.auth.password') }}</label>
                <input class="form-input" id="password" type="password" name="password" autocomplete="current-password" required>
            </div>
            <label class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-stone-700">
                <input type="checkbox" name="remember" value="1">
                {{ __('messages.auth.remember') }}
            </label>
            <button class="btn btn-primary btn-lg w-full">{{ __('messages.auth.login') }}</button>
            <p class="text-center text-sm text-stone-600">{{ __('messages.auth.no_account') }} <a href="{{ route('register') }}" class="font-extrabold text-emerald-700">{{ __('messages.auth.register_link') }}</a></p>
        </form>
    </div>
</section>
@endsection
