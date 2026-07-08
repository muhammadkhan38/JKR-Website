@extends('layouts.admin')

@section('title', __('messages.admin.settings.title'))
@section('heading', __('messages.admin.settings.title'))

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <h2 class="mb-4 text-lg font-bold text-slate-950">{{ __('messages.admin.settings.english_content') }}</h2>
            <label class="form-label" for="madrasa_name_en">{{ __('messages.admin.fields.madrasa_name_en') }}</label>
            <input class="form-input" id="madrasa_name_en" name="madrasa_name_en" value="{{ old('madrasa_name_en', $settings['madrasa_name_en'] ?? $settings['madrasa_name'] ?? '') }}">
            <label class="form-label mt-4" for="address_en">{{ __('messages.admin.fields.address_en') }}</label>
            <textarea class="form-input" id="address_en" name="address_en" rows="3">{{ old('address_en', $settings['address_en'] ?? $settings['address'] ?? '') }}</textarea>
            <label class="form-label mt-4" for="short_about_en">{{ __('messages.admin.fields.short_about_en') }}</label>
            <textarea class="form-input" id="short_about_en" name="short_about_en" rows="4">{{ old('short_about_en', $settings['short_about_en'] ?? $settings['short_about'] ?? '') }}</textarea>
            <label class="form-label mt-4" for="footer_text_en">{{ __('messages.admin.fields.footer_text_en') }}</label>
            <textarea class="form-input" id="footer_text_en" name="footer_text_en" rows="3">{{ old('footer_text_en', $settings['footer_text_en'] ?? $settings['footer_text'] ?? '') }}</textarea>
        </div>
        <div>
            <h2 class="mb-4 text-lg font-bold text-slate-950">{{ __('messages.admin.settings.urdu_content') }}</h2>
            <label class="form-label" for="madrasa_name_ur">{{ __('messages.admin.fields.madrasa_name_ur') }}</label>
            <input class="form-input" id="madrasa_name_ur" name="madrasa_name_ur" value="{{ old('madrasa_name_ur', $settings['madrasa_name_ur'] ?? '') }}" dir="rtl">
            <label class="form-label mt-4" for="address_ur">{{ __('messages.admin.fields.address_ur') }}</label>
            <textarea class="form-input" id="address_ur" name="address_ur" rows="3" dir="rtl">{{ old('address_ur', $settings['address_ur'] ?? '') }}</textarea>
            <label class="form-label mt-4" for="short_about_ur">{{ __('messages.admin.fields.short_about_ur') }}</label>
            <textarea class="form-input" id="short_about_ur" name="short_about_ur" rows="4" dir="rtl">{{ old('short_about_ur', $settings['short_about_ur'] ?? '') }}</textarea>
            <label class="form-label mt-4" for="footer_text_ur">{{ __('messages.admin.fields.footer_text_ur') }}</label>
            <textarea class="form-input" id="footer_text_ur" name="footer_text_ur" rows="3" dir="rtl">{{ old('footer_text_ur', $settings['footer_text_ur'] ?? '') }}</textarea>
        </div>
    </div>

    <div class="mt-8 grid gap-5 lg:grid-cols-2">
        <div>
            <label class="form-label" for="email">{{ __('messages.admin.fields.email') }}</label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="contact_number">{{ __('messages.admin.fields.contact_number') }}</label>
            <input class="form-input" id="contact_number" name="contact_number" value="{{ old('contact_number', $settings['contact_number'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="whatsapp_number">{{ __('messages.admin.fields.whatsapp') }}</label>
            <input class="form-input" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="facebook_link">{{ __('messages.admin.fields.facebook_link') }}</label>
            <input class="form-input" id="facebook_link" name="facebook_link" value="{{ old('facebook_link', $settings['facebook_link'] ?? '') }}">
        </div>
        <div>
            <label class="form-label" for="youtube_link">{{ __('messages.admin.fields.youtube_link') }}</label>
            <input class="form-input" id="youtube_link" name="youtube_link" value="{{ old('youtube_link', $settings['youtube_link'] ?? '') }}">
        </div>
    </div>
    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <div>
            <label class="form-label" for="logo">{{ __('messages.admin.fields.logo') }}</label>
            <input class="form-input" id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" data-file-label="#logo-file-name">
            <p id="logo-file-name" class="mt-2 text-sm text-slate-500">{{ ! empty($settings['logo']) ? __('messages.common.current_file', ['file' => $settings['logo']]) : '' }}</p>
        </div>
        <div>
            <label class="form-label" for="homepage_banner">{{ __('messages.admin.fields.homepage_banner') }}</label>
            <input class="form-input" id="homepage_banner" type="file" name="homepage_banner" accept=".jpg,.jpeg,.png,.webp" data-file-label="#banner-file-name">
            <p id="banner-file-name" class="mt-2 text-sm text-slate-500">{{ ! empty($settings['homepage_banner']) ? __('messages.common.current_file', ['file' => $settings['homepage_banner']]) : '' }}</p>
        </div>
    </div>
    <button class="mt-6 rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">{{ __('messages.common.save') }}</button>
</form>
@endsection
