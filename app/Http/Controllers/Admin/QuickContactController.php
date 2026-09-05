<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

/**
 * The floating "quick contact" bubble shown on the storefront
 * (Messenger / WhatsApp / call), all values admin-managed.
 */
class QuickContactController extends Controller
{
    private const KEYS = [
        'quick_contact_enabled',
        'quick_messenger_url',
        'quick_whatsapp_number',
        'quick_phone_number',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();

        return view('admin.quick-contact.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'quick_contact_enabled' => ['nullable', 'boolean'],
            'quick_messenger_url' => ['nullable', 'string', 'max:255'],
            'quick_whatsapp_number' => ['nullable', 'string', 'max:30'],
            'quick_phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, (string) ($data[$key] ?? ''));
        }

        return redirect()->route('admin.quick-contact.edit')->with('status', 'Quick contact widget updated.');
    }
}
