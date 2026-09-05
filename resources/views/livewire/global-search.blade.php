<div class="hidden flex-1 items-center md:flex relative" x-data="{ open: true }" @click.outside="open = false">
    <form action="{{ route('shop') }}" method="GET" class="w-full">
        <div class="relative w-full">
            <input
                type="text"
                name="search"
                wire:model.live.debounce.300ms="query"
                @focus="open = true"
                autocomplete="off"
                placeholder="Search for products..."
                class="w-full rounded-full border border-brand-navy/15 bg-brand-bg py-2.5 pl-5 pr-12 text-sm text-brand-navy placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none"
            >
            <button type="submit" class="absolute right-1.5 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-brand-orange text-white transition hover:bg-brand-navy">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </button>
        </div>
    </form>

    <!-- Search Results Dropdown -->
    @if(strlen($query) >= 2)
    <div x-show="open" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 z-50 overflow-hidden" x-cloak>
        @if($results->count() > 0)
            <div class="max-h-96 overflow-y-auto py-2">
                @foreach($results as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="flex items-center gap-4 px-4 py-3 hover:bg-brand-bg transition border-b border-gray-50 last:border-0">
                        <img src="{{ image_url($product->main_image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-md bg-gray-100">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-brand-navy truncate">{{ $product->name }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-brand-orange font-bold text-sm">৳{{ number_format($product->price) }}</span>
                                @if($product->old_price > $product->price)
                                    <span class="text-gray-400 text-xs line-through">৳{{ number_format($product->old_price) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($totalCount > 5)
                <div class="border-t border-gray-100 bg-gray-50/50 p-3">
                    <a href="{{ route('shop', ['search' => $query]) }}" class="block w-full text-center text-sm text-brand-orange font-medium hover:text-brand-navy transition">
                        View all {{ $totalCount }} results
                    </a>
                </div>
            @endif
        @else
            <div class="p-6 text-center text-gray-500 text-sm">
                No products found for "<span class="font-semibold text-brand-navy">{{ $query }}</span>"
            </div>
        @endif
    </div>
    @endif
</div>
