<x-layout title="Checkout - Raimart">
    {{-- GA4: begin_checkout --}}
    <x-ga-event event="begin_checkout" :ecommerce="[
        'currency' => \App\Support\Ga4::currency(),
        'value' => round((float) $subtotal, 2),
        'items' => \App\Support\Ga4::cartItems($items),
    ]" />

    @php
        // Selecting Dhaka bills the "inside" rate, every other district the "outside" rate.
        $selectedDistrict = old('city', auth()->user()->city ?? '');
        $districts = \App\Support\Bangladesh::districts();
        $thanas = \App\Support\Bangladesh::thanas($selectedDistrict);
        $thanaMap = \App\Support\Bangladesh::DISTRICTS;
    @endphp

    <section class="gb-container py-6 md:py-8">
        <div class="text-center">
            <h1 class="text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Checkout</h1>
            <nav class="text-sm text-brand-navy/50">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-orange">Home</a>
                <span class="mx-1">/</span>
                <span class="text-brand-orange">Checkout</span>
            </nav>
        </div>

        @guest
            <div class="mt-6 flex flex-col items-center justify-between gap-3 rounded border border-brand-navy/10 bg-white px-4 py-3 sm:flex-row">
                <p class="text-sm text-brand-navy/70">Have any account? please login or register</p>
                <div class="flex shrink-0 gap-2">
                    <a href="{{ route('login') }}" class="rounded-md border border-brand-navy/20 px-5 py-2 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">Login</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-brand-orange px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-navy">Register</a>
                </div>
            </div>
        @endguest

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="mt-5 grid items-start gap-4 lg:grid-cols-[1fr_380px]">
            @csrf
            {{-- GA4: browser client_id / session_id, filled by app.js so the server-side purchase attributes correctly --}}
            <input type="hidden" name="ga_client_id" id="ga_client_id">
            <input type="hidden" name="ga_session_id" id="ga_session_id">
            {{-- Set from the district picker below --}}
            <input type="hidden" name="delivery_area" id="delivery_area" value="{{ $area }}">

            {{-- ============ LEFT ============ --}}
            <div class="space-y-4">
                {{-- Order review --}}
                <div class="rounded border border-brand-navy/10 bg-white p-4 sm:p-5">
                    <h2 class="mb-4 border-l-4 border-brand-orange pl-3 text-base font-bold text-brand-navy">Order review</h2>

                    <div class="divide-y divide-brand-navy/10">
                        @foreach ($items as $item)
                            <div class="flex items-center gap-3 py-3">
                                <img src="{{ image_url($item->product->main_image, urlencode($item->product->name)) }}" alt="{{ $item->product->name }}"
                                    class="h-14 w-14 shrink-0 rounded border border-brand-navy/10 object-cover">

                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('products.show', $item->product) }}" class="line-clamp-2 text-sm font-medium text-brand-navy hover:text-brand-orange">{{ $item->product->name }}</a>

                                    @if (! empty($item->options))
                                        <div class="mt-1 flex flex-wrap gap-1 text-[11px] text-brand-navy/60">
                                            @foreach ($item->options as $key => $val)
                                                <span class="rounded bg-brand-bg px-1.5 py-0.5">{{ $key }}: <span class="font-medium text-brand-navy">{{ $val }}</span></span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs text-brand-navy/50">Qty:</span>
                                        <div class="flex h-8 items-center overflow-hidden rounded-md border border-brand-navy/15">
                                            <button type="button" data-checkout-dec data-id="{{ $item->id }}" data-qty="{{ $item->quantity }}"
                                                class="flex h-full w-7 items-center justify-center text-brand-navy transition hover:bg-brand-navy/5">
                                                <i class="fa-solid fa-minus text-[10px]"></i>
                                            </button>
                                            <span class="w-8 text-center text-sm font-semibold">{{ $item->quantity }}</span>
                                            <button type="button" data-checkout-inc data-id="{{ $item->id }}" data-qty="{{ $item->quantity }}" data-max="{{ $item->product->stock }}"
                                                class="flex h-full w-7 items-center justify-center text-brand-navy transition hover:bg-brand-navy/5">
                                                <i class="fa-solid fa-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-bold text-brand-orange">৳{{ number_format($item->subtotal) }}</p>
                                    <button type="button" data-checkout-remove data-id="{{ $item->id }}"
                                        class="mt-1 text-brand-navy/30 transition hover:text-red-500" aria-label="Remove item">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping address --}}
                <div class="rounded border border-brand-navy/10 bg-white p-4 sm:p-5">
                    <h2 class="mb-4 border-l-4 border-brand-orange pl-3 text-base font-bold text-brand-navy">Shipping Address</h2>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required placeholder="Your Full Name *"
                                class="w-full rounded border border-brand-navy/15 px-3 py-2.5 text-sm placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                        </div>

                        <div class="flex">
                            <span class="flex shrink-0 items-center rounded-l border border-r-0 border-brand-navy/15 bg-brand-bg px-3 text-sm font-medium text-brand-navy/70">88</span>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required placeholder="017XXXXXXXX"
                                class="w-full rounded-r border border-brand-navy/15 px-4 py-3 text-sm placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="example@gmail.com (Optional)"
                                class="w-full rounded border border-brand-navy/15 px-3 py-2.5 text-sm placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <input type="text" name="address" value="{{ old('address', auth()->user()->address ?? '') }}" required placeholder="ex: House no. / building / street / area"
                                class="w-full rounded border border-brand-navy/15 px-3 py-2.5 text-sm placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                        </div>

                        {{-- District (searchable) --}}
                        <div x-data="searchSelect({ options: @js($districts), selected: @js($selectedDistrict), placeholder: 'Select District' })"
                            data-district-select @click.outside="open = false" class="relative">
                            <input type="hidden" name="city" id="district-select" x-model="selected" required
                                data-inside-fee="{{ $areas['inside']['fee'] }}" data-outside-fee="{{ $areas['outside']['fee'] }}">

                            <button type="button" @click="toggle()"
                                class="flex w-full items-center justify-between gap-2 rounded border border-brand-navy/15 bg-white px-3 py-2.5 text-left text-sm transition focus:border-brand-orange focus:outline-none"
                                :class="open && 'border-brand-orange'">
                                <span x-text="selected || placeholder" :class="selected ? 'text-brand-navy' : 'text-brand-navy/40'"></span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-brand-navy/40 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" style="display:none;" class="absolute z-30 mt-1 w-full overflow-hidden rounded border border-brand-navy/15 bg-white shadow-lg">
                                <div class="relative border-b border-brand-navy/10">
                                    <input type="text" x-ref="search" x-model="query" placeholder="Search district..."
                                        class="w-full px-3 py-2 pr-8 text-sm focus:outline-none">
                                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-xs text-brand-navy/30"></i>
                                </div>
                                <ul class="max-h-52 overflow-y-auto py-1">
                                    <template x-for="option in filtered" :key="option">
                                        <li>
                                            <button type="button" @click="choose(option)"
                                                class="block w-full px-3 py-2 text-left text-sm transition hover:bg-brand-orange/10"
                                                :class="option === selected ? 'bg-brand-orange/10 font-semibold text-brand-orange' : 'text-brand-navy/80'"
                                                x-text="option"></button>
                                        </li>
                                    </template>
                                    <li x-show="filtered.length === 0" class="px-3 py-3 text-center text-xs text-brand-navy/40">No match found</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Thana (searchable, follows the district) --}}
                        <div x-data="searchSelect({ options: @js($thanas), selected: @js(old('thana')), placeholder: 'Select Thana (Optional)' })"
                            x-ref="thanaBox" data-thana-select @click.outside="open = false" class="relative">
                            <input type="hidden" name="thana" x-model="selected">

                            <button type="button" @click="toggle()"
                                class="flex w-full items-center justify-between gap-2 rounded border border-brand-navy/15 bg-white px-3 py-2.5 text-left text-sm transition focus:border-brand-orange focus:outline-none"
                                :class="open && 'border-brand-orange'">
                                <span x-text="selected || placeholder" :class="selected ? 'text-brand-navy' : 'text-brand-navy/40'"></span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-brand-navy/40 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" style="display:none;" class="absolute z-30 mt-1 w-full overflow-hidden rounded border border-brand-navy/15 bg-white shadow-lg">
                                <div class="relative border-b border-brand-navy/10">
                                    <input type="text" x-ref="search" x-model="query" placeholder="Search thana..."
                                        class="w-full px-3 py-2 pr-8 text-sm focus:outline-none">
                                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-xs text-brand-navy/30"></i>
                                </div>
                                <ul class="max-h-52 overflow-y-auto py-1">
                                    <template x-for="option in filtered" :key="option">
                                        <li>
                                            <button type="button" @click="choose(option)"
                                                class="block w-full px-3 py-2 text-left text-sm transition hover:bg-brand-orange/10"
                                                :class="option === selected ? 'bg-brand-orange/10 font-semibold text-brand-orange' : 'text-brand-navy/80'"
                                                x-text="option"></button>
                                        </li>
                                    </template>
                                    <li x-show="filtered.length === 0" class="px-3 py-3 text-center text-xs text-brand-navy/40">
                                        Select a district first
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-xs text-brand-navy/50 sm:col-span-2">
                            {{ $areas['inside']['label'] }}: ৳{{ number_format($areas['inside']['fee']) }} &middot;
                            {{ $areas['outside']['label'] }}: ৳{{ number_format($areas['outside']['fee']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ============ RIGHT ============ --}}
            <div class="space-y-4">
                {{-- Payment method --}}
                <div class="rounded border border-brand-navy/10 bg-white p-4 sm:p-5">
                    <h2 class="mb-4 border-l-4 border-brand-orange pl-3 text-base font-bold text-brand-navy">Payment method</h2>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="relative flex cursor-pointer items-center gap-2.5 rounded border border-brand-navy/15 p-2.5 transition has-[:checked]:border-brand-orange has-[:checked]:bg-brand-orange/5">
                            <input type="radio" name="payment_method" value="cod" @checked(old('payment_method', 'cod') === 'cod') class="peer sr-only">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-green-100 text-green-600">
                                <i class="fa-solid fa-money-bill-wave text-sm"></i>
                            </span>
                            <span class="text-sm font-medium text-brand-navy">Cash On Delivery</span>
                            <i class="fa-solid fa-circle-check absolute right-3 hidden text-brand-orange peer-checked:block"></i>
                        </label>

                        <label class="relative flex cursor-pointer items-center gap-2.5 rounded border border-brand-navy/15 p-2.5 transition has-[:checked]:border-brand-orange has-[:checked]:bg-brand-orange/5">
                            <input type="radio" name="payment_method" value="piprapay" @checked(old('payment_method') === 'piprapay') class="peer sr-only">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-pink-100 text-pink-600">
                                <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                            </span>
                            <span class="text-sm font-medium text-brand-navy">Online Payment</span>
                            <i class="fa-solid fa-circle-check absolute right-3 hidden text-brand-orange peer-checked:block"></i>
                        </label>
                    </div>

                    <p class="mt-3 text-xs text-brand-navy/50">Online payment supports bKash, Nagad, Rocket &amp; cards via PipraPay.</p>
                </div>

                {{-- Coupon --}}
                <div class="rounded border border-brand-navy/10 bg-white" x-data="accordion({{ $coupon ? 'true' : 'false' }})">
                    <button type="button" @click="toggle()" class="flex w-full items-center justify-between gap-2 px-5 py-4 text-left sm:px-6">
                        <span class="text-sm font-semibold text-brand-navy">Have any coupon or gift voucher?</span>
                        <i class="fa-solid fa-chevron-down text-xs text-brand-navy/40 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" @if (! $coupon) style="display:none;" @endif class="border-t border-brand-navy/10 px-5 py-4 sm:px-6">
                        @if ($coupon)
                            <div class="flex items-center justify-between gap-2 rounded border border-green-200 bg-green-50 px-3 py-2.5">
                                <span class="text-sm text-green-700">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    <span class="font-bold">{{ $coupon->code }}</span> — {{ $coupon->label }}
                                </span>
                                <button type="submit" form="coupon-remove-form" class="shrink-0 text-xs font-semibold text-red-500 hover:underline">Remove</button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input type="text" name="code" form="coupon-form" placeholder="Enter coupon code"
                                    class="w-full rounded border border-brand-navy/15 px-3 py-2.5 text-sm uppercase placeholder:normal-case placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                                <button type="submit" form="coupon-form"
                                    class="shrink-0 rounded bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-orange">Apply</button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Totals --}}
                <div class="rounded border border-brand-navy/10 bg-white p-4 sm:p-5">
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-brand-navy/60">Sub total</span>
                            <span class="font-medium">৳{{ number_format($subtotal) }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount{{ $coupon ? ' ('.$coupon->code.')' : '' }}</span>
                                <span>− ৳{{ number_format($discount) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-brand-navy/60">Delivery cost</span>
                            <span class="font-medium" data-shipping-amount>
                                @if ($shippingFee <= 0)
                                    <span class="font-semibold text-green-600">FREE</span>
                                @else
                                    ৳{{ number_format($shippingFee) }}
                                @endif
                            </span>
                        </div>

                        @if ($freeThreshold && $subtotal < $freeThreshold)
                            <p class="text-xs text-brand-navy/50">Add ৳{{ number_format($freeThreshold - $subtotal) }} more for free delivery.</p>
                        @endif

                        <div class="flex justify-between border-t border-brand-navy/10 pt-3 text-lg font-bold text-brand-navy">
                            <span>Total</span>
                            <span data-total-amount>৳{{ number_format($total) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Special notes --}}
                <div class="rounded border border-brand-navy/10 bg-white p-4 sm:p-5">
                    <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold text-brand-navy">
                        Special notes <span class="text-xs font-normal text-brand-navy/50">(Optional)</span>
                    </h2>
                    <textarea name="notes" id="checkout-notes" rows="3" maxlength="300" placeholder="Anything we should know about this order?"
                        class="w-full rounded border border-brand-navy/15 px-3 py-2.5 text-sm placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">{{ old('notes') }}</textarea>
                    <p class="mt-1 text-right text-xs text-brand-navy/40"><span id="notes-count">{{ strlen(old('notes', '')) }}</span> / 300 characters</p>
                </div>

                {{-- Terms + place order --}}
                <div class="space-y-4">
                    <label class="flex cursor-pointer items-start gap-2.5 text-xs text-brand-navy/70">
                        <input type="checkbox" required class="mt-0.5 h-4 w-4 shrink-0 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
                        <span>
                            I have read and agree to the
                            <a href="{{ url('/page/terms-conditions') }}" target="_blank" class="font-medium text-brand-orange hover:underline">Terms and Conditions</a>,
                            <a href="{{ url('/page/privacy-policy') }}" target="_blank" class="font-medium text-brand-orange hover:underline">Privacy Policy</a> &amp;
                            <a href="{{ url('/page/return-refund-policy') }}" target="_blank" class="font-medium text-brand-orange hover:underline">Refund and Return Policy</a>.
                        </span>
                    </label>

                    <button type="submit" class="w-full rounded-md bg-brand-orange py-3.5 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-brand-navy">
                        Place Order
                    </button>
                </div>
            </div>
        </form>

        {{-- Coupon apply/remove live outside the checkout form; the controls above
             point at them with the HTML form="" attribute so nothing gets nested. --}}
        <form id="coupon-form" action="{{ route('checkout.coupon.apply') }}" method="POST" class="hidden">@csrf</form>
        <form id="coupon-remove-form" action="{{ route('checkout.coupon.remove') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </section>

    {{-- GA4 checkout data for app.js (add_shipping_info / add_payment_info + client_id). --}}
    @php
        $gaCheckout = [
            'currency' => \App\Support\Ga4::currency(),
            'value' => round((float) $subtotal, 2),
            'items' => \App\Support\Ga4::cartItems($items),
            // Used by app.js to recalculate the delivery cost / total when the district changes.
            'discount' => round((float) $discount, 2),
            'freeShipping' => $shippingFee <= 0,
        ];
    @endphp
    <script type="application/json" id="ga-checkout-data">{!! json_encode($gaCheckout, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) !!}</script>

    {{-- district → thanas, so the thana picker refills when the district changes --}}
    <script type="application/json" id="thana-map">{!! json_encode($thanaMap, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) !!}</script>
</x-layout>
