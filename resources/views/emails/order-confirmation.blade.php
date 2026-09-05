<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order {{ $order->order_number }}</title>
</head>
{{-- Inline styles only: email clients strip <style> and external CSS. --}}
<body style="margin:0;padding:0;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#0f2144;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:6px;overflow:hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background:#0f2144;padding:24px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;letter-spacing:1px;">RAIMART</h1>
                        </td>
                    </tr>

                    {{-- Intro --}}
                    <tr>
                        <td style="padding:28px 24px 8px;">
                            <h2 style="margin:0 0 8px;font-size:20px;color:#0f2144;">Thank you for your order!</h2>
                            <p style="margin:0;font-size:14px;line-height:22px;color:#5b6472;">
                                Hi {{ $order->name }}, we have received your order
                                <strong style="color:#0f2144;">{{ $order->order_number }}</strong>
                                placed on {{ $order->created_at?->format('d M Y, h:i A') }}.
                                We will contact you shortly to confirm delivery.
                            </p>
                        </td>
                    </tr>

                    {{-- Items --}}
                    <tr>
                        <td style="padding:20px 24px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
                                <tr style="background:#f7f8fa;">
                                    <th align="left" style="padding:8px;color:#5b6472;font-size:12px;text-transform:uppercase;">Product</th>
                                    <th align="center" style="padding:8px;color:#5b6472;font-size:12px;text-transform:uppercase;">Qty</th>
                                    <th align="right" style="padding:8px;color:#5b6472;font-size:12px;text-transform:uppercase;">Total</th>
                                </tr>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td style="padding:10px 8px;border-bottom:1px solid #eceef1;">{{ $item->product_name }}</td>
                                        <td align="center" style="padding:10px 8px;border-bottom:1px solid #eceef1;">{{ $item->quantity }}</td>
                                        <td align="right" style="padding:10px 8px;border-bottom:1px solid #eceef1;">৳{{ number_format($item->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    {{-- Totals --}}
                    <tr>
                        <td style="padding:16px 24px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                <tr>
                                    <td style="padding:4px 8px;color:#5b6472;">Subtotal</td>
                                    <td align="right" style="padding:4px 8px;">৳{{ number_format($order->subtotal) }}</td>
                                </tr>
                                @if ($order->discount > 0)
                                    <tr>
                                        <td style="padding:4px 8px;color:#1a9c53;">Discount{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}</td>
                                        <td align="right" style="padding:4px 8px;color:#1a9c53;">− ৳{{ number_format($order->discount) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:4px 8px;color:#5b6472;">Delivery</td>
                                    <td align="right" style="padding:4px 8px;">{{ $order->shipping_fee > 0 ? '৳'.number_format($order->shipping_fee) : 'FREE' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 8px;border-top:2px solid #0f2144;font-weight:bold;font-size:16px;">Total</td>
                                    <td align="right" style="padding:10px 8px;border-top:2px solid #0f2144;font-weight:bold;font-size:16px;color:#f97316;">৳{{ number_format($order->total) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Delivery details --}}
                    <tr>
                        <td style="padding:20px 24px 0;">
                            <div style="background:#f7f8fa;border-radius:6px;padding:16px;font-size:13px;line-height:20px;color:#5b6472;">
                                <strong style="color:#0f2144;display:block;margin-bottom:6px;">Delivery Address</strong>
                                {{ $order->name }}<br>
                                {{ $order->phone }}<br>
                                {{ $order->address }}, {{ $order->city }}<br>
                                <span style="display:inline-block;margin-top:6px;">
                                    Payment: <strong style="color:#0f2144;text-transform:uppercase;">{{ $order->payment_method }}</strong>
                                    ({{ $order->payment_status }})
                                </span>
                            </div>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td align="center" style="padding:24px;">
                            <a href="{{ route('track') }}" style="display:inline-block;background:#f97316;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:4px;font-size:14px;font-weight:bold;">
                                Track Your Order
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#0f2144;padding:20px 24px;text-align:center;color:#aab3c2;font-size:12px;line-height:20px;">
                            @if (! empty($settings['contact_phone']))<div>Phone: {{ $settings['contact_phone'] }}</div>@endif
                            @if (! empty($settings['contact_email']))<div>Email: {{ $settings['contact_email'] }}</div>@endif
                            <div style="margin-top:8px;">&copy; {{ date('Y') }} Raimart. All rights reserved.</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
