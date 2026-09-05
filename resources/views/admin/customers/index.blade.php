<x-admin-layout title="Customers - Raimart Admin">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl font-bold">Customers</h1>
            <p class="mt-0.5 text-sm text-brand-navy/60">
                {{ number_format($stats['total']) }} registered &middot;
                {{ number_format($stats['with_orders']) }} have ordered &middot;
                {{ number_format($stats['guests']) }} guest orders
            </p>
        </div>
        <a href="{{ route('admin.customers.export', request()->query()) }}"
            class="flex shrink-0 items-center gap-2 rounded border border-brand-navy/15 px-4 py-2 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">
            <i class="fa-solid fa-file-csv"></i> Export CSV
        </a>
    </div>

    <form action="{{ route('admin.customers.index') }}" class="mt-4 flex flex-wrap gap-2 rounded border border-brand-navy/10 bg-white p-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email or phone..."
            class="w-full max-w-xs rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">

        <select name="has_orders" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            <option value="">All customers</option>
            <option value="yes" @selected(request('has_orders') === 'yes')>With orders</option>
            <option value="no" @selected(request('has_orders') === 'no')>Never ordered</option>
        </select>

        <select name="sort" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            <option value="">Newest first</option>
            <option value="spent" @selected(request('sort') === 'spent')>Highest spend</option>
            <option value="orders" @selected(request('sort') === 'orders')>Most orders</option>
            <option value="name" @selected(request('sort') === 'name')>Name (A–Z)</option>
        </select>

        <button type="submit" class="rounded bg-brand-navy px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-orange">Filter</button>
        <a href="{{ route('admin.customers.index') }}" class="self-center text-sm font-medium text-brand-navy/50 hover:text-brand-orange">Reset</a>
    </form>

    <div class="mt-4 overflow-x-auto rounded border border-brand-navy/10 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-brand-navy/[0.03] text-xs uppercase tracking-wide text-brand-navy/60">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">Customer</th>
                    <th class="px-4 py-2.5 font-semibold">Contact</th>
                    <th class="px-4 py-2.5 font-semibold">Location</th>
                    <th class="px-4 py-2.5 font-semibold">Orders</th>
                    <th class="px-4 py-2.5 font-semibold">Total Spent</th>
                    <th class="px-4 py-2.5 font-semibold">Joined</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($customers as $customer)
                    <tr class="transition hover:bg-brand-navy/[0.02]">
                        <td class="px-4 py-2.5 font-medium text-brand-navy">{{ $customer->name }}</td>
                        <td class="px-4 py-2.5 text-xs text-brand-navy/60">
                            <p>{{ $customer->email }}</p>
                            @if ($customer->phone)<p>{{ $customer->phone }}</p>@endif
                        </td>
                        <td class="px-4 py-2.5 text-brand-navy/70">{{ $customer->city ?: '—' }}</td>
                        <td class="px-4 py-2.5">
                            <span class="rounded bg-brand-navy/5 px-2 py-0.5 text-xs font-semibold">{{ $customer->orders_count }}</span>
                        </td>
                        <td class="px-4 py-2.5 font-semibold text-brand-orange">৳{{ number_format((float) $customer->orders_total) }}</td>
                        <td class="px-4 py-2.5 text-xs text-brand-navy/50">{{ $customer->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-2.5 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="font-medium text-brand-orange hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-brand-navy/40">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</x-admin-layout>
