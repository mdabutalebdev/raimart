<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::whereIn('type', ['hero', 'promo'])->orderBy('type')->orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('image'), 'banners');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('status', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($banner->image && ! str_starts_with($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('image'), 'banners');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('status', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('status', 'Banner deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:hero,promo'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'],
            'link' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $request->integer('sort_order');
        unset($data['image']);

        return $data;
    }
}
