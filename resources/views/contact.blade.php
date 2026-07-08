@extends('layouts.app')

@section('title', __('messages.contact_page.title'))

@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">{{ __('messages.contact_page.title') }}</p>
    <h1 class="section-heading">{{ __('messages.contact_page.heading') }}</h1>
    <div class="mt-8 grid gap-5 md:grid-cols-2">
        <div class="rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-emerald-950">{{ $settings['madrasa_name'] ?? __('messages.meta.site_name') }}</h2>
            <div class="mt-5 space-y-3 text-stone-700">
                @if(! empty($settings['contact_number'])) <p><strong>{{ __('messages.contact_page.phone') }}:</strong> {{ $settings['contact_number'] }}</p> @endif
                @if(! empty($settings['whatsapp_number'])) <p><strong>{{ __('messages.contact_page.whatsapp') }}:</strong> {{ $settings['whatsapp_number'] }}</p> @endif
                @if(! empty($settings['email'])) <p><strong>{{ __('messages.contact_page.email') }}:</strong> {{ $settings['email'] }}</p> @endif
                @if(! empty($settings['address'])) <p><strong>{{ __('messages.contact_page.address') }}:</strong> {{ $settings['address'] }}</p> @endif
            </div>
        </div>
        <div class="rounded-md border border-emerald-100 bg-emerald-50 p-6">
            <h2 class="text-xl font-bold text-emerald-950">{{ __('messages.contact_page.requests_title') }}</h2>
            <p class="mt-3 leading-7 text-stone-700">{{ __('messages.contact_page.requests_text') }}</p>
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">{{ __('messages.contact_page.open_whatsapp') }}</a>
            @endif
        </div>
    </div>
</section>
@endsection
