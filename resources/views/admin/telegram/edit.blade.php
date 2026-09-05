<x-admin-layout title="Telegram Settings - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Telegram Settings</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Configure your Telegram bot to receive instant order notifications.</p>

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
        {{-- Bot configuration --}}
        <form action="{{ route('admin.telegram.update') }}" method="POST" class="rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3 border-b border-brand-navy/10 px-6 py-4">
                <i class="fa-brands fa-telegram text-xl text-[#229ED9]"></i>
                <h2 class="font-serif text-lg font-bold">Bot Configuration</h2>
            </div>

            <div class="space-y-5 px-6 py-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bot Token</label>
                    <input type="text" name="telegram_bot_token" value="{{ old('telegram_bot_token', $settings['telegram_bot_token'] ?? '') }}"
                        placeholder="123456789:ABCdefGhIJKlmNoPQRstuVWXyz"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">Create a new bot via <b>&#64;BotFather</b> on Telegram to get this token.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Chat ID</label>
                    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $settings['telegram_chat_id'] ?? '') }}"
                        placeholder="-1001234567890"
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">The chat, group or channel the bot posts to. Use <b>&#64;userinfobot</b> to find your ID.</p>
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

            <div class="space-y-4 px-6 py-6">
                <p class="text-sm text-brand-navy/60">Send a test message to your configured Telegram chat.</p>

                @if ($configured)
                    <form action="{{ route('admin.telegram.test') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-orange">
                            <i class="fa-solid fa-flask"></i> Send Test Message
                        </button>
                    </form>
                @else
                    <div class="flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <span>Please save your Bot Token and Chat ID first.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
