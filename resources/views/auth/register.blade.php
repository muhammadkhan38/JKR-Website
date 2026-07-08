@extends('layouts.app')

@section('title', __('messages.auth.register'))

@section('content')
<section class="mx-auto max-w-md px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">{{ __('messages.auth.account') }}</p>
    <h1 class="section-heading">{{ __('messages.auth.register') }}</h1>
    <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5 rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label class="form-label" for="name">{{ __('messages.auth.name') }}</label>
            <input class="form-input" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label class="form-label" for="email">{{ __('messages.auth.email') }}</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label class="form-label" for="password">{{ __('messages.auth.password') }}</label>
            <input class="form-input" id="password" type="password" name="password" required>
        </div>
        <div>
            <label class="form-label" for="password_confirmation">{{ __('messages.auth.confirm_password') }}</label>
            <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button class="w-full rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">{{ __('messages.auth.create_account') }}</button>
        <p class="text-center text-sm text-stone-600">{{ __('messages.auth.already_account') }} <a href="{{ route('login') }}" class="font-semibold text-emerald-700">{{ __('messages.auth.login_link') }}</a></p>
    </form>
</section>
@endsection
