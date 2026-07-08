<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public const KEYS = [
        'logo',
        'homepage_banner',
        'contact_number',
        'whatsapp_number',
        'email',
        'facebook_link',
        'youtube_link',
    ];

    public const LOCALIZED_KEYS = [
        'madrasa_name',
        'address',
        'short_about',
        'footer_text',
    ];

    public function index(): View
    {
        return view('admin.settings.index', ['settings' => Setting::pairs()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'madrasa_name_en' => ['nullable', 'string', 'max:255'],
            'madrasa_name_ur' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:80'],
            'whatsapp_number' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_en' => ['nullable', 'string'],
            'address_ur' => ['nullable', 'string'],
            'short_about_en' => ['nullable', 'string'],
            'short_about_ur' => ['nullable', 'string'],
            'footer_text_en' => ['nullable', 'string'],
            'footer_text_ur' => ['nullable', 'string'],
            'facebook_link' => ['nullable', 'url', 'max:255'],
            'youtube_link' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'homepage_banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        foreach (self::KEYS as $key) {
            if (in_array($key, ['logo', 'homepage_banner'], true)) {
                if ($request->hasFile($key)) {
                    $old = Setting::valueFor($key);
                    if ($old) {
                        Storage::disk('public')->delete($old);
                    }
                    Setting::updateOrCreate(['key' => $key], ['value' => $request->file($key)->store('settings', 'public')]);
                }

                continue;
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $data[$key] ?? null]);
        }

        foreach (self::LOCALIZED_KEYS as $key) {
            foreach (array_keys(config('app.supported_locales', ['en' => 'English'])) as $locale) {
                Setting::updateOrCreate(['key' => $key.'_'.$locale], ['value' => $data[$key.'_'.$locale] ?? null]);
            }
        }

        return back()->with('success', __('messages.flash.settings_saved'));
    }
}
