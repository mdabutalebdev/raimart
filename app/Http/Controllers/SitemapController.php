<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        // Static pages
        foreach ([
            ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => route('shop'), 'priority' => '0.9', 'freq' => 'daily'],
            ['loc' => route('offers'), 'priority' => '0.8', 'freq' => 'daily'],
            ['loc' => route('categories.index'), 'priority' => '0.7', 'freq' => 'weekly'],
            ['loc' => route('brands.index'), 'priority' => '0.6', 'freq' => 'weekly'],
            ['loc' => route('about'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['loc' => route('track'), 'priority' => '0.4', 'freq' => 'monthly'],
        ] as $static) {
            $urls->push($static + ['lastmod' => now()->toAtomString()]);
        }

        Product::active()->select('slug', 'updated_at')->get()->each(function ($product) use ($urls) {
            $urls->push([
                'loc' => route('products.show', $product->slug),
                'lastmod' => $product->updated_at?->toAtomString(),
                'freq' => 'weekly',
                'priority' => '0.8',
            ]);
        });

        Category::active()->select('slug', 'updated_at')->get()->each(function ($category) use ($urls) {
            $urls->push([
                'loc' => route('shop', ['category' => $category->slug]),
                'lastmod' => $category->updated_at?->toAtomString(),
                'freq' => 'weekly',
                'priority' => '0.7',
            ]);
        });

        Brand::where('is_active', true)->select('slug', 'updated_at')->get()->each(function ($brand) use ($urls) {
            $urls->push([
                'loc' => route('shop', ['brand' => $brand->slug]),
                'lastmod' => $brand->updated_at?->toAtomString(),
                'freq' => 'weekly',
                'priority' => '0.6',
            ]);
        });

        Page::active()->select('slug', 'updated_at')->get()->each(function ($page) use ($urls) {
            $urls->push([
                'loc' => route('pages.show', $page->slug),
                'lastmod' => $page->updated_at?->toAtomString(),
                'freq' => 'monthly',
                'priority' => '0.4',
            ]);
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    /** robots.txt pointing crawlers at the sitemap and away from private areas. */
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /checkout',
            'Disallow: /cart',
            'Disallow: /user',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }
}
