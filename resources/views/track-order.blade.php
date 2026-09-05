<x-layout title="Track Your Order - Raimart">
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="text-center">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-brand-orange/10 text-2xl text-brand-orange">
                <i class="fa-solid fa-truck-fast"></i>
            </span>
            <h1 class="mt-4 font-serif text-2xl font-bold text-brand-navy sm:text-3xl">Track Your Order</h1>
            <p class="mt-2 text-sm text-brand-navy/60">Enter your order number to see the latest status.</p>
        </div>

        <form action="{{ route('track') }}" method="GET" class="mx-auto mt-6 flex max-w-md gap-3">
            <input
                type="text"
                name="order_number"
                value="{{ request('order_number') }}"
                placeholder="e.g. RM250712ABCDE"
                required
                class="w-full rounded-lg border border-brand-navy/15 px-4 py-3 text-sm uppercase focus:border-brand-orange focus:outline-none"
            >
            <button type="submit" class="shrink-0 rounded-lg bg-brand-orange px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-navy">
                Track
            </button>
        </form>

        @if ($searched && ! $order)
            <div class="mx-auto mt-6 max-w-md rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-600">
                No order found with number <strong>{{ request('order_number') }}</strong>. Please check and try again.
            </div>
        @endif

        @if ($order)
            @php
                $steps = [
                    'pending' => ['label' => 'Order Placed', 'icon' => 'fa-receipt'],
                    'processing' => ['label' => 'Processing', 'icon' => 'fa-box'],
                    'shipped' => ['label' => 'Shipped', 'icon' => 'fa-truck'],
                    'delivered' => ['label' => 'Delivered', 'icon' => 'fa-circle-check'],
                ];
                $order_states = array_keys($steps);
                $isCancelled = $order->status === 'cancelled';
                $currentIndex = $isCancelled ? -1 : array_search($order->status, $order_states);
            @endphp

            <div class="mt-8 rounded-2xl border border-brand-navy/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-brand-navy/10 pb-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-brand-navy/50">Order Number</p>
                        <p class="font-serif text-lg font-bold text-brand-navy">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-wide text-brand-navy/50">Placed On</p>
                        <p class="text-sm font-medium text-brand-navy">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                @if ($isCancelled)
                    <div class="mt-6 rounded-lg bg-red-50 px-4 py-4 text-center text-sm font-medium text-red-600">
                        <i class="fa-solid fa-circle-xmark"></i> This order has been cancelled.
                    </div>
                @else
                    {{-- Status timeline --}}
                    <div class="mt-8 flex items-center justify-between">
                        @foreach ($steps as $state => $step)
                            @php $done = $loop->index <= $currentIndex; @endphp
                            <div class="flex flex-1 flex-col items-center text-center">
                                <div class="flex items-center w-full">
                                    <div class="h-1 flex-1 {{ $loop->first ? 'invisible' : ($loop->index <= $currentIndex ? 'bg-brand-orange' : 'bg-brand-navy/10') }}"></div>
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm {{ $done ? 'bg-brand-orange text-white' : 'bg-brand-navy/10 text-brand-navy/40' }}">
                                        <i class="fa-solid {{ $step['icon'] }}"></i>
                                    </span>
                                    <div class="h-1 flex-1 {{ $loop->last ? 'invisible' : ($loop->index < $currentIndex ? 'bg-brand-orange' : 'bg-brand-navy/10') }}"></div>
                                </div>
                                <span class="mt-2 text-[11px] font-medium sm:text-xs {{ $done ? 'text-brand-navy' : 'text-brand-navy/40' }}">{{ $step['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Payment + delivery info --}}
                <div class="mt-6 grid gap-4 border-t border-brand-navy/10 pt-6 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-brand-navy/50">Deliver To</p>
                        <p class="mt-1 font-medium text-brand-navy">{{ $order->name }}</p>
                        <p class="text-brand-navy/60">{{ $order->phone }}</p>
                        <p class="text-brand-navy/60">{{ $order->address }}, {{ $order->city }}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs uppercase tracking-wide text-brand-navy/50">Payment</p>
                        <p class="mt-1 font-medium text-brand-navy">{{ strtoupper($order->payment_method) }}</p>
                        <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                {{-- Items --}}
                <div class="mt-6 border-t border-brand-navy/10 pt-6">
                    <p class="text-xs uppercase tracking-wide text-brand-navy/50">Items</p>
                    <ul class="mt-3 space-y-3">
                        @foreach ($order->items as $item)
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-brand-navy">{{ $item->product_name }} <span class="text-brand-navy/40">× {{ $item->quantity }}</span></span>
                                <span class="font-medium text-brand-navy">৳{{ number_format($item->subtotal) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4 space-y-1 border-t border-brand-navy/10 pt-4 text-sm">
                        <div class="flex justify-between text-brand-navy/60"><span>Subtotal</span><span>৳{{ number_format($order->subtotal) }}</span></div>
                        <div class="flex justify-between text-brand-navy/60"><span>Shipping</span><span>৳{{ number_format($order->shipping_fee) }}</span></div>
                        <div class="flex justify-between pt-1 font-serif text-base font-bold text-brand-navy"><span>Total</span><span>৳{{ number_format($order->total) }}</span></div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</x-layout>
