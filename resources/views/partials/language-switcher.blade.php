@php($currentLocale = app()->getLocale())
<div class="language-switcher" aria-label="{{ __('messages.language.label') }}">
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
            class="language-switcher-link"
        >
            {{ $labelText }}
        </a>
    @endforeach
</div>
