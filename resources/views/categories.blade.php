<x-layout title="Shop by Category - Raimart">
    <section class="mx-auto gb-container py-10">
        <div class="text-center">
            <h1 class="font-serif text-3xl font-bold text-brand-navy">Shop by Category</h1>
            <p class="mx-auto mt-2 max-w-xl text-sm text-brand-navy/60">
                Discover our curated collection of products organized by category.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="group block overflow-hidden rounded-xl border border-brand-navy/10 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="relative aspect-square overflow-hidden bg-brand-bg">
                        <img src="{{ image_url($category->image, urlencode($category->name)) }}" alt="{{ $category->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        <span class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white text-xs font-semibold text-brand-navy shadow-sm">
                            {{ $category->products_count }}
                        </span>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-medium text-brand-navy">{{ $category->name }}</p>
                        <span class="mt-1 inline-flex items-center gap-1 text-sm font-medium text-brand-orange">
                            Explore
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</x-layout>
