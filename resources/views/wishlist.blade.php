<x-layout title="Your Wishlist - Raimart">
    <section class="mx-auto gb-container py-10">
        <h1 class="font-serif text-2xl font-bold">Your Wishlist</h1>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="mt-10 rounded-xl bg-white p-10 text-center shadow-sm ring-1 ring-brand-navy/5">
                <i class="fa-regular fa-heart text-4xl text-brand-navy/20"></i>
                <p class="mt-4 text-brand-navy/60">Your wishlist is empty.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
                    Continue Shopping
                </a>
            </div>
        @else
            <div data-wishlist-page class="product-row mt-6">
                @foreach ($items as $product)
                    <div data-wishlist-card class="transition-all duration-300">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <p data-wishlist-empty class="mt-10 hidden text-center text-brand-navy/40">
                Your wishlist is empty.
            </p>
        @endif
    </section>
</x-layout>
