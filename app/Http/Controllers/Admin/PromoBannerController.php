<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ofType('mid')->orderBy('sort_order')->get();

        return view('admin.promo-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.promo-banners.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['type'] = 'mid';

        if ($request->hasFile('image')) {
            $data['image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('image'), 'banners');
        }

        Banner::create($data);

        return redirect()->route('admin.promo-banners.index')->with('status', 'Promo banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.promo-banners.edit', compact('banner'));
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

        return redirect()->route('admin.promo-banners.index')->with('status', 'Promo banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && ! str_starts_with($banner->image, 'http')) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.promo-banners.index')->with('status', 'Promo banner deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'image' => [$request->isMethod('post') ? 'required' : 'nullable', 'image', 'max:4096'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $request->integer('sort_order');
        unset($data['image']);

        return $data;
    }
}
