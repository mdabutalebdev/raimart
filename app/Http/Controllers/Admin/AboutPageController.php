<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    /** Plain text/HTML settings this screen owns. */
    private const KEYS = [
        'about_hero_title',
        'about_hero_subtitle',
        'about_title',
        'about_content',
        'about_mission_title',
        'about_mission_content',
        'about_vision_title',
        'about_vision_content',
        'about_counters_title',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();
        $counters = json_decode($settings['about_counters'] ?? '[]', true) ?: [];

        return view('admin.about.edit', compact('settings', 'counters'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'about_hero_title' => ['nullable', 'string', 'max:255'],
            'about_hero_subtitle' => ['nullable', 'string', 'max:500'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_content' => ['nullable', 'string'],
            'about_mission_title' => ['nullable', 'string', 'max:255'],
            'about_mission_content' => ['nullable', 'string'],
            'about_vision_title' => ['nullable', 'string', 'max:255'],
            'about_vision_content' => ['nullable', 'string'],
            'about_counters_title' => ['nullable', 'string', 'max:255'],
            'about_image' => ['nullable', 'image', 'max:4096'],
            'counters' => ['array'],
            'counters.*.label' => ['nullable', 'string', 'max:100'],
            'counters.*.value' => ['nullable', 'string', 'max:20'],
            'counters.*.suffix' => ['nullable', 'string', 'max:10'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        if ($request->hasFile('about_image')) {
            $old = SiteSetting::get('about_image');
            if ($old && ! str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            SiteSetting::set('about_image', app(\App\Services\ImageOptimizer::class)->store($request->file('about_image'), 'about'));
        }

        // Keep only rows that actually have a label and a value.
        $counters = collect($data['counters'] ?? [])
            ->filter(fn ($c) => filled($c['label'] ?? null) && filled($c['value'] ?? null))
            ->map(fn ($c) => [
                'label' => $c['label'],
                'value' => $c['value'],
                'suffix' => $c['suffix'] ?? null,
            ])
            ->values();

        SiteSetting::set('about_counters', $counters->toJson());

        return redirect()->route('admin.about.edit')->with('status', 'About page updated successfully.');
    }
}
