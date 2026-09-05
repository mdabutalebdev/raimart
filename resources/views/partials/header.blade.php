<header class="sticky top-0 z-50 bg-white shadow-sm">
    {{-- Top bar --}}
    <div class="relative mx-auto flex gb-container items-center gap-3 py-3 md:gap-6 md:py-4">
        {{-- Mobile hamburger --}}
        <button type="button" data-drawer-open class="flex h-9 w-9 shrink-0 items-center justify-center text-brand-navy md:hidden" aria-label="Menu">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        {{-- Logo: centered on mobile, left-aligned on desktop --}}
        <a href="{{ url('/') }}" wire:navigate class="absolute left-1/2 flex shrink-0 -translate-x-1/2 items-center md:static md:left-auto md:translate-x-0">
            <img src="{{ asset('images/logo.png') }}" alt="Raimart" class="h-6 md:h-8">
        </a>

        <livewire:global-search />

        <div class="ml-auto flex items-center gap-4 text-brand-navy md:gap-5">
            <a href="{{ route('wishlist.index') }}" wire:navigate class="relative hidden sm:block" aria-label="Wishlist">
                <i class="fa-regular fa-heart text-xl"></i>
                <span data-wishlist-count class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-brand-orange text-[10px] font-semibold text-white {{ $wishlistCount > 0 ? '' : 'hidden' }}">{{ $wishlistCount }}</span>
            </a>
            <button type="button" data-cart-open class="relative" aria-label="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-current">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L21.65 7H5.12" />
                </svg>
                <span data-cart-count class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-brand-orange text-[10px] font-semibold text-white {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
            </button>
            @auth
                <div class="relative group hidden md:block" id="profile-menu">
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-bg overflow-hidden border border-gray-200" aria-label="Profile">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <i class="fa-regular fa-user"></i>
                        @endif
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-100 z-50">
                        <div class="p-3 border-b border-gray-100">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('user.dashboard') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-orange">Dashboard</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-orange">Admin Panel</a>
                            @endif
                        </div>
                        <div class="py-1 border-t border-gray-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" wire:navigate class="hidden md:flex h-9 w-9 items-center justify-center rounded-full bg-brand-bg hover:bg-brand-orange hover:text-white transition" aria-label="Account">
                    <i class="fa-regular fa-user"></i>
                </a>
            @endauth
        </div>
    </div>

    {{-- Desktop nav bar --}}
    <div class="hidden border-t border-white/10 bg-brand-navy text-white md:block">
        <div class="mx-auto flex gb-container items-center justify-between py-3 text-sm">
            <nav class="flex items-center gap-8">
                <div class="relative" id="categories-menu" data-shop-url="{{ route('shop') }}">
                    <button type="button" id="categories-toggle" class="flex items-center gap-2 font-medium transition hover:text-brand-orange" aria-expanded="false" aria-controls="categories-panel">
                        <i class="fa-solid fa-bars"></i>
                        Categories
                    </button>

                    <div id="categories-panel" class="invisible absolute left-0 top-full z-40 mt-3 flex w-[36rem] max-w-[90vw] overflow-hidden rounded-xl bg-white text-brand-navy opacity-0 shadow-2xl transition">
                        <div class="max-h-[22rem] w-56 shrink-0 divide-y divide-brand-navy/5 overflow-y-auto border-r border-brand-navy/10 py-2">
                            @foreach ($navCategories as $navCategory)
                                <a
                                    href="{{ route('shop', ['category' => $navCategory['slug']]) }}"
                                    wire:navigate
                                    class="category-item flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm transition hover:bg-brand-bg hover:text-brand-orange"
                                    data-name="{{ $navCategory['name'] }}"
                                    data-subcategories="{{ json_encode($navCategory['subcategories']) }}"
                                >
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-bg text-xs font-semibold text-brand-navy/60">
                                        @if (!empty($navCategory['image']))
                                            <img src="{{ image_url($navCategory['image'], urlencode($navCategory['name'])) }}" alt="{{ $navCategory['name'] }}" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($navCategory['name'], 0, 1) }}
                                        @endif
                                    </span>
                                    <span>{{ $navCategory['name'] }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div id="subcategories-panel" class="flex-1 p-6 text-sm text-brand-navy/40">
                            Select a category to view subcategories
                        </div>
                    </div>
                </div>

                <a href="{{ route('shop') }}" wire:navigate class="font-medium hover:text-brand-orange">Shop</a>
                <a href="{{ route('offers') }}" wire:navigate class="font-medium hover:text-brand-orange">Offers</a>
            </nav>

            <a href="{{ route('track') }}" wire:navigate class="hidden items-center gap-2 font-medium hover:text-brand-orange sm:flex">
                <i class="fa-solid fa-truck-fast"></i>
                Track your order
            </a>
        </div>
    </div>
</header>
