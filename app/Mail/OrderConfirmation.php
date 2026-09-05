<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed — '.$this->order->order_number.' | Raimart',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: ['settings' => SiteSetting::getAll()],
        );
    }
}
