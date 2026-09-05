<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = app(\App\Services\ImageOptimizer::class)->store($request->file('avatar'), 'testimonials');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar && ! str_starts_with($testimonial->avatar, 'http')) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $data['avatar'] = app(\App\Services\ImageOptimizer::class)->store($request->file('avatar'), 'testimonials');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial deleted successfully.');
    }

    public function accept(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => true]);
        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial accepted and made visible.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => false]);
        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial rejected and hidden.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $request->integer('sort_order');
        unset($data['avatar']);

        return $data;
    }
}
