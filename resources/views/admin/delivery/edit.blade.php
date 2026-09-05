<x-admin-layout title="Delivery Charge - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Delivery Charge</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Set the delivery rate per area and the order value that earns free delivery.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.delivery.update') }}" method="POST" class="mt-6 max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Delivery Areas</h2>

            <div class="grid gap-5 sm:grid-cols-[1fr_180px]">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Area 1 Name</label>
                    <input type="text" name="shipping_inside_label" value="{{ old('shipping_inside_label', $settings['shipping_inside_label'] ?? 'Inside Dhaka') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Charge (৳) <span class="text-red-500">*</span></label>
                    <input type="number" step="1" min="0" name="shipping_inside_fee" required
                        value="{{ old('shipping_inside_fee', $settings['shipping_inside_fee'] ?? 60) }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Area 2 Name</label>
                    <input type="text" name="shipping_outside_label" value="{{ old('shipping_outside_label', $settings['shipping_outside_label'] ?? 'Outside Dhaka') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Charge (৳) <span class="text-red-500">*</span></label>
                    <input type="number" step="1" min="0" name="shipping_outside_fee" required
                        value="{{ old('shipping_outside_fee', $settings['shipping_outside_fee'] ?? 120) }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            </div>
        </div>

        <div class="space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Free Delivery Offer</h2>

            <div class="max-w-xs">
                <label class="block text-sm font-medium text-gray-700">Free delivery over (৳)</label>
                <input type="number" step="1" min="0" name="free_shipping_threshold"
                    value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '') }}" placeholder="e.g. 2000"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <p class="text-xs text-gray-500">
                Orders at or above this amount get free delivery, and the cart shows a green progress bar
                telling customers how much more to add. Leave empty or 0 to turn the offer off.
            </p>
        </div>

        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Delivery Charges</button>
    </form>
</x-admin-layout>
