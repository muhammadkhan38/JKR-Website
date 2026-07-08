@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="mx-auto max-w-md px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">Account</p>
    <h1 class="section-heading">Login</h1>
    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5 rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label class="form-label" for="email">Email</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div>
            <label class="form-label" for="password">Password</label>
            <input class="form-input" id="password" type="password" name="password" required>
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-700"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button class="w-full rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">Login</button>
        <p class="text-center text-sm text-stone-600">No account? <a href="{{ route('register') }}" class="font-semibold text-emerald-700">Register</a></p>
    </form>
</section>
@endsection
