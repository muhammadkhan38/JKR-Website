@extends('layouts.app')

@section('title', 'پروفائل')

@section('content')
<section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">اکاؤنٹ</p>
    <h1 class="section-heading">پروفائل</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="mt-8 space-y-5 rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label class="form-label" for="name">نام</label>
            <input class="form-input" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        </div>
        <div>
            <label class="form-label" for="email">ای میل</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label" for="password">نیا پاس ورڈ</label>
                <input class="form-input" id="password" type="password" name="password">
            </div>
            <div>
                <label class="form-label" for="password_confirmation">پاس ورڈ کی تصدیق</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation">
            </div>
        </div>
        <button class="rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">پروفائل محفوظ کریں</button>
    </form>
</section>
@endsection
