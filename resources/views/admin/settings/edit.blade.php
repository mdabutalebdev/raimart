<x-admin-layout title="Settings - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Site Settings</h1>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="mt-6 max-w-2xl space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium">Footer About Text</label>
            <textarea name="site_description" rows="3" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium"><i class="fa-brands fa-facebook-f w-4 text-brand-orange"></i> Facebook URL</label>
                <input type="text" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium"><i class="fa-brands fa-instagram w-4 text-brand-orange"></i> Instagram URL</label>
                <input type="text" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium"><i class="fa-brands fa-x-twitter w-4 text-brand-orange"></i> X / Twitter URL</label>
                <input type="text" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium"><i class="fa-brands fa-apple w-4 text-brand-orange"></i> App Store URL</label>
                <input type="text" name="app_store_url" value="{{ old('app_store_url', $settings['app_store_url'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium"><i class="fa-brands fa-google-play w-4 text-brand-orange"></i> Google Play URL</label>
                <input type="text" name="google_play_url" value="{{ old('google_play_url', $settings['google_play_url'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="text-sm font-medium">Address</label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium">Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Settings</button>
    </form>
</x-admin-layout>
