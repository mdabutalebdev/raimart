<div id="cart-drawer" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-brand-navy/50 opacity-0 transition-opacity duration-300" data-cart-close data-cart-backdrop></div>

    <div id="cart-drawer-panel" class="absolute inset-y-0 right-0 flex w-96 max-w-[90vw] translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
        <div class="flex items-center justify-between border-b border-brand-navy/10 px-5 py-4">
            <h2 class="text-sm font-bold uppercase tracking-wide text-brand-navy">Shopping Cart</h2>
            <button type="button" data-cart-close aria-label="Close" class="flex items-center gap-1 text-sm font-medium text-brand-navy/50 hover:text-brand-orange">
                Close <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>

        <div id="cart-drawer-body" class="flex flex-1 flex-col overflow-hidden">
            {{-- Filled by JS (partials.cart-items); server-render current state as the initial content --}}
            @include('partials.cart-items')
        </div>
    </div>
</div>
