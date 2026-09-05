<x-admin-layout title="SMTP Settings - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">SMTP Settings</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Configure your email server to send notifications.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
        {{-- Mail server configuration --}}
        <form action="{{ route('admin.smtp.update') }}" method="POST" class="rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
            @csrf
            @method('PUT')

            <div class="border-b border-brand-navy/10 px-6 py-4">
                <h2 class="font-serif text-lg font-bold">Mail Server Configuration</h2>
            </div>

            <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="smtp.gmail.com"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                    <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}" placeholder="587 or 465"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" placeholder="you@yourdomain.com"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Password / App Password</label>
                    <input type="password" name="smtp_password" value="" placeholder="{{ ($settings['smtp_password'] ?? '') ? '•••••••••• (leave blank to keep)' : '' }}"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Encryption Protocol</label>
                    @php $enc = old('smtp_encryption', $settings['smtp_encryption'] ?? 'tls'); @endphp
                    <select name="smtp_encryption" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                        <option value="tls" @selected($enc === 'tls')>TLS</option>
                        <option value="ssl" @selected($enc === 'ssl')>SSL</option>
                        <option value="none" @selected($enc === 'none')>None</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-5 border-t border-brand-navy/10 px-6 py-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sender Email (From)</label>
                    <input type="email" name="smtp_from_email" value="{{ old('smtp_from_email', $settings['smtp_from_email'] ?? '') }}" placeholder="noreply@raimartbd.com"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sender Name (From Name)</label>
                    <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings['smtp_from_name'] ?? '') }}" placeholder="Raimart"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end border-t border-brand-navy/10 px-6 py-4">
                <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Settings</button>
            </div>
        </form>

        {{-- Test --}}
        <div class="h-fit rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
            <div class="flex items-center gap-3 border-b border-brand-navy/10 px-6 py-4">
                <i class="fa-solid fa-paper-plane text-brand-orange"></i>
                <h2 class="font-serif text-lg font-bold">Test Configuration</h2>
            </div>

            <form action="{{ route('admin.smtp.test') }}" method="POST" class="space-y-4 px-6 py-6">
                @csrf
                <p class="text-sm text-brand-navy/60">Send a test email to verify that your SMTP credentials work.</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Recipient Email Address</label>
                    <input type="email" name="test_email" required placeholder="you@example.com"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>

                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-orange">
                    <i class="fa-solid fa-flask"></i> Send Test Email
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
