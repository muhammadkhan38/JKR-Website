@extends('layouts.app')

@section('title', __('messages.profile.title'))

@section('content')
<section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">{{ __('messages.auth.account') }}</p>
    <h1 class="section-heading">{{ __('messages.profile.title') }}</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="surface-card mt-8 space-y-5 p-6 sm:p-8">
        @csrf
        <div>
            <label class="form-label" for="name">{{ __('messages.auth.name') }}</label>
            <input class="form-input" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required>
        </div>
        <div>
            <label class="form-label" for="email">{{ __('messages.auth.email') }}</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label" for="password">{{ __('messages.profile.new_password') }}</label>
                <input class="form-input" id="password" type="password" name="password" autocomplete="new-password">
            </div>
            <div>
                <label class="form-label" for="password_confirmation">{{ __('messages.auth.confirm_password') }}</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
            </div>
        </div>
        <button class="btn btn-primary btn-lg">{{ __('messages.profile.save') }}</button>
    </form>
</section>
@endsection
