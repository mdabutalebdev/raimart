<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    /**
     * Contact address/phone/email are the same settings the footer uses, so editing
     * them here keeps the whole site in sync from one screen.
     */
    private const KEYS = [
        'contact_hero_title',
        'contact_hero_subtitle',
        'contact_address',
        'contact_phone',
        'contact_phone_2',
        'contact_email',
        'contact_hours',
        'contact_map_embed',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();

        return view('admin.contact.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'contact_hero_title' => ['nullable', 'string', 'max:255'],
            'contact_hero_subtitle' => ['nullable', 'string', 'max:500'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_phone_2' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_hours' => ['nullable', 'string', 'max:255'],
            'contact_map_embed' => ['nullable', 'string'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.contact-page.edit')->with('status', 'Contact page updated successfully.');
    }
}
