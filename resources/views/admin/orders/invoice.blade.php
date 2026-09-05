<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }} - Raimart</title>
    @include('partials.favicons')
    @vite(['resources/css/app.css'])

    <style>
        /* Print: drop the toolbar and page chrome so only the invoice comes out. */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet { box-shadow: none !important; border: 0 !important; margin: 0 !important; max-width: none !important; }
            @page { margin: 12mm; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-brand-navy antialiased">
    {{-- Toolbar (never printed) --}}
    <div class="no-print sticky top-0 z-10 border-b border-brand-navy/10 bg-white px-4 py-3">
        <div class="mx-auto flex max-w-3xl items-center justify-between gap-3">
            <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-orange">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to order
            </a>
            <button type="button" onclick="window.print()"
                class="flex items-center gap-2 rounded bg-brand-orange px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-navy">
                <i class="fa-solid fa-print"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <div class="sheet mx-auto my-6 max-w-3xl border border-brand-navy/10 bg-white p-8 shadow-sm">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-6 border-b border-brand-navy/10 pb-6">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="Raimart" class="h-10">
                <div class="mt-3 text-xs leading-relaxed text-brand-navy/60">
                    @if (! empty($settings['contact_address']))<p>{{ $settings['contact_address'] }}</p>@endif
                    @if (! empty($settings['contact_phone']))<p>Phone: {{ $settings['contact_phone'] }}</p>@endif
                    @if (! empty($settings['contact_email']))<p>Email: {{ $settings['contact_email'] }}</p>@endif
                </div>
            </div>

            <div class="text-right">
                <h1 class="text-2xl font-bold uppercase tracking-wide text-brand-orange">Invoice</h1>
                <p class="mt-1 font-mono text-sm font-bold">{{ $order->order_number }}</p>
                <p class="mt-1 text-xs text-brand-navy/60">Date: {{ $order->created_at?->format('d M Y, h:i A') }}</p>
                <div class="mt-2 flex justify-end gap-1.5">
                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $order->payment_status }}
                    </span>
                    <span class="rounded bg-brand-navy/5 px-2 py-0.5 text-[10px] font-bold uppercase text-brand-navy/70">{{ $order->status }}</span>
                </div>
            </div>
        </div>

        {{-- Parties --}}
        <div class="grid gap-6 py-6 sm:grid-cols-2">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wide text-brand-navy/40">Bill To</p>
                <p class="mt-1.5 font-bold">{{ $order->name }}</p>
                <div class="mt-1 space-y-0.5 text-sm text-brand-navy/70">
                    <p>{{ $order->phone }}</p>
                    @if ($order->email)<p>{{ $order->email }}</p>@endif
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city }}</p>
                </div>
            </div>

            <div class="sm:text-right">
                <p class="text-[11px] font-bold uppercase tracking-wide text-brand-navy/40">Order Details</p>
                <div class="mt-1.5 space-y-0.5 text-sm text-brand-navy/70">
                    <p>Payment: <span class="font-medium uppercase text-brand-navy">{{ $order->payment_method }}</span></p>
                    <p>Delivery: <span class="font-medium text-brand-navy">{{ $order->delivery_area === 'inside' ? 'Inside Dhaka' : 'Outside Dhaka' }}</span></p>
                    @if ($order->coupon_code)
                        <p>Coupon: <span class="font-medium text-brand-navy">{{ $order->coupon_code }}</span></p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items --}}
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-y border-brand-navy/10 bg-brand-navy/[0.03] text-[11px] uppercase tracking-wide text-brand-navy/60">
                    <th class="py-2.5 pl-2 font-semibold">#</th>
                    <th class="py-2.5 font-semibold">Product</th>
                    <th class="py-2.5 text-center font-semibold">Qty</th>
                    <th class="py-2.5 text-right font-semibold">Price</th>
                    <th class="py-2.5 pr-2 text-right font-semibold">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @foreach ($order->items as $i => $item)
                    <tr>
                        <td class="py-2.5 pl-2 text-brand-navy/50">{{ $i + 1 }}</td>
                        <td class="py-2.5">
                            <p class="font-medium">{{ $item->product_name }}</p>
                            @if (! empty($item->options))
                                <p class="text-xs text-brand-navy/50">
                                    @foreach ($item->options as $key => $val){{ $key }}: {{ $val }}@if (! $loop->last) &middot; @endif @endforeach
                                </p>
                            @endif
                        </td>
                        <td class="py-2.5 text-center">{{ $item->quantity }}</td>
                        <td class="py-2.5 text-right">৳{{ number_format($item->price) }}</td>
                        <td class="py-2.5 pr-2 text-right font-medium">৳{{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="mt-4 flex justify-end">
            <div class="w-full max-w-xs space-y-1.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-brand-navy/60">Subtotal</span>
                    <span>৳{{ number_format($order->subtotal) }}</span>
                </div>

                @if ($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span>− ৳{{ number_format($order->discount) }}</span>
                    </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-brand-navy/60">Delivery</span>
                    <span>{{ $order->shipping_fee > 0 ? '৳'.number_format($order->shipping_fee) : 'FREE' }}</span>
                </div>

                <div class="flex justify-between border-t border-brand-navy/15 pt-2 text-base font-bold">
                    <span>Total</span>
                    <span class="text-brand-orange">৳{{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        @if ($order->notes)
            <div class="mt-6 rounded border border-brand-navy/10 bg-brand-navy/[0.02] p-3">
                <p class="text-[11px] font-bold uppercase tracking-wide text-brand-navy/40">Order Note</p>
                <p class="mt-1 text-sm text-brand-navy/70">{{ $order->notes }}</p>
            </div>
        @endif

        <div class="mt-8 border-t border-brand-navy/10 pt-4 text-center text-xs text-brand-navy/50">
            <p class="font-medium text-brand-navy/70">Thank you for shopping with Raimart!</p>
            <p class="mt-1">This is a computer generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
