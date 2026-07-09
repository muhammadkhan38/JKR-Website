@php($currentLocale = app()->getLocale())
<div class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-white/90 p-1 text-sm font-bold shadow-sm" aria-label="{{ __('messages.language.label') }}">
    @foreach(config('app.supported_locales', ['en' => 'English']) as $locale => $label)
        @php($labelText = match ($locale) {
            'en' => __('messages.language.english'),
            'ur' => __('messages.language.urdu'),
            'ar' => \Illuminate\Support\Facades\Lang::has('messages.language.arabic') ? __('messages.language.arabic') : 'Arabic',
            default => $label,
        })
        <a
            href="{{ route('language.switch', $locale) }}"
            hreflang="{{ $locale }}"
            aria-current="{{ $currentLocale === $locale ? 'true' : 'false' }}"
            class="rounded-full px-3 py-1.5 transition {{ $currentLocale === $locale ? 'bg-emerald-800 text-white' : 'text-emerald-900 hover:bg-emerald-50' }}"
        >
            {{ $labelText }}
        </a>
    @endforeach
</div>
