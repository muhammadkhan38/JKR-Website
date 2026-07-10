@php($currentLocale = app()->getLocale())
<select class="language-switcher" aria-label="{{ __('messages.language.label') }}" onchange="window.location.assign(this.value)">
    @foreach(config('app.supported_locales', ['en' => 'English']) as $locale => $label)
        @php($labelText = match ($locale) {
            'en' => __('messages.language.english'),
            'ur' => __('messages.language.urdu'),
            'ar' => \Illuminate\Support\Facades\Lang::has('messages.language.arabic') ? __('messages.language.arabic') : 'Arabic',
            default => $label,
        })
        <option value="{{ route('language.switch', $locale) }}" @selected($currentLocale === $locale)>{{ '🌐 '.$labelText }}</option>
    @endforeach
</select>
