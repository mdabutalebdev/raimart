<x-admin-layout title="Quick Contact - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Quick Contact Widget</h1>
    <p class="mt-1 text-sm text-brand-navy/60">The floating bubble on your website that opens Messenger, WhatsApp and a call button.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.quick-contact.update') }}" method="POST" class="mt-6 max-w-2xl space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @method('PUT')

        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-brand-navy/10 p-4">
            <input type="hidden" name="quick_contact_enabled" value="0">
            <input type="checkbox" name="quick_contact_enabled" value="1"
                @checked(old('quick_contact_enabled', $settings['quick_contact_enabled'] ?? '') == '1')
                class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
            <span class="text-sm font-medium">Show the floating widget on the website</span>
        </label>

        <div>
            <label class="block text-sm font-medium text-gray-700"><i class="fa-brands fa-facebook-messenger w-4 text-[#0084FF]"></i> Messenger Link</label>
            <input type="text" name="quick_messenger_url" value="{{ old('quick_messenger_url', $settings['quick_messenger_url'] ?? '') }}"
                placeholder="https://m.me/yourpage"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700"><i class="fa-brands fa-whatsapp w-4 text-[#25D366]"></i> WhatsApp Number</label>
            <input type="text" name="quick_whatsapp_number" value="{{ old('quick_whatsapp_number', $settings['quick_whatsapp_number'] ?? '') }}"
                placeholder="8801XXXXXXXXX (country code, no + or spaces)"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700"><i class="fa-solid fa-phone w-4 text-brand-orange"></i> Phone Number (call button)</label>
            <input type="text" name="quick_phone_number" value="{{ old('quick_phone_number', $settings['quick_phone_number'] ?? '') }}"
                placeholder="+8801895288808"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>

        <p class="text-xs text-gray-500">Leave a field empty to hide that button from the widget.</p>

        <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Widget</button>
    </form>
</x-admin-layout>
