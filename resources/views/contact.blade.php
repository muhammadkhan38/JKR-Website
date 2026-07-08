@extends('layouts.app')

@section('title', 'رابطہ')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <p class="section-kicker">رابطہ</p>
    <h1 class="section-heading">مدرسہ سے رابطہ کریں</h1>
    <div class="mt-8 grid gap-5 md:grid-cols-2">
        <div class="rounded-md border border-emerald-100 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-emerald-950">{{ $settings['madrasa_name'] ?? 'مدرسہ اسلامی کتب' }}</h2>
            <div class="mt-5 space-y-3 text-stone-700">
                @if(! empty($settings['contact_number'])) <p><strong>فون:</strong> {{ $settings['contact_number'] }}</p> @endif
                @if(! empty($settings['whatsapp_number'])) <p><strong>واٹس ایپ:</strong> {{ $settings['whatsapp_number'] }}</p> @endif
                @if(! empty($settings['email'])) <p><strong>ای میل:</strong> {{ $settings['email'] }}</p> @endif
                @if(! empty($settings['address'])) <p><strong>پتا:</strong> {{ $settings['address'] }}</p> @endif
            </div>
        </div>
        <div class="rounded-md border border-emerald-100 bg-emerald-50 p-6">
            <h2 class="text-xl font-bold text-emerald-950">کتاب سے متعلق درخواستیں</h2>
            <p class="mt-3 leading-7 text-stone-700">کتاب اپ لوڈ کرانے، اصلاح کی اطلاع دینے، یا آڈیو بیان کی تجویز کے لیے یہاں دی گئی معلومات کے ذریعے مدرسہ دفتر سے رابطہ کریں۔</p>
            @if(! empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['whatsapp_number']) }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">واٹس ایپ کھولیں</a>
            @endif
        </div>
    </div>
</section>
@endsection
