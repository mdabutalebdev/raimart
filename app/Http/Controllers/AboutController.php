<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class AboutController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAll();

        // Counters are stored as a JSON array of {label, value, suffix} in one setting.
        $counters = collect(json_decode($settings['about_counters'] ?? '[]', true) ?: [])
            ->filter(fn ($c) => filled($c['label'] ?? null) && filled($c['value'] ?? null))
            ->values();

        return view('about', compact('settings', 'counters'));
    }
}
