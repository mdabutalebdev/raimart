<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\TelegramNotifier;
use Illuminate\Http\Request;

class TelegramSettingController extends Controller
{
    public function edit(TelegramNotifier $telegram)
    {
        $settings = SiteSetting::getAll();
        $configured = $telegram->isConfigured();

        return view('admin.telegram.edit', compact('settings', 'configured'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'telegram_bot_token' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
        ]);

        SiteSetting::set('telegram_bot_token', $data['telegram_bot_token'] ?? null);
        SiteSetting::set('telegram_chat_id', $data['telegram_chat_id'] ?? null);

        return redirect()->route('admin.telegram.edit')->with('status', 'Telegram settings saved.');
    }

    /** Sends a test message so the admin can confirm the bot works. */
    public function test(TelegramNotifier $telegram)
    {
        [$ok, $message] = $telegram->send("✅ <b>Raimart test message</b>\nYour Telegram notifications are working.");

        return redirect()->route('admin.telegram.edit')
            ->with($ok ? 'status' : 'error', $ok ? 'Test message sent — check your Telegram.' : 'Failed: '.$message);
    }
}
