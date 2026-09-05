<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\Cart;
use App\Services\CouponService;
use App\Services\Ga4MeasurementProtocol;
use App\Services\PipraPayService;
use App\Services\TelegramNotifier;
use App\Support\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private Ga4MeasurementProtocol $ga4,
        private TelegramNotifier $telegram,
        private CouponService $coupons,
    ) {
    }

    public function index(Cart $cart)
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $cart->subtotal();
        // Mirror the district → area rule used on submit, so the summary opens with the right rate.
        $city = old('city', auth()->user()->city ?? '');
        $area = $city ? (strcasecmp(trim($city), 'Dhaka') === 0 ? 'inside' : 'outside') : Shipping::DEFAULT_AREA;
        $discount = $this->coupons->discount($subtotal);
        $shippingFee = Shipping::fee($area, $subtotal, $this->coupons->givesFreeShipping($subtotal));
        $total = max(0, $subtotal - $discount) + $shippingFee;

        return view('checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $this->coupons->coupon(),
            'shippingFee' => $shippingFee,
            'total' => $total,
            'area' => $area,
            'areas' => Shipping::areas(),
            'freeThreshold' => Shipping::threshold(),
        ]);
    }

    /** Apply a coupon code from the checkout page. */
    public function applyCoupon(Request $request, Cart $cart)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50']]);

        [$ok, $message] = $this->coupons->apply($data['code'], $cart->subtotal());

        return back()->with($ok ? 'status' : 'error', $message);
    }

    public function removeCoupon()
    {
        $this->coupons->clear();

        return back()->with('status', 'Coupon removed.');
    }

    public function store(Request $request, Cart $cart)
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'delivery_area' => ['nullable', 'in:inside,outside'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,piprapay'],
            'ga_client_id' => ['nullable', 'string', 'max:64'],
            'ga_session_id' => ['nullable', 'string', 'max:64'],
        ]);

        foreach ($items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->withErrors(['quantity' => "Only {$item->product->stock} units of {$item->product->name} are available."])->withInput();
            }
        }

        // The district decides the rate — derived server-side so a disabled or
        // tampered-with hidden field can never change the delivery charge.
        $data['delivery_area'] = strcasecmp(trim($data['city']), 'Dhaka') === 0 ? 'inside' : 'outside';

        $subtotal = $cart->subtotal();
        $coupon = $this->coupons->coupon();
        $discount = $this->coupons->discount($subtotal);
        $shippingFee = Shipping::fee($data['delivery_area'], $subtotal, $this->coupons->givesFreeShipping($subtotal));
        $total = max(0, $subtotal - $discount) + $shippingFee;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $discount, $coupon, $shippingFee, $total) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'city' => $data['city'],
                'delivery_area' => $data['delivery_area'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'coupon_code' => $coupon?->code,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'ga_client_id' => $data['ga_client_id'] ?? null,
                'ga_session_id' => $data['ga_session_id'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'options' => $item->options ?: null,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Count the coupon use once the order is actually written.
            $coupon?->increment('used_count');

            return $order;
        });

        $cart->clear();
        $this->coupons->clear();
        session()->forget('delivery_area');

        // Ping the shop owner's Telegram as soon as the order lands.
        $this->telegram->orderPlaced($order);

        if ($order->payment_method === 'piprapay') {
            return $this->initiatePipraPayPayment($order);
        }

        // COD: the order is confirmed the moment it's placed → track purchase server-side.
        $this->ga4->purchase($order);

        return redirect()->route('checkout.confirmation', $order);
    }

    public function pay(Order $order)
    {
        abort_if($order->payment_method !== 'piprapay' || $order->payment_status === 'paid', 404);

        return $this->initiatePipraPayPayment($order);
    }

    public function callback(Request $request, Order $order, PipraPayService $pipraPay)
    {
        $ppId = $request->query('pp_id');

        if ($ppId) {
            $this->verifyAndUpdate($order, $ppId, $pipraPay);
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    public function cancel(Order $order)
    {
        return redirect()->route('checkout.confirmation', $order)->with('status', 'Payment was cancelled. You can retry payment below.');
    }

    public function webhook(Request $request, PipraPayService $pipraPay)
    {
        $ppId = $request->input('pp_id');

        if (! $ppId) {
            return response()->json(['status' => false], 400);
        }

        $payment = Payment::where('pp_id', $ppId)->first();

        if ($payment) {
            $this->verifyAndUpdate($payment->order, $ppId, $pipraPay);
        }

        return response()->json(['status' => true]);
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        return view('checkout-confirmation', compact('order'));
    }

    private function initiatePipraPayPayment(Order $order)
    {
        $pipraPay = app(PipraPayService::class);

        if (! $pipraPay->isConfigured()) {
            return redirect()->route('checkout.confirmation', $order)
                ->with('status', 'Online payment is not available right now. Please contact support or choose Cash on Delivery for a new order.');
        }

        $response = $pipraPay->createCharge([
            'full_name' => $order->name,
            'email_address' => $order->email ?: 'no-email@example.com',
            'mobile_number' => $order->phone,
            'amount' => $order->total,
            'metadata' => ['order_number' => $order->order_number],
            'return_url' => route('checkout.callback', $order),
            'webhook_url' => route('checkout.webhook'),
        ]);

        if (empty($response['pp_url'])) {
            Log::warning('PipraPay createCharge failed', ['order' => $order->order_number, 'response' => $response]);

            return redirect()->route('checkout.confirmation', $order)
                ->with('status', 'Could not start online payment: '.($response['message'] ?? 'unknown error').'. Please retry or contact support.');
        }

        Payment::create([
            'order_id' => $order->id,
            'gateway' => 'piprapay',
            'pp_id' => $response['pp_id'],
            'amount' => $order->total,
            'status' => 'pending',
            'raw_response' => $response,
        ]);

        return redirect()->away($response['pp_url']);
    }

    private function verifyAndUpdate(Order $order, string $ppId, PipraPayService $pipraPay): void
    {
        $result = $pipraPay->verifyPayment($ppId);
        $isCompleted = ($result['status'] ?? null) === 'completed';

        $payment = Payment::where('order_id', $order->id)->where('pp_id', $ppId)->first();

        if ($payment) {
            $payment->update([
                'transaction_id' => $result['transaction_id'] ?? null,
                'status' => $isCompleted ? 'completed' : 'failed',
                'raw_response' => $result,
            ]);
        }

        if ($isCompleted) {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);

            // Online payment confirmed → track purchase (guarded against double-send
            // when both the callback and the webhook fire for the same order).
            $this->ga4->purchase($order->fresh());
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'RM'.now()->format('ymd').strtoupper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
