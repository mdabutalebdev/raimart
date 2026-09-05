<x-layout title="Contact Us - Raimart">
    <div class="gb-container py-12 md:py-16">
        
        {{-- Header Section --}}
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 rounded-full bg-brand-orange/10 px-4 py-1.5 text-sm font-semibold text-brand-orange mb-4">
                <span class="h-2 w-2 rounded-full bg-brand-orange"></span> Get In Touch
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                {{ $settings['contact_hero_title'] ?? 'Let\'s Start a Conversation' }}
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed">
                {{ $settings['contact_hero_subtitle'] ?? 'Have questions about our products, support, or anything else? We\'d love to hear from you.' }}
            </p>
        </div>

        @if (session('status'))
            <div class="mb-10 max-w-4xl mx-auto rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-700 flex items-center gap-4 shadow-sm">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <p class="font-medium text-base">{{ session('status') }}</p>
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-[1fr_400px] items-start">
            
            {{-- Form Section --}}
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.05)] border border-gray-100 p-8 sm:p-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Send a Message</h2>

                @if ($errors->any())
                    <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-600 flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
                        <ul class="list-inside list-disc space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm focus:border-brand-orange focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-orange/10 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+880 1234 567 890"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm focus:border-brand-orange focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-orange/10 transition-all">
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. john@example.com"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm focus:border-brand-orange focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-orange/10 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="How can we help?"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm focus:border-brand-orange focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-orange/10 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Your Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required placeholder="Write your message here..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm focus:border-brand-orange focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-orange/10 transition-all resize-y">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-brand-orange px-10 py-4 text-base font-bold text-white shadow-[0_4px_14px_0_rgb(255,100,51,0.39)] transition-all hover:translate-y-[-2px] hover:shadow-[0_6px_20px_rgba(255,100,51,0.23)] hover:bg-orange-600">
                        <span>Send Message</span>
                        <i class="fa-solid fa-paper-plane text-sm ml-1"></i>
                    </button>
                </form>
            </div>

            {{-- Contact Information --}}
            <div class="space-y-8">
                <div class="bg-gray-900 rounded-3xl p-8 sm:p-10 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-orange/30 rounded-full blur-[80px]"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-brand-orange/20 rounded-full blur-[80px]"></div>
                    
                    <h3 class="text-2xl font-bold mb-10 relative z-10">Contact Information</h3>
                    
                    @php
                        $details = array_filter([
                            ['icon' => 'location-dot', 'label' => 'Our Office', 'value' => $settings['contact_address'] ?? '123 E-commerce St, Business Avenue', 'href' => null],
                            ['icon' => 'phone-volume', 'label' => 'Call Us', 'value' => $settings['contact_phone'] ?? '+880 1234 567890', 'href' => 'tel:'.($settings['contact_phone'] ?? '')],
                            ['icon' => 'envelope-open-text', 'label' => 'Email Us', 'value' => $settings['contact_email'] ?? 'support@raimart.com', 'href' => 'mailto:'.($settings['contact_email'] ?? '')],
                            ['icon' => 'clock', 'label' => 'Working Hours', 'value' => $settings['contact_hours'] ?? 'Sat - Thu: 09:00 AM - 08:00 PM', 'href' => null],
                        ], fn ($d) => filled($d['value']));
                    @endphp

                    <div class="space-y-8 relative z-10">
                        @foreach ($details as $detail)
                            <div class="flex items-start gap-5 group">
                                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-brand-orange transition-all duration-300 group-hover:bg-brand-orange group-hover:text-white group-hover:scale-110">
                                    <i class="fa-solid fa-{{ $detail['icon'] }} text-xl"></i>
                                </div>
                                <div class="pt-1">
                                    <p class="text-sm font-medium text-gray-400 mb-1">{{ $detail['label'] }}</p>
                                    @if ($detail['href'])
                                        <a href="{{ $detail['href'] }}" class="text-base font-semibold text-white transition hover:text-brand-orange block leading-tight">{{ $detail['value'] }}</a>
                                    @else
                                        <p class="text-base font-semibold text-white leading-snug">{{ $detail['value'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Map --}}
                @if (filled($settings['contact_map_embed'] ?? null))
                    <div class="overflow-hidden rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] bg-white p-2">
                        <div class="rounded-2xl overflow-hidden [&_iframe]:block [&_iframe]:h-[280px] [&_iframe]:w-full filter grayscale hover:grayscale-0 transition-all duration-500">
                            {!! $settings['contact_map_embed'] !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layout>
