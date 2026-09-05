<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->topLevel()->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::topLevel()->orderBy('name')->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('image'), 'categories');
        }

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::topLevel()->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($category->image && ! str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = app(\App\Services\ImageOptimizer::class)->store($request->file('image'), 'categories');
        }

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'show_on_home' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['sort_order'] = $request->integer('sort_order');
        unset($data['image']);

        return $data;
    }
}
