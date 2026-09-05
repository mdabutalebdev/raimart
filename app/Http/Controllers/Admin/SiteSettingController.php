<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    private const KEYS = [
        'site_description',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'app_store_url',
        'google_play_url',
        'contact_address',
        'contact_phone',
        'contact_email',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_description' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'app_store_url' => ['nullable', 'string', 'max:255'],
            'google_play_url' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated successfully.');
    }
}
