<footer class="bg-brand-navy mt-auto pb-16 md:pb-0">
    <div class="mx-auto grid gb-container grid-cols-1 gap-8 py-10 md:grid-cols-3">
        <div>
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-white.png') }}" alt="Raimart" class="h-8">
            </a>
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/70">
                {{ $siteSettings['site_description'] ?? 'Your trusted online shopping destination. Quality products across every category, delivered fast and priced fair.' }}
            </p>

            {{-- Social icons: fully driven by admin → Social Links (only ticked ones appear) --}}
            @if ($socialLinks->isNotEmpty())
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach ($socialLinks as $social)
                        <a href="{{ $social->url }}" target="_blank" rel="noopener" title="{{ $social->label }}" aria-label="{{ $social->label }}"
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-brand-orange hover:text-white">
                            <i class="fa-brands fa-{{ $social->icon }} text-sm"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h3 class="font-semibold text-white text-lg">Utilities</h3>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                {{-- Content pages come from admin → Pages (any page ticked "show in footer") --}}
                @foreach ($footerPages as $footerPage)
                    <li><a href="{{ route('pages.show', $footerPage) }}" class="hover:text-brand-orange transition">{{ $footerPage->title }}</a></li>
                @endforeach
                <li><a href="{{ route('about') }}" class="hover:text-brand-orange transition">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-brand-orange transition">Contact Us</a></li>
            </ul>
        </div>

        <div>
            <h3 class="font-semibold text-white text-lg">Connect with Raimart</h3>
            <ul class="mt-4 space-y-3 text-sm text-white/70">
                <li class="flex items-center gap-3"><i class="fa-solid fa-location-dot w-4 text-white"></i> {{ $siteSettings['contact_address'] ?? 'Dhaka, Bangladesh' }}</li>
                <li class="flex items-center gap-3"><i class="fa-solid fa-phone w-4 text-white"></i> {{ $siteSettings['contact_phone'] ?? '+880 1XXX-XXXXXX' }}</li>
                <li class="flex items-center gap-3"><i class="fa-solid fa-envelope w-4 text-white"></i> {{ $siteSettings['contact_email'] ?? 'support@raimart.com' }}</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex gb-container flex-col items-center justify-between gap-4 py-6 md:flex-row text-sm text-white/60">
            <p>
                &copy; {{ date('Y') }} Raimart. All rights reserved.
            </p>
            <p>
                Developed by <a href="https://hosterex.com/" target="_blank" rel="noopener noreferrer" class="font-medium text-white hover:text-white/80 transition">Hosterex</a>
            </p>
        </div>
    </div>
</footer>
