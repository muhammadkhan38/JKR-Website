@extends('layouts.app')

@section('title', __('messages.contact_page.title'))

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8 max-w-3xl">
        <p class="section-kicker">{{ __('messages.contact_page.title') }}</p>
        <h1 class="section-heading">{{ __('messages.contact_page.heading') }}</h1>
        <p class="section-copy">{{ __('messages.contact_page.requests_text') }}</p>
    </div>
    <div class="grid gap-6 md:grid-cols-[1fr_0.9fr]">
        <div class="surface-card p-6 sm:p-8">
            <h2 class="text-2xl font-extrabold text-emerald-950">{{ $settings['madrasa_name'] ?? __('messages.meta.site_name') }}</h2>
            <div class="mt-6 grid gap-3 text-stone-700">
                @if(! empty($settings['contact_number']))
                    <p class="rounded-xl bg-emerald-50 px-4 py-3"><strong>{{ __('messages.contact_page.phone') }}:</strong> {{ $settings['contact_number'] }}</p>
                @endif
                @if(! empty($settings['whatsapp_number']))
                    <p class="rounded-xl bg-emerald-50 px-4 py-3"><strong>{{ __('messages.contact_page.whatsapp') }}:</strong> {{ $settings['whatsapp_number'] }}</p>
                @endif
                @if(! empty($settings['email']))
                    <p class="rounded-xl bg-emerald-50 px-4 py-3"><strong>{{ __('messages.contact_page.email') }}:</strong> {{ $settings['email'] }}</p>
                @endif
                @if(! empty($settings['address']))
                    <p class="rounded-xl bg-emerald-50 px-4 py-3"><strong>{{ __('messages.contact_page.address') }}:</strong> {{ $settings['address'] }}</p>
                @endif
            </div>
        </div>
        <div class="islamic-pattern rounded-3xl p-6 text-white shadow-2xl shadow-emerald-950/10 sm:p-8">
            <h2 class="text-2xl font-extrabold">{{ __('messages.contact_page.requests_title') }}</h2>
            <p class="mt-4 leading-8 text-emerald-50/90">{{ __('messages.contact_page.requests_text') }}</p>
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="btn btn-gold mt-6" target="_blank" rel="noopener">{{ __('messages.contact_page.open_whatsapp') }}</a>
            @endif
        </div>
    </div>
</section>
@endsection
