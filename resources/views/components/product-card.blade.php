@props(['product'])

@php
    $isWishlisted = in_array($product->id, $wishlistIds ?? []);
@endphp

<div class="group flex h-full flex-col overflow-hidden rounded-[4px] border border-[#cccccc] bg-white" data-ga-item="{{ json_encode(\App\Support\Ga4::item($product)) }}">
    <div class="relative aspect-square overflow-hidden bg-brand-bg">
        <a href="{{ route('products.show', $product) }}" wire:navigate class="block h-full w-full">
            {{-- width/height reserve the box so the grid doesn't jump while images load --}}
            <img src="{{ image_url($product->main_image, urlencode($product->name)) }}" alt="{{ $product->name }}"
                width="430" height="430" loading="lazy" decoding="async"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        </a>

        @if ($product->discount_percent)
            <span class="absolute left-3 top-3 rounded-full bg-brand-orange px-2.5 py-1 text-xs font-semibold text-white">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        <button
            type="button"
            data-wishlist-toggle
            data-toggle-url="{{ route('wishlist.toggle', $product) }}"
            aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
            aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
            class="wishlist-btn absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm transition hover:bg-brand-orange hover:text-white {{ $isWishlisted ? 'text-brand-orange' : 'text-brand-navy' }}"
        >
            <i class="fa-solid fa-heart text-sm" style="{{ $isWishlisted ? '' : 'display: none;' }}"></i>
            <i class="fa-regular fa-heart text-sm" style="{{ $isWishlisted ? 'display: none;' : '' }}"></i>
        </button>
    </div>

    <div class="flex flex-1 flex-col gap-0.5 p-2.5">
        <p class="text-[9px] sm:text-[10px] font-medium uppercase tracking-wide text-brand-orange">{{ $product->category->name }}</p>
        {{-- min-h keeps the name box two lines tall so every card ends up the same height --}}
        <a href="{{ route('products.show', $product) }}" wire:navigate class="line-clamp-2 min-h-[2.6em] text-xs sm:text-[13px] font-normal sm:font-medium leading-snug text-brand-navy/90 hover:text-brand-orange">{{ $product->name }}</a>

        <div class="mt-0.5 flex items-baseline gap-1.5">
            <span class="text-[13px] sm:text-sm font-bold text-brand-navy">৳{{ number_format($product->price) }}</span>
            @if ($product->old_price)
                <span class="text-[10px] sm:text-[11px] text-brand-navy/40 line-through">৳{{ number_format($product->old_price) }}</span>
            @endif
        </div>

        @if($product->attributes)
            <a href="{{ route('products.show', $product) }}" wire:navigate class="mt-auto flex w-full items-center justify-center gap-1.5 rounded-md border border-brand-orange py-1.5 text-[13px] font-medium text-brand-orange transition hover:bg-brand-orange hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-current">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L21.65 7H5.12" />
                </svg>
                Add to cart
            </a>
        @else
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-auto" data-add-to-cart>
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-md border border-brand-orange py-1.5 text-[13px] font-medium text-brand-orange transition hover:bg-brand-orange hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-current">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L21.65 7H5.12" />
                    </svg>
                    Add to cart
                </button>
            </form>
        @endif
    </div>
</div>
