<x-layout title="Your Cart - Raimart">
    @if ($items->isNotEmpty())
        {{-- GA4: view_cart --}}
        <x-ga-event event="view_cart" :ecommerce="[
            'currency' => \App\Support\Ga4::currency(),
            'value' => round((float) $subtotal, 2),
            'items' => \App\Support\Ga4::cartItems($items),
        ]" />
    @endif

    <section class="mx-auto gb-container py-10">
        <h1 class="font-serif text-2xl font-bold">Your Cart</h1>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="mt-10 rounded-xl bg-white p-10 text-center shadow-sm ring-1 ring-brand-navy/5">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-brand-navy/20 mx-auto">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L21.65 7H5.12" />
                </svg>
                <p class="mt-4 text-brand-navy/60">Your cart is empty.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_320px]">
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-brand-navy/5">
                            <img src="{{ image_url($item->product->main_image, urlencode($item->product->name)) }}" class="h-20 w-20 rounded-lg object-cover">

                            <div class="flex-1">
                                <a href="{{ route('products.show', $item->product) }}" class="font-medium text-brand-navy hover:text-brand-orange">{{ $item->product->name }}</a>
                                
                                @if(!empty($item->options))
                                    <div class="mt-1 flex flex-wrap gap-2 text-xs text-brand-navy/60">
                                        @foreach($item->options as $key => $val)
                                            <span class="rounded bg-brand-bg px-2 py-0.5 border border-brand-navy/10">{{ $key }}: <span class="font-medium text-brand-navy">{{ $val }}</span></span>
                                        @endforeach
                                    </div>
                                @endif

                                <p class="mt-2 text-sm text-brand-navy/50">৳{{ number_format($item->product->price) }} each</p>
                            </div>

                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2" x-data="{ qty: {{ $item->quantity }}, maxQty: {{ $item->product->stock }} }">
                                @csrf
                                @method('PATCH')
                                <div class="flex h-10 items-center rounded-lg border border-brand-navy/15 bg-white overflow-hidden">
                                    <button type="button" @click="if(qty > 1) { qty--; $nextTick(() => $el.closest('form').submit()) }" class="flex h-full w-8 items-center justify-center text-brand-navy hover:bg-brand-navy/5 transition-colors">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" name="quantity" x-model="qty" min="1" :max="maxQty" class="w-10 text-center text-sm font-semibold text-brand-navy focus:outline-none bg-transparent appearance-none p-0 border-none [-moz-appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" readonly>
                                    <button type="button" @click="if(qty < maxQty) { qty++; $nextTick(() => $el.closest('form').submit()) }" class="flex h-full w-8 items-center justify-center text-brand-navy hover:bg-brand-navy/5 transition-colors">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </form>

                            <p class="w-24 text-right font-semibold text-brand-navy">৳{{ number_format($item->subtotal) }}</p>

                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-brand-navy/40 hover:text-red-500" aria-label="Remove">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="h-fit rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
                    <h2 class="font-serif text-lg font-bold">Order Summary</h2>
                    <div class="mt-4 flex justify-between text-sm">
                        <span class="text-brand-navy/60">Subtotal</span>
                        <span class="font-semibold">৳{{ number_format($subtotal) }}</span>
                    </div>
                    <p class="mt-1 text-xs text-brand-navy/40">Shipping calculated at checkout.</p>

                    <a href="{{ route('checkout.index') }}" class="mt-6 block rounded-lg bg-brand-orange py-3 text-center text-sm font-semibold text-white hover:bg-brand-navy">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @endif
    </section>
</x-layout>
