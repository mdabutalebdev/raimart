<x-layout>
    {{-- Hero --}}
    <section class="gb-container pt-5 md:pt-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-[5fr_1fr]">
            <div class="relative overflow-hidden rounded-2xl" data-hero-slider>
                @foreach ($heroBanners as $index => $hero)
                    @php $hasText = filled($hero->title) || filled($hero->subtitle); @endphp
                    <div class="hero-slide relative aspect-[12/5] {{ $index === 0 ? '' : 'hidden' }}" data-ga-promotion="{{ json_encode(\App\Support\Ga4::promotion($hero)) }}">
                        <img src="{{ image_url($hero->image, urlencode($hero->title ?? 'Raimart')) }}" alt="{{ $hero->title }}" class="absolute inset-0 h-full w-full object-cover">

                        @if ($hasText)
                            <div class="absolute inset-0 bg-gradient-to-r from-brand-navy/60 via-brand-navy/20 to-transparent"></div>
                        @endif

                        @if ($hasText || filled($hero->button_text))
                            <div class="absolute inset-0 flex flex-col justify-center px-5 text-white sm:px-8 lg:px-14">
                                @if (filled($hero->title))
                                    <h1 class="max-w-md font-serif text-xl font-bold leading-tight sm:text-3xl lg:text-5xl">
                                        {{ $hero->title }}
                                    </h1>
                                @endif
                                @if (filled($hero->subtitle))
                                    <p class="mt-1 max-w-sm text-xs text-white/80 sm:mt-4 sm:text-base">
                                        {{ $hero->subtitle }}
                                    </p>
                                @endif

                                @if (filled($hero->button_text))
                                    <a href="{{ $hero->link ?: route('shop') }}" class="mt-3 inline-flex w-fit items-center gap-2 rounded-md bg-brand-orange px-4 py-2 text-xs font-semibold text-white transition hover:bg-white hover:text-brand-navy sm:mt-8 sm:px-6 sm:py-3 sm:text-sm">
                                        {{ $hero->button_text }}
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($heroBanners->count() > 1)
                    <button type="button" data-hero-prev class="absolute left-4 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand-navy shadow-md transition hover:bg-brand-orange hover:text-white" aria-label="Previous slide">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" data-hero-next class="absolute right-4 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand-navy shadow-md transition hover:bg-brand-orange hover:text-white" aria-label="Next slide">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                @endif
            </div>

            <div class="hidden gap-4 lg:flex lg:flex-col">
                @foreach ($promoBanners->take(2) as $promo)
                    <a href="{{ $promo->link ?? '#' }}" class="relative aspect-square overflow-hidden rounded-2xl" data-ga-promotion="{{ json_encode(\App\Support\Ga4::promotion($promo)) }}">
                        <img src="{{ image_url($promo->image, urlencode($promo->title ?? 'Promo')) }}" alt="{{ $promo->title }}" class="absolute inset-0 h-full w-full object-cover">
                        @if ($promo->badge_text)
                            <span class="absolute left-3 top-3 rounded-full bg-brand-orange px-3 py-1 text-xs font-semibold text-white">{{ $promo->badge_text }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Browse category: two rows, 8 cards in view, slides two columns at a time --}}
    <section class="gb-container py-4 md:py-6">
        <div x-data="carousel()" @mouseenter="clearInterval(timer)" @mouseleave="start()">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Browse Category</h2>
                <div class="flex shrink-0 gap-2">
                    <button type="button" @click="move(-1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll left">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" @click="move(1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll right">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div x-ref="slider" @scroll.debounce.100ms="sync()"
                class="mt-4 flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2 md:mt-5 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->slug]) }}"
                        class="group w-[92px] shrink-0 snap-start text-center sm:w-[110px] lg:w-[124px]">
                        {{-- Plain circle: the image fills it edge to edge, no tint, no padding --}}
                        <div class="mx-auto aspect-square w-full overflow-hidden rounded-full">
                            <img src="{{ image_url($category->image, urlencode($category->name)) }}" alt="{{ $category->name }}"
                                class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                loading="lazy">
                        </div>

                        <p class="mt-2.5 text-[11px] font-semibold uppercase leading-tight tracking-wide text-brand-navy transition-colors group-hover:text-brand-orange sm:text-xs">
                            {{ $category->name }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured products (forpink-style: category tabs filter a grid) --}}
    <section class="gb-container py-4 md:py-6">
        @php $featuredCategories = $featuredProducts->pluck('category')->filter()->unique('id')->values(); @endphp
        <div x-data="tabFilter()">
            <div class="text-center">
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Our Featured Products</h2>
            </div>

            {{-- Category tabs --}}
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <button type="button" @click="tab = 'all'"
                    :class="tab === 'all' ? 'border-brand-orange bg-brand-orange text-white' : 'border-brand-navy/20 bg-transparent text-brand-navy hover:border-brand-orange hover:text-brand-orange'"
                    class="rounded-lg border px-5 py-2 text-sm font-semibold transition">All</button>
                @foreach ($featuredCategories as $cat)
                    <button type="button" @click="tab = '{{ $cat->id }}'"
                        :class="tab === '{{ $cat->id }}' ? 'border-brand-orange bg-brand-orange text-white' : 'border-brand-navy/20 bg-transparent text-brand-navy hover:border-brand-orange hover:text-brand-orange'"
                        class="rounded-lg border px-5 py-2 text-sm font-semibold transition">{{ $cat->name }}</button>
                @endforeach
            </div>

            {{-- Product grid (filtered by the active tab) --}}
            <div class="product-row mt-4 md:mt-5">
                @foreach ($featuredProducts as $product)
                    <div x-show="tab === 'all' || tab === '{{ $product->category_id }}'">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('shop', ['flags' => ['featured']]) }}" wire:navigate
                    class="inline-flex items-center gap-2 rounded-md border border-brand-orange px-6 py-2.5 text-sm font-semibold text-brand-orange transition hover:bg-brand-orange hover:text-white">
                    See More Products
                </a>
            </div>
        </div>
    </section>

    {{-- Best selling --}}
    <section class="py-4 md:py-6">
        <div class="gb-container" x-data="carousel()" @mouseenter="clearInterval(timer)" @mouseleave="start()">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Best Selling Product</h2>
                <div class="flex shrink-0 gap-2">
                    <button type="button" @click="move(-1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll left">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" @click="move(1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll right">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div x-ref="slider" @scroll.debounce.100ms="sync()" class="product-row-track mt-5 snap-x snap-mandatory overflow-x-auto scroll-smooth pb-2 md:mt-8 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @forelse ($bestSelling as $product)
                    <div class="product-slide snap-start">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <p class="py-6 text-center text-brand-navy/40">No best sellers yet.</p>
                @endforelse
            </div>

            <div class="mt-4 flex items-center justify-center gap-2" x-show="pages > 1" style="display:none;">
                <template x-for="i in Math.min(Math.max(pages | 0, 1), 50)" :key="i">
                    <button type="button" @click="goTo(i - 1)" :class="active === (i - 1) ? 'w-6 bg-brand-orange' : 'w-2.5 bg-brand-navy/20 hover:bg-brand-navy/40'" class="h-2.5 rounded-md transition-all duration-300"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- New arrivals --}}
    <section class="py-4 md:py-6">
        <div class="gb-container" x-data="carousel()" @mouseenter="clearInterval(timer)" @mouseleave="start()">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">New Arrival Product</h2>
                <div class="flex shrink-0 gap-2">
                    <button type="button" @click="move(-1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll left">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" @click="move(1)" class="flex h-9 w-9 items-center justify-center rounded-[5px] border border-brand-orange text-brand-orange transition hover:bg-brand-orange hover:text-white" aria-label="Scroll right">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div x-ref="slider" @scroll.debounce.100ms="sync()" class="product-row-track mt-5 snap-x snap-mandatory overflow-x-auto scroll-smooth pb-2 md:mt-8 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @forelse ($newArrivals as $product)
                    <div class="product-slide snap-start">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <p class="py-6 text-center text-brand-navy/40">No new arrivals yet.</p>
                @endforelse
            </div>

            <div class="mt-4 flex items-center justify-center gap-2" x-show="pages > 1" style="display:none;">
                <template x-for="i in Math.min(Math.max(pages | 0, 1), 50)" :key="i">
                    <button type="button" @click="goTo(i - 1)" :class="active === (i - 1) ? 'w-6 bg-brand-orange' : 'w-2.5 bg-brand-navy/20 hover:bg-brand-navy/40'" class="h-2.5 rounded-md transition-all duration-300"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- Just for you — 15 shown, then 5 more per "Show More" click --}}
    <section class="gb-container py-4 md:py-6">
        <div x-data="paginator({{ $justForYou->count() }}, 10)" x-ref="top">
            <h2 class="text-center text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Just for you</h2>

            {{-- 10 products per page, paged client-side (no reload) --}}
            <div class="product-row mt-4 md:mt-5">
                @foreach ($justForYou as $index => $product)
                    <div x-show="shows({{ $index }})" @if ($index >= 10) style="display:none;" @endif>
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            @if ($justForYou->count() > 10)
                <nav class="mt-6 flex items-center justify-center gap-1.5" aria-label="Just for you pages">
                    <button type="button" @click="prev()" :disabled="page === 1"
                        class="flex h-9 w-9 items-center justify-center rounded-md text-brand-navy/60 transition hover:text-brand-orange disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Previous page">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>

                    <template x-for="(n, i) in numbers" :key="i">
                        <button type="button" @click="go(n)" :disabled="n === '…'"
                            :class="n === page
                                ? 'bg-brand-orange text-white'
                                : (n === '…' ? 'cursor-default text-brand-navy/40' : 'text-brand-navy hover:bg-brand-orange/10 hover:text-brand-orange')"
                            class="flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition"
                            x-text="n"></button>
                    </template>

                    <button type="button" @click="next()" :disabled="page === pages"
                        class="flex h-9 w-9 items-center justify-center rounded-md text-brand-navy/60 transition hover:text-brand-orange disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Next page">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </nav>
            @endif
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-4 md:py-6">
        <div class="gb-container">
            <h2 class="text-center text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">Customer Reviews</h2>
            <p class="mx-auto mt-2 max-w-xl text-center text-sm text-brand-navy/60">
                Real stories, genuine smiles. Discover why thousands trust Raimart for their everyday shopping.
            </p>

            <div x-data="{
                    activeSlide: 0,
                    totalSlides: {{ count($testimonials) }},
                    scrollNext() { 
                        if ($refs.slider.scrollLeft + $refs.slider.clientWidth >= $refs.slider.scrollWidth - 10) {
                            $refs.slider.scrollTo({left: 0, behavior: 'smooth'});
                        } else {
                            $refs.slider.scrollBy({left: $refs.slider.clientWidth * 0.8, behavior: 'smooth'});
                        }
                    },
                    scrollTo(index) {
                        const slide = $refs.slider.children[index];
                        if (slide) {
                            $refs.slider.scrollTo({left: slide.offsetLeft - $refs.slider.offsetLeft, behavior: 'smooth'});
                        }
                    },
                    updateActiveSlide() {
                        const scrollLeft = $refs.slider.scrollLeft;
                        let minDistance = Infinity;
                        let activeIndex = 0;
                        Array.from($refs.slider.children).forEach((child, index) => {
                            const distance = Math.abs(child.offsetLeft - $refs.slider.offsetLeft - scrollLeft);
                            if (distance < minDistance) {
                                minDistance = distance;
                                activeIndex = index;
                            }
                        });
                        this.activeSlide = activeIndex;
                    },
                    init() {
                        setInterval(() => { this.scrollNext() }, 4000);
                        // Initial active slide setup just in case
                        this.$nextTick(() => { this.updateActiveSlide(); });
                    }
                }" class="mt-4 md:mt-6">
                
                <div x-ref="slider" @scroll.passive="updateActiveSlide" class="flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth pb-4 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    @foreach ($testimonials as $testimonial)
                        <div class="w-[85%] shrink-0 snap-start sm:w-[45%] lg:w-[23%] rounded-xl bg-brand-bg p-6">
                        <div class="flex items-center gap-3">
                            @if ($testimonial->avatar)
                                <img src="{{ image_url($testimonial->avatar) }}" class="h-10 w-10 rounded-full object-cover" alt="{{ $testimonial->name ?? $testimonial->phone }}">
                            @else
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-navy font-serif text-sm font-semibold text-white">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-brand-navy">{{ $testimonial->name ?? $testimonial->phone ?? 'Customer' }}</p>
                                @if($testimonial->location)
                                    <p class="text-xs text-brand-navy/50">{{ $testimonial->location }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 text-xs text-brand-orange">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-{{ $i < $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>

                        <p class="mt-3 text-sm leading-relaxed text-brand-navy/70">{{ $testimonial->text }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination Dots -->
                @if(count($testimonials) > 0)
                <div class="mt-4 flex justify-center gap-2">
                    <template x-for="(slide, index) in Array.from({length: totalSlides})">
                        <button type="button" 
                                @click="scrollTo(index)" 
                                :class="activeSlide === index ? 'w-6 bg-brand-orange' : 'w-2 bg-brand-orange/30 hover:bg-brand-orange/50'"
                                class="h-2 rounded-md transition-all duration-300"
                                aria-label="Go to slide">
                        </button>
                    </template>
                </div>
                @endif
            </div>

        </div>
    </section>
</x-layout>
