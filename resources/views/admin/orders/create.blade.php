<x-admin-layout title="Create Order - Raimart Admin">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl font-bold">Create Order</h1>
            <p class="mt-0.5 text-sm text-brand-navy/60">Place an order by hand — for phone, WhatsApp or walk-in customers.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-orange">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to orders
        </a>
    </div>

    @if ($errors->any())
        <div class="mt-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        // [id => [name, price, stock]] for the line-item picker
        $productData = $products->mapWithKeys(fn ($p) => [$p->id => [
            'name' => $p->name,
            'price' => (float) $p->price,
            'stock' => (int) $p->stock,
        ]])->all();
    @endphp

    <form action="{{ route('admin.orders.store') }}" method="POST"
        x-data="manualOrder(@js($productData), {{ $areas['inside']['fee'] }}, {{ $areas['outside']['fee'] }})"
        class="mt-4 grid items-start gap-4 lg:grid-cols-[1fr_340px]">
        @csrf

        <div class="space-y-4">
            {{-- Items --}}
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Products</h2>

                <template x-for="(line, index) in lines" :key="index">
                    <div class="mb-2 grid gap-2 sm:grid-cols-[1fr_110px_110px_40px]">
                        <select :name="`items[${index}][product_id]`" x-model="line.product_id" required
                            class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                            <option value="">Select product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} — ৳{{ number_format($product->price) }} ({{ $product->stock }} in stock)</option>
                            @endforeach
                        </select>

                        <input type="number" :name="`items[${index}][quantity]`" x-model.number="line.quantity" min="1" required placeholder="Qty"
                            class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">

                        <div class="flex items-center justify-end rounded border border-brand-navy/10 bg-brand-navy/[0.02] px-3 text-sm font-semibold"
                            x-text="'৳' + lineTotal(line).toLocaleString()"></div>

                        <button type="button" @click="removeLine(index)" x-show="lines.length > 1"
                            class="flex items-center justify-center rounded border border-brand-navy/10 text-red-500 transition hover:bg-red-50">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </template>

                <button type="button" @click="addLine()"
                    class="mt-1 flex items-center gap-2 rounded border border-brand-orange px-4 py-2 text-sm font-semibold text-brand-orange transition hover:bg-brand-orange hover:text-white">
                    <i class="fa-solid fa-plus text-xs"></i> Add product
                </button>
            </div>

            {{-- Customer --}}
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Customer</h2>

                <div class="grid gap-2 sm:grid-cols-2">
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full name *"
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Phone *"
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email (optional)"
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none sm:col-span-2">
                    <input type="text" name="address" value="{{ old('address') }}" required placeholder="Address *"
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none sm:col-span-2">

                    <select name="city" x-model="district" required
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                        <option value="">Select district *</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district }}" @selected(old('city') === $district)>{{ $district }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center rounded border border-brand-navy/10 bg-brand-navy/[0.02] px-3 text-sm text-brand-navy/70">
                        <span x-text="district === 'Dhaka' ? '{{ $areas['inside']['label'] }}' : '{{ $areas['outside']['label'] }}'"></span>
                    </div>

                    <textarea name="notes" rows="2" placeholder="Order note (optional)"
                        class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none sm:col-span-2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="space-y-4">
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Payment &amp; Status</h2>

                <div class="space-y-2">
                    <div>
                        <label class="text-xs font-medium text-brand-navy/60">Payment method</label>
                        <select name="payment_method" class="mt-1 w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                            <option value="cod">Cash on Delivery</option>
                            <option value="manual">Manual / Bank transfer</option>
                            <option value="piprapay">PipraPay</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-brand-navy/60">Payment status</label>
                        <select name="payment_status" class="mt-1 w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-brand-navy/60">Order status</label>
                        <select name="status" class="mt-1 w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                            @foreach (\App\Http\Controllers\Admin\OrderController::STATUSES as $status)
                                <option value="{{ $status }}" @selected($status === 'pending')>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Totals</h2>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-brand-navy/60">Subtotal</span>
                        <span class="font-semibold" x-text="'৳' + subtotal.toLocaleString()"></span>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <label class="text-brand-navy/60">Discount ৳</label>
                        <input type="number" name="discount" x-model.number="discount" min="0" step="1"
                            class="w-24 rounded border border-brand-navy/15 px-2 py-1 text-right text-sm focus:border-brand-orange focus:outline-none">
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <label class="text-brand-navy/60">Delivery ৳</label>
                        <input type="number" name="shipping_fee" x-model.number="shipping" min="0" step="1"
                            class="w-24 rounded border border-brand-navy/15 px-2 py-1 text-right text-sm focus:border-brand-orange focus:outline-none">
                    </div>

                    <div class="flex justify-between border-t border-brand-navy/10 pt-2 text-base font-bold">
                        <span>Total</span>
                        <span class="text-brand-orange" x-text="'৳' + total.toLocaleString()"></span>
                    </div>
                </div>

                <button type="submit" class="mt-4 w-full rounded bg-brand-orange py-2.5 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-brand-navy">
                    Create Order
                </button>
                <p class="mt-2 text-center text-xs text-brand-navy/50">Stock is reduced and a Telegram alert is sent.</p>
            </div>
        </div>
    </form>
</x-admin-layout>
