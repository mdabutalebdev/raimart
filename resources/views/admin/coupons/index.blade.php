<x-admin-layout title="Coupons - Raimart Admin">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl font-bold">Coupons</h1>
            <p class="mt-1 text-sm text-brand-navy/60">Discount codes customers can apply at checkout.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="flex shrink-0 items-center gap-2 rounded-lg bg-brand-orange px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
            <i class="fa-solid fa-plus"></i> Add Coupon
        </a>
    </div>

    <form action="{{ route('admin.coupons.index') }}" class="mt-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code..."
            class="w-full max-w-xs rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <option value="">All statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <button type="submit" class="rounded-lg bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-orange">Filter</button>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
        <table class="w-full text-sm">
            <thead class="bg-brand-navy/[0.03] text-left text-xs uppercase tracking-wide text-brand-navy/60">
                <tr>
                    <th class="px-5 py-3">Code</th>
                    <th class="px-5 py-3">Discount</th>
                    <th class="px-5 py-3">Min order</th>
                    <th class="px-5 py-3">Used</th>
                    <th class="px-5 py-3">Validity</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="px-5 py-3">
                            <span class="rounded bg-brand-orange/10 px-2 py-1 font-mono text-xs font-bold text-brand-orange">{{ $coupon->code }}</span>
                            @if ($coupon->free_shipping)
                                <span class="ml-1 text-[10px] font-semibold uppercase text-green-600">+ free delivery</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium">
                            {{ $coupon->label }}
                            @if ($coupon->type === 'percent' && $coupon->max_discount)
                                <span class="block text-xs text-brand-navy/50">max ৳{{ number_format((float) $coupon->max_discount) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-brand-navy/70">{{ $coupon->min_order_amount ? '৳'.number_format((float) $coupon->min_order_amount) : '—' }}</td>
                        <td class="px-5 py-3 text-brand-navy/70">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}</td>
                        <td class="px-5 py-3 text-xs text-brand-navy/60">
                            @if ($coupon->starts_at || $coupon->expires_at)
                                {{ $coupon->starts_at?->format('d M Y') ?? 'now' }} – {{ $coupon->expires_at?->format('d M Y') ?? 'no end' }}
                            @else
                                Always
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @php $usable = $coupon->isUsable(); @endphp
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $usable ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $usable ? 'Active' : ($coupon->is_active ? 'Expired' : 'Inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="font-medium text-brand-orange hover:underline">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this coupon?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-brand-navy/40">No coupons yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $coupons->links() }}</div>
</x-admin-layout>
