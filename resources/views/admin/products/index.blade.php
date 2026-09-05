<x-admin-layout title="Products - Raimart Admin">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl font-bold">Products</h1>
            <p class="mt-0.5 text-sm text-brand-navy/60">{{ number_format($products->total()) }} products found</p>
        </div>
        <div class="flex shrink-0 gap-2">
            <a href="{{ route('admin.products.export', request()->query()) }}"
                class="flex items-center gap-2 rounded border border-brand-navy/15 px-4 py-2 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 rounded bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    {{-- Quick stock chips --}}
    @php
        $chips = [
            ['label' => 'All', 'count' => $counts['all'], 'params' => []],
            ['label' => 'Low stock (≤'.$lowStock.')', 'count' => $counts['low'], 'params' => ['stock' => 'low']],
            ['label' => 'Out of stock', 'count' => $counts['out'], 'params' => ['stock' => 'out']],
            ['label' => 'Inactive', 'count' => $counts['inactive'], 'params' => ['status' => 'inactive']],
        ];
    @endphp
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($chips as $chip)
            @php
                $active = collect($chip['params'])->every(fn ($v, $k) => request($k) === $v)
                    && (count($chip['params']) || (! request('stock') && ! request('status')));
            @endphp
            <a href="{{ route('admin.products.index', $chip['params']) }}"
                class="flex items-center gap-2 rounded border px-3 py-1.5 text-xs font-semibold transition
                    {{ $active ? 'border-brand-orange bg-brand-orange text-white' : 'border-brand-navy/15 text-brand-navy hover:border-brand-orange hover:text-brand-orange' }}">
                {{ $chip['label'] }}
                <span class="rounded px-1.5 py-0.5 text-[10px] {{ $active ? 'bg-white/20' : 'bg-brand-navy/5' }}">{{ $chip['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form action="{{ route('admin.products.index') }}" class="mt-4 rounded border border-brand-navy/10 bg-white p-3">
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..."
                class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">

            <select name="category" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">All categories</option>
                @foreach ($categoryGroups as $group)
                    <option value="{{ $group->id }}" @selected(request('category') == $group->id)>{{ $group->name }}</option>
                    @foreach ($group->children as $child)
                        <option value="{{ $child->id }}" @selected(request('category') == $child->id)>&nbsp;&nbsp;— {{ $child->name }}</option>
                    @endforeach
                @endforeach
            </select>

            <select name="brand" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">All brands</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>

            <select name="stock" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any stock</option>
                <option value="in" @selected(request('stock') === 'in')>In stock (>{{ $lowStock }})</option>
                <option value="low" @selected(request('stock') === 'low')>Low stock (≤{{ $lowStock }})</option>
                <option value="out" @selected(request('stock') === 'out')>Out of stock</option>
            </select>

            <select name="status" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <select name="flag" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Any flag</option>
                <option value="featured" @selected(request('flag') === 'featured')>Featured</option>
                <option value="best_seller" @selected(request('flag') === 'best_seller')>Best Seller</option>
                <option value="new_arrival" @selected(request('flag') === 'new_arrival')>New Arrival</option>
            </select>

            <div class="flex gap-2">
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ৳" min="0"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ৳" min="0"
                    class="w-full rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <select name="sort" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                <option value="">Newest first</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                <option value="name" @selected(request('sort') === 'name')>Name (A–Z)</option>
                <option value="price_low" @selected(request('sort') === 'price_low')>Price: low → high</option>
                <option value="price_high" @selected(request('sort') === 'price_high')>Price: high → low</option>
                <option value="stock_low" @selected(request('sort') === 'stock_low')>Stock: low → high</option>
                <option value="stock_high" @selected(request('sort') === 'stock_high')>Stock: high → low</option>
            </select>
        </div>

        <div class="mt-2 flex items-center gap-2">
            <button type="submit" class="rounded bg-brand-navy px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-orange">Apply filters</button>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-brand-navy/50 hover:text-brand-orange">Reset</a>

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
                    <th class="px-4 py-2.5 font-semibold">Product</th>
                    <th class="px-4 py-2.5 font-semibold">SKU</th>
                    <th class="px-4 py-2.5 font-semibold">Category</th>
                    <th class="px-4 py-2.5 font-semibold">Price</th>
                    <th class="px-4 py-2.5 font-semibold">Stock</th>
                    <th class="px-4 py-2.5 font-semibold">Status</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($products as $product)
                    <tr class="transition hover:bg-brand-navy/[0.02]">
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ image_url($product->main_image) }}" class="h-9 w-9 shrink-0 rounded border border-brand-navy/10 object-cover">
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-brand-navy">{{ $product->name }}</p>
                                    <div class="mt-0.5 flex flex-wrap gap-1">
                                        @if ($product->is_featured) <span class="rounded bg-brand-orange/10 px-1.5 text-[10px] font-semibold text-brand-orange">Featured</span> @endif
                                        @if ($product->is_best_seller) <span class="rounded bg-brand-navy/5 px-1.5 text-[10px] font-semibold text-brand-navy/60">Best Seller</span> @endif
                                        @if ($product->is_new_arrival) <span class="rounded bg-green-100 px-1.5 text-[10px] font-semibold text-green-700">New</span> @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 font-mono text-xs text-brand-navy/60">{{ $product->sku ?: '—' }}</td>
                        <td class="px-4 py-2.5 text-brand-navy/70">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-2.5">
                            <span class="font-semibold">৳{{ number_format($product->price) }}</span>
                            @if ($product->old_price)
                                <span class="ml-1 text-xs text-brand-navy/40 line-through">৳{{ number_format($product->old_price) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5">
                            @if ($product->stock <= 0)
                                <span class="rounded bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Out of stock</span>
                            @elseif ($product->stock <= $lowStock)
                                <span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">{{ $product->stock }} left</span>
                            @else
                                <span class="font-medium text-brand-navy/70">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="rounded px-2 py-0.5 text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2.5 text-right">
                            <a href="{{ route('products.show', $product) }}" target="_blank" class="text-brand-navy/50 hover:text-brand-orange" title="View on site">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="ml-3 font-medium text-brand-orange hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-brand-navy/40">No products match these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</x-admin-layout>
