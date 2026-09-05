<x-layout title="Order Confirmation - Raimart">
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-brand-orange/30 bg-brand-bg px-4 py-3 text-sm text-brand-navy">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl bg-white p-8 text-center shadow-sm ring-1 ring-brand-navy/5">
            @if ($order->payment_status === 'paid')
                <i class="fa-solid fa-circle-check text-4xl text-green-500"></i>
                <h1 class="mt-4 font-serif text-2xl font-bold">Payment Successful!</h1>
            @else
                <i class="fa-solid fa-circle-check text-4xl text-brand-orange"></i>
                <h1 class="mt-4 font-serif text-2xl font-bold">Order Placed Successfully!</h1>
            @endif

            <p class="mt-2 text-brand-navy/60">Thank you, {{ $order->name }}. Your order number is <strong>{{ $order->order_number }}</strong>.</p>

            @if ($order->payment_method === 'piprapay' && $order->payment_status !== 'paid')
                <a href="{{ route('checkout.pay', $order) }}" class="mt-6 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
                    Pay Now
                </a>
            @endif
        </div>

        <div class="mt-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Order Details</h2>

            <table class="mt-4 w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-brand-navy/10 text-brand-navy/50">
                        <th class="pb-2 font-medium">Product</th>
                        <th class="pb-2 font-medium">Qty</th>
                        <th class="pb-2 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b border-brand-navy/5">
                            <td class="py-2">{{ $item->product_name }}</td>
                            <td class="py-2">{{ $item->quantity }}</td>
                            <td class="py-2">৳{{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 space-y-1 text-sm">
                <div class="flex justify-between"><span class="text-brand-navy/60">Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-brand-navy/60">Shipping</span><span>৳{{ number_format($order->shipping_fee, 2) }}</span></div>
                <div class="flex justify-between font-semibold"><span>Total</span><span>৳{{ number_format($order->total, 2) }}</span></div>
            </div>

            <div class="mt-4 flex justify-between border-t border-brand-navy/10 pt-4 text-sm">
                <span class="text-brand-navy/60">Payment Method</span>
                <span class="capitalize">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'PipraPay' }} &middot; {{ $order->payment_status }}</span>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('shop') }}" class="text-sm font-medium text-brand-orange hover:underline">Continue Shopping &rarr;</a>
        </div>
    </section>
</x-layout>
