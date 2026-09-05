<x-admin-layout title="About Page - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">About Page</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Everything on the public About Us page is edited here.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-4xl space-y-6">
        @csrf
        @method('PUT')

        {{-- Page header --}}
        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Page Header</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hero Title</label>
                    <input type="text" name="about_hero_title" value="{{ old('about_hero_title', $settings['about_hero_title'] ?? 'About Us') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hero Subtitle</label>
                    <input type="text" name="about_hero_subtitle" value="{{ old('about_hero_subtitle', $settings['about_hero_subtitle'] ?? '') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Main about block: text left, image right --}}
        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">About Section <span class="text-sm font-normal text-brand-navy/50">(text left, image right)</span></h2>

            <div>
                <label class="block text-sm font-medium text-gray-700">Section Title</label>
                <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title'] ?? 'Who We Are') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="about_content" rows="6"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('about_content', $settings['about_content'] ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Section Image</label>
                @if (! empty($settings['about_image']))
                    <img src="{{ image_url($settings['about_image'], 'About') }}" alt="About" class="mt-2 h-40 rounded-lg object-cover">
                @endif
                <input type="file" name="about_image" accept="image/*"
                    class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-orange/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-orange hover:file:bg-brand-orange/20">
            </div>
        </div>

        {{-- Mission / Vision --}}
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
                <h2 class="font-serif text-lg font-bold">Our Mission</h2>
                <input type="text" name="about_mission_title" value="{{ old('about_mission_title', $settings['about_mission_title'] ?? 'Our Mission') }}"
                    class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                <textarea name="about_mission_content" rows="5" placeholder="Describe your mission…"
                    class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('about_mission_content', $settings['about_mission_content'] ?? '') }}</textarea>
            </div>

            <div class="space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
                <h2 class="font-serif text-lg font-bold">Our Vision</h2>
                <input type="text" name="about_vision_title" value="{{ old('about_vision_title', $settings['about_vision_title'] ?? 'Our Vision') }}"
                    class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                <textarea name="about_vision_content" rows="5" placeholder="Describe your vision…"
                    class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('about_vision_content', $settings['about_vision_content'] ?? '') }}</textarea>
            </div>
        </div>

        {{-- Counters --}}
        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-serif text-lg font-bold">Counters / Stats</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Counters Section Title</label>
                <input type="text" name="about_counters_title" value="{{ old('about_counters_title', $settings['about_counters_title'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <div class="space-y-3">
                @php $rows = old('counters', $counters ?: [['label' => '', 'value' => '', 'suffix' => '']]); @endphp
                @for ($i = 0; $i < 6; $i++)
                    @php $row = $rows[$i] ?? ['label' => '', 'value' => '', 'suffix' => '']; @endphp
                    <div class="grid gap-3 rounded-lg border border-brand-navy/10 p-3 sm:grid-cols-[1fr_140px_120px]">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Label</label>
                            <input type="text" name="counters[{{ $i }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="Happy Customers"
                                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Value</label>
                            <input type="text" name="counters[{{ $i }}][value]" value="{{ $row['value'] ?? '' }}" placeholder="500"
                                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Suffix</label>
                            <input type="text" name="counters[{{ $i }}][suffix]" value="{{ $row['suffix'] ?? '' }}" placeholder="+ or %"
                                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
                        </div>
                    </div>
                @endfor
                <p class="text-xs text-gray-500">Leave a row empty to skip it. Up to 6 counters.</p>
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save About Page</button>
    </form>
</x-admin-layout>
