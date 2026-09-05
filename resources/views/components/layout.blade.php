@props([
    'title' => 'Raimart - Everything you need in one place',
    'description' => null,
    'image' => null,
])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    {{-- SEO + social sharing --}}
    @php
        $metaDescription = $description
            ?: (\App\Models\SiteSetting::get('site_description')
                ?: 'Your trusted online shopping destination. Quality products across every category, delivered fast and priced fair.');
        $metaImage = $image ? image_url($image) : asset('images/logo.png');
    @endphp
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160) }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Raimart">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 200) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $metaImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 200) }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    {{-- Data layer + Google Tag Manager (must load before other scripts) --}}
    <script>window.dataLayer = window.dataLayer || [];</script>
    @stack('datalayer')
    @if (config('services.ga4.gtm_id'))
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ config('services.ga4.gtm_id') }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    <!-- Favicon -->
    @include('partials.favicons')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#FBF9F5] font-sans text-brand-navy antialiased">
    @if (config('services.ga4.gtm_id'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.ga4.gtm_id') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    @include('partials.header')

    {{-- No bottom padding here: the clearance for the fixed mobile nav lives on the
         footer instead, otherwise it shows up as a blank gap above the footer. --}}
    <main>
        {{ $slot }}
    </main>

    @include('partials.footer')
    @include('partials.mobile-nav')
    @include('partials.category-drawer')
    @include('partials.cart-drawer')
    @if(!request()->routeIs('products.show'))
        @include('partials.quick-contact')
    @endif
    
    @livewireScripts
</body>
</html>
