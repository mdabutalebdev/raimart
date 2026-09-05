<x-admin-layout title="Orders - Raimart Admin">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl font-bold">Orders</h1>
            <p class="mt-0.5 text-sm text-brand-navy/60">
                {{ number_format($orders->total()) }} orders &middot;
                <span class="font-semibold text-brand-orange">৳{{ number_format($filteredTotal) }}</span> value
            </p>
        </div>
        <div class="flex shrink-0 gap-2">
            <a href="{{ route('admin.orders.export', request()->query()) }}"
                class="flex items-center gap-2 rounded border border-brand-navy/15 px-4 py-2 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('admin.orders.create') }}" class="flex items-center gap-2 rounded bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Create Order
            </a>
        </div>
    </div>

    {{-- Quick chips --}}
    @php
        $chips = [
            ['label' => 'All', 'count' => $counts['all'], 'params' => []],
            ['label' => 'Pending', 'count' => $counts['pending'], 'params' => ['status' => 'pending']],
            ['label' => 'Processing', 'count' => $counts['processing'], 'params' => ['status' => 'processing']],
            ['label' => 'Unpaid', 'count' => $counts['unpaid'], 'params' => ['payment_status' => 'unpaid']],
        ];
    @endphp
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($chips as $chip)
            @php
                $active = count($chip['params'])
                    ? collect($chip['params'])->every(fn ($v, $k) => request($k) === $v)
                    : (! request('status') && ! request('payment_status'));
            @endphp
            <a href="{{ route('admin.orders.index', $chip['params']) }}"
                class="flex items-center gap-2 rounded border px-3 py-1.5 text-xs font-semibold transition
                    {{ $active ? 'border-brand-orange bg-brand-orange text-white' : 'border-brand-navy/15 text-brand-navy hover:border-brand-orange hover:text-brand-orange' }}">
                {{ $chip['label'] }}
                <span class="rounded px-1.5 py-0.5 text-[10px] {{ $active ? 'bg-white/20' : 'bg-brand-navy/5' }}">{{ $chip['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form action="{{ route('admin.orders.index') }}" class="mt-4 rounded border border-brand-navy/10 bg-white p-3">
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, phone, email..."
                class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none lg:col-span-2">

            <select name="status" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">All statuses</option>
                @foreach (\App\Http\Controllers\Admin\OrderController::STATUSES as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <select name="payment_status" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any payment status</option>
                @foreach (['unpaid', 'paid', 'refunded'] as $ps)
                    <option value="{{ $ps }}" @selected(request('payment_status') === $ps)>{{ ucfirst($ps) }}</option>
                @endforeach
            </select>

            <select name="payment_method" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any payment method</option>
                <option value="cod" @selected(request('payment_method') === 'cod')>Cash on Delivery</option>
                <option value="piprapay" @selected(request('payment_method') === 'piprapay')>PipraPay</option>
                <option value="manual" @selected(request('payment_method') === 'manual')>Manual</option>
            </select>

            <select name="area" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any area</option>
                <option value="inside" @selected(request('area') === 'inside')>Inside Dhaka</option>
                <option value="outside" @selected(request('area') === 'outside')>Outside Dhaka</option>
            </select>

            <div class="flex gap-2">
                <input type="date" name="from" value="{{ request('from') }}" title="From date"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <input type="date" name="to" value="{{ request('to') }}" title="To date"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <div class="flex gap-2">
                <input type="number" name="min_total" value="{{ request('min_total') }}" placeholder="Min ৳" min="0"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <input type="number" name="max_total" value="{{ request('max_total') }}" placeholder="Max ৳" min="0"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <select name="sort" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Newest first</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                <option value="total_high" @selected(request('sort') === 'total_high')>Total: high → low</option>
                <option value="total_low" @selected(request('sort') === 'total_low')>Total: low → high</option>
            </select>
        </div>

        <div class="mt-2 flex items-center gap-2">
            <button type="submit" class="rounded bg-brand-navy px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-orange">Apply filters</button>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-brand-navy/50 hover:text-brand-orange">Reset</a>

            <select name="per_page" onchange="this.form.submit()" class="ml-auto rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                @foreach ([20, 50, 100] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 20) === $size)>{{ $size }} / page</option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Table --}}
    <div class="mt-4 overflow-x-auto rounded border border-brand-navy/10 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-brand-navy/[0.03] text-xs uppercase tracking-wide text-brand-navy/60">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">Order #</th>
                    <th class="px-4 py-2.5 font-semibold">Date</th>
                    <th class="px-4 py-2.5 font-semibold">Customer</th>
                    <th class="px-4 py-2.5 font-semibold">Items</th>
                    <th class="px-4 py-2.5 font-semibold">Total</th>
                    <th class="px-4 py-2.5 font-semibold">Payment</th>
                    <th class="px-4 py-2.5 font-semibold">Status</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($orders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'shipped' => 'bg-indigo-100 text-indigo-700',
                            'delivered' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <tr class="transition hover:bg-brand-navy/[0.02]">
                        <td class="px-4 py-2.5">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-xs font-bold text-brand-orange hover:underline">{{ $order->order_number }}</a>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2.5 text-xs text-brand-navy/60">{{ $order->created_at?->format('d M Y') }}<br>{{ $order->created_at?->format('h:i A') }}</td>
                        <td class="px-4 py-2.5">
                            <p class="font-medium text-brand-navy">{{ $order->name }}</p>
                            <p class="text-xs text-brand-navy/50">{{ $order->phone }}</p>
                        </td>
                        <td class="px-4 py-2.5 text-brand-navy/70">{{ $order->items->sum('quantity') }}</td>
                        <td class="whitespace-nowrap px-4 py-2.5 font-semibold">৳{{ number_format($order->total) }}</td>
                        <td class="px-4 py-2.5">
                            <p class="text-xs uppercase text-brand-navy/60">{{ $order->payment_method }}</p>
                            <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="rounded px-2 py-0.5 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2.5 text-right">
                            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="text-brand-navy/50 hover:text-brand-orange" title="Invoice">
                                <i class="fa-solid fa-file-invoice text-xs"></i>
                            </a>
                            <a href="{{ route('admin.orders.show', $order) }}" class="ml-3 font-medium text-brand-orange hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-brand-navy/40">No orders match these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</x-admin-layout>
