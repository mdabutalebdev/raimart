<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class TopSellingController extends Controller
{
    public function edit()
    {
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderByDesc('is_top_selling')
            ->orderBy('top_selling_order')
            ->orderBy('name')
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '—',
                'price' => (float) $product->price,
                'image' => image_url($product->main_image, urlencode($product->name)),
                'selected' => (bool) $product->is_top_selling,
                'order' => (int) $product->top_selling_order,
            ])
            ->values();

        return view('admin.top-selling.edit', compact('products'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
            'order' => ['nullable', 'array'],
        ]);

        $ids = collect($data['products'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();

        // Reset everything, then flag + order the chosen products.
        Product::where('is_top_selling', true)->whereNotIn('id', $ids)->update([
            'is_top_selling' => false,
            'top_selling_order' => 0,
        ]);

        $order = $data['order'] ?? [];

        foreach ($ids as $id) {
            Product::whereKey($id)->update([
                'is_top_selling' => true,
                'top_selling_order' => (int) ($order[$id] ?? 0),
            ]);
        }

        return redirect()->route('admin.top-selling.edit')->with('status', 'Top Selling products updated.');
    }
}
