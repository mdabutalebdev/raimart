<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Testimonial;
use App\Observers\CacheBuster;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Services\Cart;
use App\Services\Wishlist;
use App\Support\MailConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mail credentials saved in the admin panel win over .env (wrapped so a
        // missing settings table during migrate/install can't break booting).
        try {
            MailConfig::apply();
        } catch (\Throwable $e) {
            // no settings table yet — ignore
        }

        // Admin edits to these instantly invalidate the cached home/nav fragments.
        foreach ([Banner::class, Category::class, Testimonial::class] as $model) {
            $model::observe(CacheBuster::class);
        }

        // The header/drawer category tree is identical for every visitor on every
        // page, so it is cached and busted by the CacheBuster observer on save.
        View::composer(['partials.header', 'partials.category-drawer'], function ($view) {
            $navCategories = Cache::remember('nav:categories', now()->addHours(6), fn () => Category::active()->topLevel()
                ->withCount('products')
                ->with(['children' => fn ($query) => $query->active()->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Category $category) => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image,
                    'products_count' => $category->products_count,
                    'subcategories' => $category->children->map(fn (Category $child) => [
                        'name' => $child->name,
                        'slug' => $child->slug,
                    ])->all(),
                ])->all());

            $view->with('navCategories', $navCategories);
            $view->with('cartCount', app(Cart::class)->count());
            $view->with('wishlistCount', app(Wishlist::class)->count());
        });

        View::composer('partials.footer', function ($view) {
            $view->with('siteSettings', SiteSetting::getAll());
            $view->with('socialLinks', SocialLink::visible()->get());
            $view->with('footerPages', Page::visible()->get());
        });

        View::composer('partials.quick-contact', function ($view) {
            $view->with('siteSettings', SiteSetting::getAll());
        });

        View::composer('partials.mobile-nav', function ($view) {
            $view->with('cartCount', app(Cart::class)->count());
        });

        View::composer('components.product-card', function ($view) {
            $view->with('wishlistIds', app(Wishlist::class)->raw());
        });
    }
}
