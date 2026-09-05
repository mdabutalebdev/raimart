<x-admin-layout title="{{ $customer->name }} - Raimart Admin">
    <div class="flex items-center justify-between gap-3">
        <h1 class="font-serif text-2xl font-bold">{{ $customer->name }}</h1>
        <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-orange">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to customers
        </a>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-[320px_1fr]">
        {{-- Profile --}}
        <div class="space-y-4">
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Profile</h2>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-xs text-brand-navy/50">Email</dt><dd class="font-medium">{{ $customer->email }}</dd></div>
                    <div><dt class="text-xs text-brand-navy/50">Phone</dt><dd class="font-medium">{{ $customer->phone ?: '—' }}</dd></div>
                    <div><dt class="text-xs text-brand-navy/50">Address</dt><dd class="font-medium">{{ $customer->address ?: '—' }}</dd></div>
                    <div><dt class="text-xs text-brand-navy/50">City</dt><dd class="font-medium">{{ $customer->city ?: '—' }}</dd></div>
                    <div><dt class="text-xs text-brand-navy/50">Joined</dt><dd class="font-medium">{{ $customer->created_at?->format('d M Y') }}</dd></div>
                </dl>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded border border-brand-navy/10 bg-white p-4 text-center">
                    <p class="text-2xl font-bold text-brand-navy">{{ $orders->count() }}</p>
                    <p class="text-xs text-brand-navy/50">Total orders</p>
                </div>
                <div class="rounded border border-brand-navy/10 bg-white p-4 text-center">
                    <p class="text-2xl font-bold text-brand-orange">৳{{ number_format($totalSpent) }}</p>
                    <p class="text-xs text-brand-navy/50">Total spent</p>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="overflow-x-auto rounded border border-brand-navy/10 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-brand-navy/[0.03] text-xs uppercase tracking-wide text-brand-navy/60">
                    <tr>
                        <th class="px-4 py-2.5 font-semibold">Order #</th>
                        <th class="px-4 py-2.5 font-semibold">Date</th>
                        <th class="px-4 py-2.5 font-semibold">Items</th>
                        <th class="px-4 py-2.5 font-semibold">Total</th>
                        <th class="px-4 py-2.5 font-semibold">Status</th>
                        <th class="px-4 py-2.5 text-right font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-navy/5">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-2.5 font-mono text-xs font-bold text-brand-orange">{{ $order->order_number }}</td>
                            <td class="px-4 py-2.5 text-xs text-brand-navy/60">{{ $order->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-2.5">{{ $order->items->sum('quantity') }}</td>
                            <td class="px-4 py-2.5 font-semibold">৳{{ number_format($order->total) }}</td>
                            <td class="px-4 py-2.5">
                                <span class="rounded bg-brand-navy/5 px-2 py-0.5 text-xs font-semibold capitalize">{{ $order->status }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-brand-orange hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-brand-navy/40">This customer has not ordered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
