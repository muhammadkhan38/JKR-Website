@extends('layouts.app')

@section('title', __('messages.auth.register'))

@section('content')
<section class="mx-auto grid min-h-[70dvh] max-w-6xl place-items-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <p class="section-kicker">{{ __('messages.auth.account') }}</p>
            <h1 class="section-heading">{{ __('messages.auth.register') }}</h1>
        </div>
        <form method="POST" action="{{ route('register.store') }}" class="surface-card space-y-5 p-6 sm:p-8">
            @csrf
            <div>
                <label class="form-label" for="name">{{ __('messages.auth.name') }}</label>
                <input class="form-input" id="name" name="name" value="{{ old('name') }}" autocomplete="name" required>
            </div>
            <div>
                <label class="form-label" for="email">{{ __('messages.auth.email') }}</label>
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            </div>
            <div>
                <label class="form-label" for="password">{{ __('messages.auth.password') }}</label>
                <input class="form-input" id="password" type="password" name="password" autocomplete="new-password" required>
            </div>
            <div>
                <label class="form-label" for="password_confirmation">{{ __('messages.auth.confirm_password') }}</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
            </div>
            <button class="btn btn-primary btn-lg w-full">{{ __('messages.auth.create_account') }}</button>
            <p class="text-center text-sm text-stone-600">{{ __('messages.auth.already_account') }} <a href="{{ route('login') }}" class="font-extrabold text-emerald-700">{{ __('messages.auth.login_link') }}</a></p>
        </form>
    </div>
</section>
@endsection
