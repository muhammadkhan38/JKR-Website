@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">Contact</p>
    <h1 class="section-heading">Contact the Madrasa</h1>
    <div class="mt-8 grid gap-5 md:grid-cols-2">
        <div class="rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-emerald-950">{{ $settings['madrasa_name'] ?? 'Madrasa Islamic Books' }}</h2>
            <div class="mt-5 space-y-3 text-stone-700">
                @if(! empty($settings['contact_number'])) <p><strong>Phone:</strong> {{ $settings['contact_number'] }}</p> @endif
                @if(! empty($settings['whatsapp_number'])) <p><strong>WhatsApp:</strong> {{ $settings['whatsapp_number'] }}</p> @endif
                @if(! empty($settings['email'])) <p><strong>Email:</strong> {{ $settings['email'] }}</p> @endif
                @if(! empty($settings['address'])) <p><strong>Address:</strong> {{ $settings['address'] }}</p> @endif
            </div>
        </div>
        <div class="rounded-md border border-emerald-100 bg-emerald-50 p-6">
            <h2 class="text-xl font-bold text-emerald-950">Book Requests</h2>
            <p class="mt-3 leading-7 text-stone-700">For book upload requests, correction notices, or audio lecture suggestions, please contact the Madrasa office using the details provided here.</p>
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">Open WhatsApp</a>
            @endif
        </div>
    </div>
</section>
@endsection
