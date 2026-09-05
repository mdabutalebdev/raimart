<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function edit()
    {
        $links = SocialLink::all()->keyBy('platform');

        return view('admin.social-links.edit', compact('links'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'links' => ['array'],
            'links.*.url' => ['nullable', 'string', 'max:255'],
            'links.*.is_active' => ['nullable', 'boolean'],
        ]);

        $sort = 0;

        foreach (array_keys(SocialLink::PLATFORMS) as $platform) {
            $row = $data['links'][$platform] ?? [];

            SocialLink::updateOrCreate(
                ['platform' => $platform],
                [
                    'url' => $row['url'] ?? null,
                    // Only really "on" when it is ticked AND has a URL to point at.
                    'is_active' => (bool) ($row['is_active'] ?? false) && filled($row['url'] ?? null),
                    'sort_order' => $sort++,
                ]
            );
        }

        return redirect()->route('admin.social-links.edit')->with('status', 'Social links updated successfully.');
    }
}
