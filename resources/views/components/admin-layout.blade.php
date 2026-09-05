@props(['title' => 'Admin - Raimart'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    
    <!-- Favicon -->
    @include('partials.favicons')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @livewireStyles
    
    <!-- Quill.js -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
</head>
<body class="bg-brand-bg font-sans text-brand-navy antialiased">
    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-50 hidden w-64 shrink-0 flex-col bg-brand-navy text-white lg:flex overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2 px-6 py-6 font-serif text-xl font-bold">
                <img src="{{ asset('images/logo-white.png') }}" alt="Raimart" class="h-8">
            </a>

            <nav class="flex-1 space-y-1 px-3">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'gauge', 'label' => 'Dashboard'],
                        ['route' => 'admin.categories.index', 'icon' => 'list', 'label' => 'Categories'],
                        ['route' => 'admin.brands.index', 'icon' => 'trademark', 'label' => 'Brands'],
                        ['route' => 'admin.products.index', 'icon' => 'box', 'label' => 'Products'],
                        ['route' => 'admin.top-selling.edit', 'icon' => 'fire', 'label' => 'Top Selling'],
                        ['route' => 'admin.banners.index', 'icon' => 'image', 'label' => 'Banners'],
                        ['route' => 'admin.promo-banners.index', 'icon' => 'rectangle-ad', 'label' => 'Promo Banner'],
                        ['route' => 'admin.testimonials.index', 'icon' => 'quote-left', 'label' => 'Reviews'],
                        ['route' => 'admin.orders.index', 'icon' => 'bag-shopping', 'label' => 'Orders'],
                        ['route' => 'admin.customers.index', 'icon' => 'users', 'label' => 'Customers'],
                        ['route' => 'admin.product-reviews.index', 'icon' => 'star', 'label' => 'Product Reviews'],
                        ['route' => 'admin.reports.index', 'icon' => 'chart-line', 'label' => 'Reports'],
                        ['route' => 'admin.coupons.index', 'icon' => 'ticket', 'label' => 'Coupons'],
                        ['route' => 'admin.delivery.edit', 'icon' => 'truck', 'label' => 'Delivery Charge'],
                        ['route' => 'admin.messages.index', 'icon' => 'envelope', 'label' => 'Messages'],
                        ['route' => 'admin.pages.index', 'icon' => 'file-lines', 'label' => 'Pages'],
                        ['route' => 'admin.about.edit', 'icon' => 'circle-info', 'label' => 'About Page'],
                        ['route' => 'admin.contact-page.edit', 'icon' => 'address-book', 'label' => 'Contact Page'],
                        ['route' => 'admin.social-links.edit', 'icon' => 'share-nodes', 'label' => 'Social Links'],
                        ['route' => 'admin.quick-contact.edit', 'icon' => 'headset', 'label' => 'Quick Contact'],
                        ['route' => 'admin.telegram.edit', 'icon' => 'paper-plane', 'label' => 'Telegram'],
                        ['route' => 'admin.smtp.edit', 'icon' => 'envelope-open-text', 'label' => 'SMTP'],
                        ['route' => 'admin.settings.edit', 'icon' => 'gear', 'label' => 'Settings'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs($item['route'].'*') || request()->routeIs(str($item['route'])->beforeLast('.').'.*') ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                    >
                        <i class="fa-solid fa-{{ $item['icon'] }} w-4"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="px-3 pb-6">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/60 hover:bg-white/5 hover:text-white">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                    Logout
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            <header class="sticky top-0 z-40 flex items-center justify-between border-b border-gray-200 bg-white/90 backdrop-blur-sm px-6 py-4 shadow-sm">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="lg:hidden flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Raimart" class="h-7">
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-brand-navy hover:bg-gray-200 hover:text-brand-orange transition-colors">
                        <i class="fa-solid fa-globe"></i>
                        View Website
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="lg:hidden">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-500">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-6 lg:p-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>
