@php($currentLocale = app()->getLocale())
<div class="inline-flex items-center gap-1 rounded-md border border-emerald-200 bg-white/90 p-1 text-sm font-semibold shadow-sm" aria-label="{{ __('messages.language.label') }}">
    @foreach(config('app.supported_locales', ['en' => 'English']) as $locale => $label)
        <a
            href="{{ route('language.switch', $locale) }}"
            hreflang="{{ $locale }}"
            aria-current="{{ $currentLocale === $locale ? 'true' : 'false' }}"
            class="rounded px-3 py-1.5 transition {{ $currentLocale === $locale ? 'bg-emerald-700 text-white' : 'text-emerald-900 hover:bg-emerald-50' }}"
        >
            {{ $locale === 'ur' ? __('messages.language.urdu') : __('messages.language.english') }}
        </a>
    @endforeach
</div>
