<x-layout title="About Us - Raimart">
    {{-- Main Content --}}
    <div class="relative gb-container py-16 md:py-24 bg-[#FFF9F5] overflow-hidden">
        {{-- Faint dot/grid background pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 24px 24px;"></div>

        <div class="relative z-10 grid items-center gap-12 lg:grid-cols-2">
            
            {{-- Image Left --}}
            <div class="relative">
                <div class="absolute -inset-4 md:-inset-6 bg-[#FFEFE5] rounded-full blur-2xl opacity-60"></div>
                <div class="relative overflow-hidden rounded-2xl shadow-sm bg-white">
                    @if (filled($settings['about_image'] ?? null))
                        <img src="{{ image_url($settings['about_image'], 'About Raimart') }}" alt="About Raimart" class="w-full h-auto object-cover rounded-2xl">
                    @else
                        {{-- Fallback image if none in settings, matching the vibe of screenshot --}}
                        <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?q=80&w=1000&auto=format&fit=crop" alt="Placeholder" class="w-full h-auto object-cover rounded-2xl">
                    @endif
                </div>
            </div>

            {{-- Content Right --}}
            <div>
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 rounded-full bg-white border border-brand-orange/20 px-3 py-1 text-xs font-semibold text-brand-orange mb-6 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-orange"></span> About US
                </div>
                
                {{-- Heading --}}
                <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-bold text-[#111827] leading-[1.1] mb-6">
                    {{ $settings['about_title'] ?? 'We Build Digital Experiences That Grow Businesses' }}
                </h1>
                
                {{-- Paragraph --}}
                <div class="text-[#4B5563] text-[17px] leading-relaxed mb-10">
                    @if (filled($settings['about_content'] ?? null))
                        @foreach (preg_split('/\R{2,}/', trim($settings['about_content'])) as $paragraph)
                            <p class="mb-4 last:mb-0">{{ $paragraph }}</p>
                        @endforeach
                    @else
                        <p>At Raimart, we help customers find the best quality products for their daily needs. Our team combines creativity, technology, and strategy to deliver a seamless shopping experience that drives satisfaction and success.</p>
                    @endif
                </div>
                
                {{-- Stats Grid --}}
                <div class="grid grid-cols-3 gap-4 sm:gap-6">
                    @if ($counters->isNotEmpty())
                        @foreach ($counters->take(3) as $counter)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center hover:shadow-md transition-shadow">
                                <p class="text-2xl sm:text-3xl font-bold text-[#111827] mb-1">
                                    {{ $counter['value'] }}{{ $counter['suffix'] ?? '' }}
                                </p>
                                <p class="text-[11px] sm:text-xs font-medium text-gray-500">{{ $counter['label'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center hover:shadow-md transition-shadow">
                            <p class="text-2xl sm:text-3xl font-bold text-[#111827] mb-1">5+</p>
                            <p class="text-[11px] sm:text-xs font-medium text-gray-500">Years Experience</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center hover:shadow-md transition-shadow">
                            <p class="text-2xl sm:text-3xl font-bold text-[#111827] mb-1">500+</p>
                            <p class="text-[11px] sm:text-xs font-medium text-gray-500">Projects Completed</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center hover:shadow-md transition-shadow">
                            <p class="text-2xl sm:text-3xl font-bold text-[#111827] mb-1">98%</p>
                            <p class="text-[11px] sm:text-xs font-medium text-gray-500">Client Satisfaction</p>
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</x-layout>
