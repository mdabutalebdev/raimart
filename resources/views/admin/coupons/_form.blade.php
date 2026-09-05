@csrf

<div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Coupon Code <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required placeholder="EID25"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm uppercase focus:border-brand-orange focus:outline-none">
            <p class="mt-1 text-xs text-gray-500">Customers type this at checkout. Saved in capitals.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Discount Type <span class="text-red-500">*</span></label>
            @php $type = old('type', $coupon->type ?? 'percent'); @endphp
            <select name="type" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                <option value="percent" @selected($type === 'percent')>Percentage (%)</option>
                <option value="fixed" @selected($type === 'fixed')>Fixed amount (৳)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Discount Value <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value) }}" required placeholder="10"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <p class="mt-1 text-xs text-gray-500">e.g. 10 = 10% off, or ৳10 off (depending on type).</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Max Discount (৳)</label>
            <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" placeholder="Leave empty for no cap"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <p class="mt-1 text-xs text-gray-500">Caps a percentage coupon (ignored for fixed amounts).</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Minimum Order (৳)</label>
            <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" placeholder="No minimum"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Usage Limit</label>
            <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Unlimited"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <p class="mt-1 text-xs text-gray-500">Total number of times this code can be used.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Starts At</label>
            <input type="date" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Expires At</label>
            <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>
    </div>

    <div class="grid gap-4 border-t border-brand-navy/10 pt-5 sm:grid-cols-2">
        <label class="flex cursor-pointer items-center gap-3">
            <input type="hidden" name="free_shipping" value="0">
            <input type="checkbox" name="free_shipping" value="1" @checked(old('free_shipping', $coupon->free_shipping))
                class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
            <span class="text-sm font-medium">Also make delivery free</span>
        </label>

        <label class="flex cursor-pointer items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))
                class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
            <span class="text-sm font-medium">Active</span>
        </label>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
            {{ $submitLabel ?? 'Save Coupon' }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-navy">Cancel</a>
    </div>
</div>
