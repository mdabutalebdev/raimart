<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class DeliveryChargeController extends Controller
{
    private const KEYS = [
        'shipping_inside_label',
        'shipping_inside_fee',
        'shipping_outside_label',
        'shipping_outside_fee',
        'free_shipping_threshold',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();

        return view('admin.delivery.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shipping_inside_label' => ['nullable', 'string', 'max:60'],
            'shipping_inside_fee' => ['required', 'numeric', 'min:0'],
            'shipping_outside_label' => ['nullable', 'string', 'max:60'],
            'shipping_outside_fee' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.delivery.edit')->with('status', 'Delivery charges updated.');
    }
}
