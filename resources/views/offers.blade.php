<x-layout title="Offers - Raimart">
    @if ($products->isNotEmpty())
        {{-- GA4: view_item_list --}}
        <x-ga-event event="view_item_list" :ecommerce="[
            'item_list_id' => 'offers',
            'item_list_name' => 'Offers',
            'items' => \App\Support\Ga4::itemsFromProducts($products->items(), ['item_list_id' => 'offers', 'item_list_name' => 'Offers']),
        ]" />
    @endif

    <section class="mx-auto gb-container py-5 lg:py-8">
        <div class="rounded-2xl bg-gradient-to-r from-brand-navy to-brand-orange px-6 py-8 text-white sm:px-10 sm:py-10">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Special Deals</p>
            <h1 class="mt-2 font-serif text-2xl font-bold sm:text-3xl">Offers &amp; Discounts</h1>
            <p class="mt-2 max-w-xl text-sm text-white/80">Grab your favourite products at reduced prices — while stocks last.</p>
        </div>

        <div class="mt-6 flex items-center justify-between lg:mt-8">
            <p class="text-sm text-brand-navy/50">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} on offer</p>
        </div>

        <div class="product-row mt-4 lg:mt-6">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full py-16 text-center">
                    <i class="fa-solid fa-tags text-4xl text-brand-navy/20"></i>
                    <p class="mt-4 text-brand-navy/50">No offers available right now. Check back soon!</p>
                    <a href="{{ route('shop') }}" class="mt-4 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Browse all products</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    </section>
</x-layout>
