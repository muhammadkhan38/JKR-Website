@extends('layouts.admin')

@section('title', 'ویب سائٹ ترتیبات')
@section('heading', 'ویب سائٹ ترتیبات')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <label class="form-label" for="madrasa_name">مدرسہ کا نام</label>
            <input class="form-input" id="madrasa_name" name="madrasa_name" value="{{ old('madrasa_name', $settings['madrasa_name'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="email">ای میل</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="contact_number">رابطہ نمبر</label>
            <input class="form-input" id="contact_number" name="contact_number" value="{{ old('contact_number', $settings['contact_number'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="whatsapp_number">واٹس ایپ نمبر</label>
            <input class="form-input" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="facebook_link">فیس بک لنک</label>
            <input class="form-input" id="facebook_link" name="facebook_link" value="{{ old('facebook_link', $settings['facebook_link'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="youtube_link">یوٹیوب لنک</label>
            <input class="form-input" id="youtube_link" name="youtube_link" value="{{ old('youtube_link', $settings['youtube_link'] ?? '') }}">
        </div>
    </div>
    <div class="mt-5">
        <label class="form-label" for="address">پتا</label>
        <textarea class="form-input" id="address" name="address" rows="3">{{ old('address', $settings['address'] ?? '') }}</textarea>
    </div>
    <div class="mt-5">
        <label class="form-label" for="short_about">مختصر تعارف</label>
        <textarea class="form-input" id="short_about" name="short_about" rows="4">{{ old('short_about', $settings['short_about'] ?? '') }}</textarea>
    </div>
    <div class="mt-5">
        <label class="form-label" for="footer_text">فوٹر متن</label>
        <textarea class="form-input" id="footer_text" name="footer_text" rows="3">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
    </div>
    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <div>
            <label class="form-label" for="logo">لوگو</label>
            <input class="form-input" id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" data-file-label="#logo-file-name">
            <p id="logo-file-name" class="mt-2 text-sm text-slate-500">{{ ! empty($settings['logo']) ? 'موجودہ فائل: '.$settings['logo'] : '' }}</p>
        </div>
        <div>
            <label class="form-label" for="homepage_banner">صفحہ اول کا بینر</label>
            <input class="form-input" id="homepage_banner" type="file" name="homepage_banner" accept=".jpg,.jpeg,.png,.webp" data-file-label="#banner-file-name">
            <p id="banner-file-name" class="mt-2 text-sm text-slate-500">{{ ! empty($settings['homepage_banner']) ? 'موجودہ فائل: '.$settings['homepage_banner'] : '' }}</p>
        </div>
    </div>
    <button class="mt-6 rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">ترتیبات محفوظ کریں</button>
</form>
@endsection
