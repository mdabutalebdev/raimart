<x-admin-layout title="Product Reviews - Raimart Admin">
    <div>
        <h1 class="font-serif text-2xl font-bold">Product Reviews</h1>
        <p class="mt-0.5 text-sm text-brand-navy/60">Approve reviews before they appear on the product page.</p>
    </div>

    @php
        $chips = [
            ['label' => 'All', 'count' => $counts['all'], 'params' => []],
            ['label' => 'Pending', 'count' => $counts['pending'], 'params' => ['status' => 'pending']],
            ['label' => 'Approved', 'count' => $counts['approved'], 'params' => ['status' => 'approved']],
        ];
    @endphp
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($chips as $chip)
            @php $active = count($chip['params']) ? request('status') === $chip['params']['status'] : ! request('status'); @endphp
            <a href="{{ route('admin.product-reviews.index', $chip['params']) }}"
                class="flex items-center gap-2 rounded border px-3 py-1.5 text-xs font-semibold transition
                    {{ $active ? 'border-brand-orange bg-brand-orange text-white' : 'border-brand-navy/15 text-brand-navy hover:border-brand-orange hover:text-brand-orange' }}">
                {{ $chip['label'] }}
                <span class="rounded px-1.5 py-0.5 text-[10px] {{ $active ? 'bg-white/20' : 'bg-brand-navy/5' }}">{{ $chip['count'] }}</span>
            </a>
        @endforeach
    </div>

    <form action="{{ route('admin.product-reviews.index') }}" class="mt-4 flex flex-wrap gap-2 rounded border border-brand-navy/10 bg-white p-3">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviewer or text..."
            class="w-full max-w-xs rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
        <select name="rating" class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
            <option value="">Any rating</option>
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
            @endfor
        </select>
        <button type="submit" class="rounded bg-brand-navy px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-orange">Filter</button>
    </form>

    <div class="mt-4 space-y-3">
        @forelse ($reviews as $review)
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-brand-navy">{{ $review->name }}</p>
                            <span class="text-xs text-brand-orange">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fa-{{ $i < $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                            </span>
                        </div>

                        <p class="mt-0.5 text-xs text-brand-navy/50">
                            on
                            @if ($review->product)
                                <a href="{{ route('products.show', $review->product) }}" target="_blank" class="font-medium text-brand-orange hover:underline">{{ $review->product->name }}</a>
                            @else
                                <span class="italic">deleted product</span>
                            @endif
                            &middot; {{ $review->created_at?->diffForHumans() }}
                            @if ($review->phone) &middot; {{ $review->phone }} @endif
                        </p>

                        <p class="mt-2 text-sm leading-relaxed text-brand-navy/80">{{ $review->text }}</p>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        @if (! $review->is_approved)
                            <form action="{{ route('admin.product-reviews.approve', $review) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-green-700">Approve</button>
                            </form>
                        @else
                            <form action="{{ route('admin.product-reviews.reject', $review) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="rounded border border-brand-navy/15 px-4 py-2 text-xs font-semibold text-brand-navy transition hover:border-amber-400 hover:text-amber-600">Hide</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.product-reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded border border-red-200 px-4 py-2 text-xs font-semibold text-red-500 transition hover:bg-red-50">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded border border-brand-navy/10 bg-white px-4 py-10 text-center text-brand-navy/40">No reviews found.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</x-admin-layout>
