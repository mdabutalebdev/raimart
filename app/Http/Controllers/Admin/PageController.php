<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create', ['page' => new Page(['is_active' => true, 'show_in_footer' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('status', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $page->update($this->validated($request, $page));

        return redirect()->route('admin.pages.index')->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'content' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'show_in_footer' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['show_in_footer'] = (bool) ($data['show_in_footer'] ?? false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
