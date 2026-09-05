<x-layout title="Our Brands - Raimart">
    <section class="mx-auto gb-container py-6 lg:py-10">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="font-serif text-xl font-bold sm:text-2xl">Our Brands</h1>
                <span class="mt-2 block h-1 w-14 rounded-full bg-brand-orange"></span>
            </div>
            <a href="{{ route('shop') }}" class="text-sm font-semibold text-brand-orange hover:text-brand-navy">Shop All</a>
        </div>

        @if ($brands->count())
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:mt-8 lg:grid-cols-4">
                @foreach ($brands as $brand)
                    <a href="{{ route('shop', ['brand' => $brand->slug]) }}"
                        class="group flex flex-col items-center justify-center rounded-[4px] border border-[#cccccc] bg-white p-6 transition hover:border-brand-orange">
                        <div class="flex h-20 w-full items-center justify-center">
                            @if ($brand->logo)
                                <img src="{{ image_url($brand->logo, urlencode($brand->name)) }}" alt="{{ $brand->name }}" class="max-h-16 max-w-full object-contain opacity-90 transition group-hover:opacity-100">
                            @else
                                <span class="font-serif text-xl font-bold text-brand-navy/70 transition group-hover:text-brand-orange">{{ $brand->name }}</span>
                            @endif
                        </div>
                        <p class="mt-3 text-sm font-semibold text-brand-navy">{{ $brand->name }}</p>
                        <p class="text-xs text-brand-navy/50">{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-brand-navy/15 bg-white py-16 text-center">
                <i class="fa-solid fa-trademark text-4xl text-brand-navy/15"></i>
                <p class="mt-4 text-brand-navy/50">No brands available yet.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Browse all products</a>
            </div>
        @endif
    </section>
</x-layout>
