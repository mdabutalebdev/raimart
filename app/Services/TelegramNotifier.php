<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Support\MailConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends instant Telegram notifications (new orders, contact messages) using the
 * bot token + chat id the admin saves under Admin → Telegram Settings.
 */
class TelegramNotifier
{
    public function isConfigured(): bool
    {
        return filled(SiteSetting::get('telegram_bot_token')) && filled(SiteSetting::get('telegram_chat_id'));
    }

    /** Low-level send. Returns [ok, message] so the admin "test" button can report back. */
    public function send(string $text): array
    {
        $token = SiteSetting::get('telegram_bot_token');
        $chatId = SiteSetting::get('telegram_chat_id');

        if (! $token || ! $chatId) {
            return [false, 'Bot Token and Chat ID must be saved first.'];
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful() && ($response->json('ok') === true)) {
                return [true, 'Message sent successfully.'];
            }

            return [false, $response->json('description') ?? 'Telegram rejected the request.'];
        } catch (\Throwable $e) {
            Log::warning('Telegram send failed', ['error' => $e->getMessage()]);

            return [false, $e->getMessage()];
        }
    }

    /**
     * Everything that should happen the moment an order lands: Telegram alert to
     * the shop, confirmation email to the customer. Never throws — a failed
     * notification must not break checkout.
     */
    public function orderPlaced(Order $order): void
    {
        $this->newOrder($order);
        $this->emailCustomer($order);
    }

    /** Confirmation email, only when we have an address and SMTP is set up. */
    private function emailCustomer(Order $order): void
    {
        if (blank($order->email) || ! MailConfig::isConfigured()) {
            return;
        }

        try {
            MailConfig::apply();
            Mail::to($order->email)->send(new OrderConfirmation($order));
        } catch (\Throwable $e) {
            Log::warning('Order confirmation email failed', ['order' => $order->order_number, 'error' => $e->getMessage()]);
        }
    }

    /** Fired when a new order is placed. Never throws — a failed notify must not break checkout. */
    public function newOrder(Order $order): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $order->loadMissing('items');

        $lines = [
            '🛒 <b>New Order</b> — '.e($order->order_number),
            '',
            '<b>Customer:</b> '.e($order->name),
            '<b>Phone:</b> '.e($order->phone),
        ];

        if ($order->email) {
            $lines[] = '<b>Email:</b> '.e($order->email);
        }

        $lines[] = '<b>Address:</b> '.e($order->address.', '.$order->city);
        $lines[] = '';
        $lines[] = '<b>Items:</b>';

        foreach ($order->items as $item) {
            $lines[] = '• '.e($item->product_name).' × '.$item->quantity.' — ৳'.number_format((float) $item->subtotal);
        }

        $lines[] = '';
        $lines[] = '<b>Subtotal:</b> ৳'.number_format((float) $order->subtotal);
        $lines[] = '<b>Shipping:</b> ৳'.number_format((float) $order->shipping_fee);
        $lines[] = '<b>Total:</b> ৳'.number_format((float) $order->total);
        $lines[] = '<b>Payment:</b> '.strtoupper($order->payment_method).' ('.$order->payment_status.')';

        if ($order->notes) {
            $lines[] = '';
            $lines[] = '<b>Note:</b> '.e($order->notes);
        }

        $this->send(implode("\n", $lines));
    }

    /** Fired when someone submits the contact form. */
    public function contactMessage(ContactMessage $message): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $lines = [
            '✉️ <b>New Contact Message</b>',
            '',
            '<b>Name:</b> '.e($message->name),
        ];

        if ($message->email) {
            $lines[] = '<b>Email:</b> '.e($message->email);
        }
        if ($message->phone) {
            $lines[] = '<b>Phone:</b> '.e($message->phone);
        }
        if ($message->subject) {
            $lines[] = '<b>Subject:</b> '.e($message->subject);
        }

        $lines[] = '';
        $lines[] = e($message->message);

        $this->send(implode("\n", $lines));
    }
}
