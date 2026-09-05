<x-layout :title="($activeCategory->name ?? 'Shop').' - Raimart'">
    @php
        // Normalise the query into array form so each chip can drop just its own value.
        $base = collect(request()->except(['page', 'category', 'brand']))
            ->merge([
                'categories' => $selectedCategories->values()->all(),
                'brands' => $selectedBrands->values()->all(),
                'flags' => $selectedFlags->values()->all(),
            ]);

        $cleanUrl = fn ($arr) => route('shop', collect($arr)
            ->map(fn ($v) => is_array($v) ? array_values($v) : $v)
            ->filter(fn ($v) => is_array($v) ? count($v) : ($v !== null && $v !== '' && $v !== '0'))
            ->all());

        $priceActive = (request()->filled('min_price') || request()->filled('max_price'))
            && ($minPrice > $priceFloor || $maxPrice < $priceCeil);

        $flagLabels = ['best_seller' => 'Best Selling', 'featured' => 'Featured', 'new_arrival' => 'New Arrival'];

        // Build removable chips.
        $chips = [];

        foreach ($selectedCategories as $slug) {
            $name = optional($categories->firstWhere('slug', $slug))->name ?? \App\Models\Category::where('slug', $slug)->value('name') ?? $slug;
            $chips[] = ['label' => $name, 'url' => $cleanUrl(array_replace($base->toArray(), ['categories' => $selectedCategories->reject(fn ($s) => $s === $slug)->values()->all()]))];
        }
        foreach ($selectedBrands as $slug) {
            $name = optional($brands->firstWhere('slug', $slug))->name ?? $slug;
            $chips[] = ['label' => $name, 'url' => $cleanUrl(array_replace($base->toArray(), ['brands' => $selectedBrands->reject(fn ($s) => $s === $slug)->values()->all()]))];
        }
        foreach ($selectedFlags as $flag) {
            $chips[] = ['label' => $flagLabels[$flag] ?? $flag, 'url' => $cleanUrl(array_replace($base->toArray(), ['flags' => $selectedFlags->reject(fn ($f) => $f === $flag)->values()->all()]))];
        }
        if ($priceActive) {
            $chips[] = ['label' => '৳'.number_format($minPrice).' – ৳'.number_format($maxPrice), 'url' => $cleanUrl(collect($base->toArray())->except(['min_price', 'max_price'])->all())];
        }
        if (request('in_stock')) {
            $chips[] = ['label' => 'In Stock', 'url' => $cleanUrl(collect($base->toArray())->except(['in_stock'])->all())];
        }
        if (request('on_sale')) {
            $chips[] = ['label' => 'On Sale', 'url' => $cleanUrl(collect($base->toArray())->except(['on_sale'])->all())];
        }
        if (request('search')) {
            $chips[] = ['label' => 'Search: "'.request('search').'"', 'url' => $cleanUrl(collect($base->toArray())->except(['search'])->all())];
        }

        $sortLabels = [
            '' => 'Newest',
            'popular' => 'Most Reviewed',
            'price_low' => 'Price: Low to High',
            'price_high' => 'Price: High to Low',
        ];
    @endphp

    @if ($products->isNotEmpty())
        @php $listName = $activeCategory->name ?? 'Shop All Products'; @endphp
        {{-- GA4: view_item_list --}}
        <x-ga-event event="view_item_list" :ecommerce="[
            'item_list_id' => 'shop',
            'item_list_name' => $listName,
            'items' => \App\Support\Ga4::itemsFromProducts($products->items(), ['item_list_id' => 'shop', 'item_list_name' => $listName]),
        ]" />
    @endif

    <section x-data="{ filtersOpen: false }" class="mx-auto gb-container py-5 lg:py-8">
        <h1 class="font-serif text-xl font-bold sm:text-2xl">{{ $activeCategory->name ?? 'Shop All Products' }}</h1>

        <div class="mt-4 grid gap-8 lg:mt-6 lg:grid-cols-[290px_1fr]">
            {{-- Filter column: static on desktop, slide-over drawer on mobile --}}
            <div :class="filtersOpen ? 'block' : 'hidden lg:block'" class="fixed inset-0 z-50 lg:static lg:z-auto">
                <div @click="filtersOpen = false" x-show="filtersOpen" x-transition.opacity class="absolute inset-0 bg-brand-navy/40 backdrop-blur-sm lg:hidden" style="display: none;"></div>

                <div class="absolute inset-y-0 left-0 flex w-[86%] max-w-sm flex-col overflow-y-auto bg-brand-bg p-4 shadow-2xl lg:static lg:w-auto lg:max-w-none lg:overflow-visible lg:bg-transparent lg:p-0 lg:shadow-none">
                    <div class="mb-4 flex items-center justify-between lg:hidden">
                        <span class="font-serif text-lg font-bold">Filters</span>
                        <button type="button" @click="filtersOpen = false" class="flex h-8 w-8 items-center justify-center rounded-full text-brand-navy/60 hover:bg-white">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    @include('partials.shop-filters')
                </div>
            </div>

            <div>
                {{-- Toolbar --}}
                {{-- One row on every size: Filters on the left, Sort on the right --}}
                <div class="flex items-center justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" @click="filtersOpen = true"
                            class="flex shrink-0 items-center gap-2 rounded-md border border-brand-navy/15 px-4 py-2 text-sm font-medium text-brand-navy transition hover:border-brand-orange hover:text-brand-orange lg:hidden">
                            <i class="fa-solid fa-sliders text-xs"></i>
                            Filters
                            @if (count($chips))
                                <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-orange px-1 text-[10px] font-semibold text-white">{{ count($chips) }}</span>
                            @endif
                        </button>
                        <p class="hidden truncate text-sm text-brand-navy/50 sm:block">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</p>
                    </div>

                    <form action="{{ route('shop') }}" method="GET" class="flex shrink-0 items-center gap-2">
                        @foreach (request()->except(['sort', 'page']) as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label class="hidden text-sm text-brand-navy/50 sm:block">Sort by</label>
                        <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                            @foreach ($sortLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('sort', '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Active filter chips --}}
                @if (count($chips))
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @foreach ($chips as $chip)
                            <a href="{{ $chip['url'] }}"
                                class="inline-flex items-center gap-1.5 rounded-md bg-brand-orange/10 px-3 py-1.5 text-xs font-medium text-brand-orange transition hover:bg-brand-orange hover:text-white">
                                {{ $chip['label'] }}
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Product grid — fixed 200px card slots, cards don't stretch when few --}}
                <div class="mt-5 product-grid lg:mt-6">
                    @forelse ($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div class="col-span-full py-16 text-center">
                            <i class="fa-solid fa-box-open text-4xl text-brand-navy/15"></i>
                            <p class="mt-4 text-brand-navy/50">No products match your filters.</p>
                            <a href="{{ route('shop') }}" class="mt-4 inline-block rounded-md bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Clear filters</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">{{ $products->links() }}</div>
            </div>
        </div>
    </section>
</x-layout>
