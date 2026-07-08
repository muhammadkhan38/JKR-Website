<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supportedLocales = array_keys(config('app.supported_locales', ['en' => 'English']));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        $request->session()->put('locale', $locale);
        Cookie::queue(Cookie::forever('locale', $locale));

        return redirect()->back();
    }
}
