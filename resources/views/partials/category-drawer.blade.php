<div id="category-drawer" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-brand-navy/50" data-drawer-close></div>

    <div class="absolute inset-y-0 left-0 flex w-80 max-w-[85vw] flex-col bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-brand-navy/10 px-5 py-4">
            <h2 class="flex items-center gap-2 font-serif text-lg font-bold text-brand-navy">
                <i class="fa-solid fa-bars text-brand-orange"></i>
                Categories
            </h2>
            <button type="button" data-drawer-close aria-label="Close" class="flex h-8 w-8 items-center justify-center rounded-full text-brand-navy/50 hover:bg-brand-bg hover:text-brand-navy">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            @foreach ($navCategories as $navCategory)
                <a href="{{ route('shop', ['category' => $navCategory['slug']]) }}" wire:navigate class="flex items-center gap-3 border-b border-brand-navy/5 px-5 py-3 hover:bg-brand-bg">
                    <img src="{{ image_url($navCategory['image'], urlencode($navCategory['name'])) }}" alt="{{ $navCategory['name'] }}" class="h-12 w-12 shrink-0 rounded-lg object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-brand-navy">{{ $navCategory['name'] }}</p>
                        <p class="text-xs text-brand-navy/50">{{ $navCategory['products_count'] }} Products</p>
                    </div>
                    <i class="fa-solid fa-chevron-right shrink-0 text-brand-navy/30"></i>
                </a>
            @endforeach
        </div>

        <div class="border-t border-brand-navy/10 p-4">
            <a href="{{ route('categories.index') }}" wire:navigate class="block rounded-md bg-brand-orange py-2.5 text-center text-sm font-semibold text-white hover:bg-brand-navy">
                View All Categories
            </a>
        </div>
    </div>
</div>
