<x-admin-layout title="Order {{ $order->order_number }} - Raimart Admin">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-2xl font-bold">Order {{ $order->order_number }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
                class="flex items-center gap-2 rounded bg-brand-orange px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-navy">
                <i class="fa-solid fa-file-invoice"></i> Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-brand-navy/60 hover:text-brand-orange">&larr; Back to Orders</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5 lg:col-span-2">
            <h2 class="font-serif text-lg font-bold">Items</h2>
            <table class="mt-4 w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-brand-navy/10 text-brand-navy/50">
                        <th class="pb-2 font-medium">Product</th>
                        <th class="pb-2 font-medium">Price</th>
                        <th class="pb-2 font-medium">Qty</th>
                        <th class="pb-2 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b border-brand-navy/5">
                            <td class="py-3">{{ $item->product_name }}</td>
                            <td class="py-3">৳{{ number_format($item->price, 2) }}</td>
                            <td class="py-3">{{ $item->quantity }}</td>
                            <td class="py-3">৳{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 space-y-1 text-sm">
                <div class="flex justify-between"><span class="text-brand-navy/60">Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-brand-navy/60">Shipping</span><span>৳{{ number_format($order->shipping_fee, 2) }}</span></div>
                <div class="flex justify-between font-semibold"><span>Total</span><span>৳{{ number_format($order->total, 2) }}</span></div>
            </div>

            @if ($order->payments->count())
                <h2 class="mt-6 font-serif text-lg font-bold">Payments</h2>
                <table class="mt-3 w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-brand-navy/10 text-brand-navy/50">
                            <th class="pb-2 font-medium">Gateway</th>
                            <th class="pb-2 font-medium">Transaction ID</th>
                            <th class="pb-2 font-medium">Amount</th>
                            <th class="pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->payments as $payment)
                            <tr class="border-b border-brand-navy/5">
                                <td class="py-3 capitalize">{{ $payment->gateway }}</td>
                                <td class="py-3">{{ $payment->transaction_id ?: '—' }}</td>
                                <td class="py-3">৳{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-3 capitalize">{{ $payment->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
                <h2 class="font-serif text-lg font-bold">Customer</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div><dt class="text-brand-navy/50">Name</dt><dd>{{ $order->name }}</dd></div>
                    <div><dt class="text-brand-navy/50">Phone</dt><dd>{{ $order->phone }}</dd></div>
                    <div><dt class="text-brand-navy/50">Email</dt><dd>{{ $order->email ?: '—' }}</dd></div>
                    <div><dt class="text-brand-navy/50">Address</dt><dd>{{ $order->address }}, {{ $order->city }}</dd></div>
                    @if ($order->notes)
                        <div><dt class="text-brand-navy/50">Notes</dt><dd>{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
                <h2 class="font-serif text-lg font-bold">Update Status</h2>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                        @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-lg bg-brand-orange py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Update</button>
                </form>

                <h2 class="mt-6 font-serif text-lg font-bold">Payment Status</h2>
                <form action="{{ route('admin.orders.payment', $order) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status" class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                        @foreach (['unpaid', 'paid', 'refunded'] as $ps)
                            <option value="{{ $ps }}" @selected($order->payment_status === $ps)>{{ ucfirst($ps) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-lg border border-brand-orange py-2.5 text-sm font-semibold text-brand-orange transition hover:bg-brand-orange hover:text-white">Mark payment</button>
                </form>

                <h2 class="mt-6 font-serif text-lg font-bold">Courier &amp; Notes</h2>
                <p class="mt-0.5 text-xs text-brand-navy/50">Internal only — never shown to the customer.</p>
                <form action="{{ route('admin.orders.tracking', $order) }}" method="POST" class="mt-3 space-y-3">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name) }}" placeholder="Courier (e.g. Pathao, Steadfast)"
                        class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Tracking / consignment number"
                        class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                    <textarea name="admin_note" rows="3" placeholder="Internal note about this order..."
                        class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('admin_note', $order->admin_note) }}</textarea>
                    <button type="submit" class="w-full rounded-lg border border-brand-navy/20 py-2.5 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">Save tracking</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
