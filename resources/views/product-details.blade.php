<x-layout
    :title="($product->meta_title ?: $product->name).' - Raimart'"
    :description="$product->meta_description ?: $product->short_description"
    :image="$product->main_image"
>
    @php
        $phone = preg_replace('/\D+/', '', \App\Models\SiteSetting::get('contact_phone', ''));
        $waMsg = urlencode("Assalamu Alaikum, I would like to order: {$product->name} (৳" . number_format($product->price) . "). Link: " . route('products.show', $product));

        // Build a de-duplicated media list (main image first, then gallery)
        $media = collect();
        if ($product->main_image) {
            $media->push(['url' => image_url($product->main_image, urlencode($product->name)), 'type' => is_video($product->main_image) ? 'video' : 'image']);
        }
        foreach ($product->images as $img) {
            $u = image_url($img->image);
            if (! $media->contains(fn ($m) => $m['url'] === $u)) {
                $media->push(['url' => $u, 'type' => is_video($img->image) ? 'video' : 'image']);
            }
        }
        $media = $media->values()->all();
    @endphp

    {{-- GA4: view_item --}}
    <x-ga-event event="view_item" :ecommerce="[
        'currency' => \App\Support\Ga4::currency(),
        'value' => round((float) $product->price, 2),
        'items' => [\App\Support\Ga4::item($product)],
    ]" />

    <section class="gb-container pt-6 pb-24 md:py-6">
        <nav class="text-sm text-brand-navy/50">
            <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-orange">Home</a>
            <span class="mx-1">/</span>
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" wire:navigate class="hover:text-brand-orange">{{ $product->category->name }}</a>
            <span class="mx-1">/</span>
            <span class="text-brand-navy">{{ $product->name }}</span>
        </nav>

        @if ($errors->any())
            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Product card --}}
        <div class="mt-4 rounded-2xl border border-brand-navy/10 bg-white p-4 shadow-sm sm:p-6 lg:p-8">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-start">
                <!-- Product Media Gallery -->
                <div x-data="{
                        items: {{ Illuminate\Support\Js::from($media) }},
                        idx: 0,
                        isZoomed: false,
                        get current() { return this.items[this.idx] || { url: '', type: 'image' } },
                        go(i) { this.idx = (i + this.items.length) % this.items.length },
                    }" class="w-full">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-start">
                        <!-- Thumbnails: horizontal below on mobile, vertical left on desktop -->
                        <template x-if="items.length > 1">
                            <div class="flex gap-2.5 overflow-x-auto pb-1 scrollbar-hide sm:w-[68px] sm:flex-col sm:overflow-visible sm:pb-0">
                                <template x-for="(m, i) in items" :key="i">
                                    <button type="button" @click="idx = i" class="relative h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border-2 bg-white transition-colors" :class="idx === i ? 'border-brand-orange' : 'border-brand-navy/15'">
                                        <template x-if="m.type === 'image'">
                                            <img :src="m.url" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="m.type === 'video'">
                                            <div class="relative h-full w-full">
                                                <video :src="m.url" class="h-full w-full object-cover" muted></video>
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                                    <i class="fa-solid fa-play text-white drop-shadow"></i>
                                                </div>
                                            </div>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <!-- Main image -->
                        <div class="group relative flex aspect-square flex-1 items-center justify-center overflow-hidden rounded-xl border border-brand-navy/10 bg-white">
                            <template x-if="current.type === 'image'">
                                <img :src="current.url" alt="{{ $product->name }}" class="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105">
                            </template>
                            <template x-if="current.type === 'video'">
                                <video :src="current.url" controls class="h-full w-full object-contain bg-black cursor-default"></video>
                            </template>

                            <!-- Zoom Button -->
                            <button @click="isZoomed = true" type="button" x-show="current.type === 'image'" class="absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white/80 text-brand-navy shadow-sm backdrop-blur-sm transition hover:bg-white hover:text-brand-orange opacity-0 group-hover:opacity-100 focus:opacity-100" aria-label="Zoom image">
                                <i class="fa-solid fa-expand"></i>
                            </button>

                            <!-- Prev / Next arrows -->
                            <template x-if="items.length > 1">
                                <div>
                                    <button type="button" @click="go(idx - 1)" class="absolute left-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand-navy shadow-md transition hover:bg-brand-orange hover:text-white" aria-label="Previous image">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </button>
                                    <button type="button" @click="go(idx + 1)" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand-navy shadow-md transition hover:bg-brand-orange hover:text-white" aria-label="Next image">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Zoom Modal -->
                    <div x-show="isZoomed" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 p-4 sm:p-8 backdrop-blur-md" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <button @click="isZoomed = false" type="button" class="absolute right-4 top-4 z-[110] flex h-10 w-10 items-center justify-center rounded-full bg-black/20 text-white sm:right-6 sm:top-6" aria-label="Close">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                        <div class="relative flex h-full w-full items-center justify-center" @click.outside="isZoomed = false">
                            <img :src="current.url" class="max-h-[90vh] max-w-full object-contain rounded-xl shadow-2xl" alt="Zoomed Product Image"
                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-brand-orange">{{ $product->category->name }}</p>
                    <h1 class="mt-2 font-serif text-3xl font-bold text-brand-navy">{{ $product->name }}</h1>

                    <div class="mt-3 flex items-center gap-4 text-sm text-brand-navy/60">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-brand-orange"></i>
                            <span>{{ number_format($product->rating, 1) }}</span>
                            <span>({{ $product->reviews_count }} reviews)</span>
                        </div>
                        
                        @if($product->brand)
                            <span class="h-4 w-px bg-brand-navy/20"></span>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-brand-navy">Brand:</span>
                                @if($product->brand->image)
                                    <img src="{{ image_url($product->brand->image, urlencode($product->brand->name)) }}" alt="{{ $product->brand->name }}" class="h-6 w-auto object-contain">
                                @else
                                    <span class="text-brand-navy/70">{{ $product->brand->name }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 flex items-baseline gap-3">
                        <span class="font-serif text-3xl font-bold text-brand-orange">৳{{ number_format($product->price) }}</span>
                        @if ($product->old_price)
                            <span class="text-lg text-brand-navy/40 line-through">৳{{ number_format($product->old_price) }}</span>
                            <span class="rounded bg-green-500 px-2 py-0.5 text-xs font-semibold text-white">Save {{ $product->discount_percent }}%</span>
                        @endif
                    </div>

                    @if($product->weight)
                        <div class="mt-3 inline-flex items-center gap-2 rounded-lg border border-brand-navy/10 bg-brand-bg/50 px-3 py-1.5 text-sm">
                            <span class="font-semibold text-brand-navy">Weight / Size:</span>
                            <span class="text-brand-navy/70">{{ $product->weight }}</span>
                        </div>
                    @endif

                    @if($product->short_description)
                        <p class="mt-4 leading-relaxed text-brand-navy/70 text-sm">{{ $product->short_description }}</p>
                    @endif

                    <p class="mt-3 text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                        @if($product->stock > 0)
                            <i class="fa-solid fa-circle-check mr-1"></i> In Stock ({{ $product->stock }} available)
                        @else
                            <i class="fa-solid fa-circle-xmark mr-1"></i> Out of Stock
                        @endif
                    </p>

                    <form action="{{ route('cart.add', $product) }}" method="POST" data-add-to-cart class="mt-5 border-t border-brand-navy/10 pt-5">
                        @csrf

                        <!-- Dynamic Attributes (Variants) -->
                        @if($product->attributes)
                            <div class="mb-4 space-y-3">
                                @foreach($product->attributes as $attribute)
                                    @php
                                        $optionsArray = array_values(array_filter(array_map('trim', explode(',', $attribute['values'] ?? ''))));
                                    @endphp
                                    @if(count($optionsArray) > 0)
                                        <div>
                                            @if(count($optionsArray) == 1)
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-sm font-semibold text-brand-navy">{{ $attribute['name'] }}:</span>
                                                    <span class="text-sm text-brand-navy font-medium bg-brand-navy/5 px-3 py-1 rounded-md">{{ $optionsArray[0] }}</span>
                                                </div>
                                                <input type="hidden" name="options[{{ $attribute['name'] }}]" value="{{ $optionsArray[0] }}">
                                            @else
                                                <label class="block text-sm font-semibold text-brand-navy mb-2">{{ $attribute['name'] }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <select name="options[{{ $attribute['name'] }}]" required class="w-full appearance-none rounded-lg border border-brand-navy/15 bg-white px-4 py-3 text-sm font-medium text-brand-navy focus:border-brand-orange focus:outline-none pr-10 hover:border-brand-orange/50 transition-colors cursor-pointer">
                                                        <option value="">Select {{ $attribute['name'] }}</option>
                                                        @foreach($optionsArray as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-brand-navy/50">
                                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Quantity + wishlist -->
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-brand-navy">Quantity:</span>
                            <div x-data="{ qty: 1, maxQty: {{ $product->stock }} }" class="flex h-11 items-center rounded-lg border border-brand-navy/15 bg-white">
                                <button type="button" @click="if(qty > 1) qty--" class="flex h-full w-10 items-center justify-center text-brand-navy">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" x-model="qty" min="1" :max="maxQty" class="w-12 text-center text-sm font-semibold focus:outline-none bg-transparent appearance-none p-0 border-none" readonly>
                                <button type="button" @click="if(qty < maxQty) qty++" class="flex h-full w-10 items-center justify-center text-brand-navy">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            @php $isWishlisted = app(\App\Services\Wishlist::class)->has($product->id); @endphp
                            <button
                                type="button"
                                data-wishlist-toggle
                                data-toggle-url="{{ route('wishlist.toggle', $product) }}"
                                aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
                                aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                                class="wishlist-btn ml-auto flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg border border-brand-navy/15 {{ $isWishlisted ? 'text-brand-orange' : 'text-brand-navy' }}"
                            >
                                <i class="fa-solid fa-heart text-lg" style="{{ $isWishlisted ? '' : 'display: none;' }}"></i>
                                <i class="fa-regular fa-heart text-lg" style="{{ $isWishlisted ? 'display: none;' : '' }}"></i>
                            </button>
                        </div>

                        <!-- Action buttons (2 x 2, ghorerbazar style) -->
                        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <button type="submit" @disabled($product->stock <= 0) class="flex h-12 items-center justify-center gap-2 rounded-md bg-brand-orange text-sm font-bold uppercase tracking-wide text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
                                <i class="fa-solid fa-bag-shopping text-xs"></i> Add to Cart
                            </button>

                            <button type="submit" name="redirect" value="checkout" @disabled($product->stock <= 0) class="flex h-12 items-center justify-center gap-2 rounded-md bg-brand-navy text-sm font-bold uppercase tracking-wide text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
                                Buy Now
                            </button>

                            <a href="https://wa.me/{{ $phone }}?text={{ $waMsg }}" target="_blank" rel="noopener" class="flex h-12 items-center justify-center gap-2 rounded-md bg-[#1DAA61] text-sm font-semibold text-white transition hover:opacity-90">
                                <i class="fa-brands fa-whatsapp text-lg"></i> Order On WhatsApp
                            </a>

                            <a href="tel:{{ $phone }}" class="flex h-12 items-center justify-center gap-2 rounded-md bg-blue-900 text-sm font-semibold text-white transition hover:opacity-90">
                                <i class="fa-solid fa-phone text-xs"></i> Call For Order
                            </a>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        {{-- Description / Reviews tabs --}}
        <div class="mt-8 overflow-hidden rounded-2xl border border-brand-navy/10 bg-white shadow-sm" x-data="{ tab: '{{ $errors->any() || session('status') ? 'reviews' : 'desc' }}' }">
            <div class="flex border-b border-brand-navy/10">
                <button type="button" @click="tab = 'desc'"
                    :class="tab === 'desc' ? 'border-brand-orange text-brand-navy' : 'border-transparent text-brand-navy/50 hover:text-brand-navy'"
                    class="border-b-2 px-5 py-4 text-sm font-semibold transition sm:px-8">
                    Description
                </button>
                <button type="button" @click="tab = 'reviews'"
                    :class="tab === 'reviews' ? 'border-brand-orange text-brand-navy' : 'border-transparent text-brand-navy/50 hover:text-brand-navy'"
                    class="border-b-2 px-5 py-4 text-sm font-semibold transition sm:px-8">
                    Customer Reviews ({{ $reviews->count() }})
                </button>
            </div>

            {{-- Description panel --}}
            <div x-show="tab === 'desc'" class="px-6 py-6 sm:p-8">
                @if($product->description)
                    <div class="prose prose-brand max-w-none text-brand-navy/80 prose-p:leading-relaxed prose-headings:font-serif prose-headings:text-brand-navy prose-img:rounded-xl">
                        {!! $product->description !!}
                    </div>
                @else
                    <p class="text-sm text-brand-navy/50">No description available for this product.</p>
                @endif
            </div>

            {{-- Reviews panel --}}
            <div x-show="tab === 'reviews'" style="display: none;" class="px-6 py-6 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid gap-8 md:grid-cols-[240px_1fr] md:items-start">
                    <div class="text-center md:text-left">
                        <p class="font-serif text-5xl font-bold text-brand-navy">{{ number_format($product->rating, 1) }}</p>
                        <div class="mt-2 text-brand-orange">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= round($product->rating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="mt-2 text-sm text-brand-navy/60">Based on {{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</p>
                    </div>

                    <div class="space-y-2">
                        @foreach ([5, 4, 3, 2, 1] as $star)
                            @php
                                $count = $ratingCounts[$star] ?? 0;
                                $percent = $reviews->count() > 0 ? round(($count / $reviews->count()) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-8 text-brand-navy/70">{{ $star }} <i class="fa-solid fa-star text-brand-orange text-xs"></i></span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-brand-navy/10">
                                    <div class="h-full rounded-full bg-brand-orange" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="w-10 text-right text-brand-navy/60">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Write a Review Form (always visible) --}}
                <div class="mt-8 rounded-xl border border-brand-navy/10 bg-brand-bg/40 p-5 sm:p-6">
                    <h3 class="font-serif text-lg font-bold text-brand-navy">Write a Review</h3>
                    <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-brand-navy mb-1">Name *</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-orange focus:ring-1 focus:ring-brand-orange sm:text-sm outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-navy mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-orange focus:ring-1 focus:ring-brand-orange sm:text-sm outline-none transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-navy mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-orange focus:ring-1 focus:ring-brand-orange sm:text-sm outline-none transition-colors">
                        </div>
                        <div x-data="{ rating: 5 }">
                            <label class="block text-sm font-medium text-brand-navy mb-2">Rating *</label>
                            <div class="flex gap-2 text-2xl cursor-pointer">
                                <template x-for="i in 5">
                                    <i class="fa-star text-brand-orange"
                                       :class="i <= rating ? 'fa-solid' : 'fa-regular'"
                                       @click="rating = i"></i>
                                </template>
                            </div>
                            <input type="hidden" name="rating" x-model="rating">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-navy mb-1">Your Review *</label>
                            <textarea name="text" rows="4" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-brand-orange focus:ring-1 focus:ring-brand-orange sm:text-sm outline-none transition-colors">{{ old('text') }}</textarea>
                        </div>
                        <div>
                            <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy transition">Submit Review</button>
                        </div>
                    </form>
                </div>

                {{-- Review List --}}
                @if ($reviews->count())
                    <div class="mt-8 border-t border-brand-navy/10 pt-2">
                        @foreach ($reviews as $review)
                            <div class="border-b border-brand-navy/5 py-5 last:border-b-0">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-brand-navy font-serif text-sm font-semibold text-white">
                                        {{ strtoupper(mb_substr($review->name, 0, 1)) }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <p class="font-semibold text-brand-navy">{{ $review->name }}</p>
                                            <span class="text-xs text-brand-navy/50">{{ $review->created_at->format('d M, Y') }}</span>
                                        </div>
                                        <div class="mt-1 text-brand-orange text-xs">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <p class="mt-2 text-sm leading-relaxed text-brand-navy/80">{{ $review->text }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if ($relatedProducts->count())
            <div class="mt-10">
                <h2 class="font-serif text-2xl font-bold">Related Products</h2>
                <div class="mt-6 product-grid">
                    @foreach ($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layout>
