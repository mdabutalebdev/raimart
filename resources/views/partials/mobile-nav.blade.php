<nav x-data="{ searchOpen: false }" class="fixed inset-x-0 bottom-0 z-50 bg-white shadow-[0_-2px_10px_-3px_rgba(0,0,0,0.08)] md:hidden">
    <div class="grid grid-cols-5 items-center">
        <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium {{ request()->routeIs('home') ? 'text-brand-orange' : 'text-brand-navy/70' }}">
            <i class="fa-solid fa-house text-[18px]"></i>
            Home
        </a>

        <button type="button" data-drawer-open class="flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium text-brand-navy/70">
            <i class="fa-solid fa-bars text-[18px]"></i>
            Menu
        </button>

        <button type="button" data-cart-open class="relative flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium text-white">
            <span class="-mt-6 flex h-12 w-12 items-center justify-center rounded-full bg-brand-orange shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-current">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L21.65 7H5.12" />
                </svg>
                <span data-cart-count class="absolute right-1 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-white text-[10px] font-bold text-brand-orange border border-brand-orange {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
            </span>
            <span class="text-brand-navy/70">Cart</span>
        </button>

        <button type="button" @click="searchOpen = true" class="flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium text-brand-navy/70">
            <i class="fa-solid fa-magnifying-glass text-[18px]"></i>
            Search
        </button>

        @auth
            <a href="{{ route('user.dashboard') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium {{ request()->routeIs('user.*') ? 'text-brand-orange' : 'text-brand-navy/70' }}">
                <i class="fa-solid fa-user text-[18px]"></i>
                Account
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate class="flex flex-col items-center gap-0.5 py-2 text-[10px] font-medium {{ request()->routeIs('login') ? 'text-brand-orange' : 'text-brand-navy/70' }}">
                <i class="fa-solid fa-user text-[18px]"></i>
                Account
            </a>
        @endauth
    </div>

    {{-- Mobile Search Modal --}}
    <div x-show="searchOpen" x-transition.opacity style="display: none;" class="fixed inset-0 z-[100] bg-black/50" @click.self="searchOpen = false">
        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0" class="absolute inset-x-0 top-0 bg-white p-4 shadow-lg">
            <form action="{{ route('shop') }}" method="GET" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for products..." autofocus class="w-full rounded-full border border-brand-navy/15 bg-brand-bg py-2.5 pl-5 pr-12 text-sm text-brand-navy placeholder:text-brand-navy/40 focus:border-brand-orange focus:outline-none">
                    <button type="submit" class="absolute right-1.5 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-brand-orange text-white">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>
                <button type="button" @click="searchOpen = false" class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-bg text-brand-navy">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</nav>
