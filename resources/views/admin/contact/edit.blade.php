<x-admin-layout title="Contact Page - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Contact Page</h1>
    <p class="mt-1 text-sm text-brand-navy/60">These details power the Contact page, the footer and the whole site.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.contact-page.update') }}" method="POST" class="mt-6 max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Page Header</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hero Title</label>
                    <input type="text" name="contact_hero_title" value="{{ old('contact_hero_title', $settings['contact_hero_title'] ?? 'Contact Us') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hero Subtitle</label>
                    <input type="text" name="contact_hero_subtitle" value="{{ old('contact_hero_subtitle', $settings['contact_hero_subtitle'] ?? '') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            </div>
        </div>

        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Contact Details</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone 2 (optional)</label>
                    <input type="text" name="contact_phone_2" value="{{ old('contact_phone_2', $settings['contact_phone_2'] ?? '') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Business Hours</label>
                    <input type="text" name="contact_hours" value="{{ old('contact_hours', $settings['contact_hours'] ?? '') }}" placeholder="Sat–Thu, 10:00 AM – 8:00 PM"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            </div>
        </div>

        <div class="space-y-3 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold">Google Map</h2>
            <label class="block text-sm font-medium text-gray-700">Map Embed Code</label>
            <textarea name="contact_map_embed" rows="4" placeholder="Paste the &lt;iframe&gt; embed code from Google Maps"
                class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 font-mono text-xs focus:border-brand-orange focus:outline-none">{{ old('contact_map_embed', $settings['contact_map_embed'] ?? '') }}</textarea>
            <p class="text-xs text-gray-500">Google Maps → Share → Embed a map → copy the HTML. Leave empty to hide the map.</p>
        </div>

        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Contact Page</button>
    </form>
</x-admin-layout>
