<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\TelegramNotifier;
use App\Support\Bangladesh;
use App\Support\Csv;
use App\Support\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function index(Request $request)
    {
        $orders = $this->filtered($request)
            ->with('items')
            ->paginate((int) $request->input('per_page', 20))
            ->withQueryString();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'unpaid' => Order::where('payment_status', 'unpaid')->count(),
        ];

        // Revenue for the current filter, so the admin sees what the view is worth.
        $filteredTotal = (clone $this->filtered($request))->sum('total');

        return view('admin.orders.index', compact('orders', 'counts', 'filteredTotal'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'payments');

        return view('admin.orders.show', compact('order'));
    }

    /** Printable invoice (browser print → PDF), with the shop logo and full details. */
    public function invoice(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.invoice', [
            'order' => $order,
            'settings' => SiteSetting::getAll(),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $wasCancelled = $order->status === 'cancelled';
        $nowCancelled = $data['status'] === 'cancelled';

        DB::transaction(function () use ($order, $data, $wasCancelled, $nowCancelled) {
            $order->update($data);

            // Stock follows the cancellation: give it back when an order is cancelled,
            // and take it again if the cancellation is reversed.
            if (! $wasCancelled && $nowCancelled) {
                $this->adjustStock($order, 1);
            } elseif ($wasCancelled && ! $nowCancelled) {
                $this->adjustStock($order, -1);
            }
        });

        return back()->with('status', 'Order status updated.');
    }

    /** Courier / tracking number / internal note — none of it is shown to the customer. */
    public function updateTracking(Request $request, Order $order)
    {
        $data = $request->validate([
            'courier_name' => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update($data);

        return back()->with('status', 'Tracking details saved.');
    }

    /** Mark an order paid/unpaid by hand (bank transfer, cash collected, etc.). */
    public function updatePayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:unpaid,paid,refunded'],
        ]);

        $order->update($data);

        return back()->with('status', 'Payment status updated.');
    }

    // ---------------------------------------------------------------- manual order

    public function create()
    {
        return view('admin.orders.create', [
            'products' => Product::with('category')->active()->orderBy('name')->get(),
            'districts' => Bangladesh::districts(),
            'areas' => Shipping::areas(),
        ]);
    }

    public function store(Request $request, TelegramNotifier $telegram)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,piprapay,manual'],
            'payment_status' => ['required', 'in:unpaid,paid'],
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $products = Product::findMany(collect($data['items'])->pluck('product_id'))->keyBy('id');

        $subtotal = collect($data['items'])->sum(
            fn ($line) => (float) $products[$line['product_id']]->price * (int) $line['quantity']
        );

        $discount = min((float) ($data['discount'] ?? 0), $subtotal);
        $shippingFee = (float) ($data['shipping_fee'] ?? Shipping::fee(Bangladesh::areaFor($data['city']), $subtotal));
        $total = $subtotal - $discount + $shippingFee;

        $order = DB::transaction(function () use ($data, $products, $subtotal, $discount, $shippingFee, $total) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'city' => $data['city'],
                'delivery_area' => Bangladesh::areaFor($data['city']),
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'],
                'status' => $data['status'],
            ]);

            foreach ($data['items'] as $line) {
                $product = $products[$line['product_id']];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $line['quantity'],
                    'subtotal' => (float) $product->price * (int) $line['quantity'],
                ]);

                $product->decrement('stock', (int) $line['quantity']);
            }

            return $order;
        });

        $telegram->orderPlaced($order);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order created successfully.');
    }

    // ---------------------------------------------------------------- export

    public function export(Request $request)
    {
        $orders = $this->filtered($request)->with('items')->get();

        $rows = $orders->map(fn (Order $o) => [
            $o->order_number,
            $o->created_at?->format('Y-m-d H:i'),
            $o->name,
            $o->phone,
            $o->email,
            $o->address,
            $o->city,
            $o->delivery_area,
            $o->items->map(fn ($i) => $i->product_name.' x'.$i->quantity)->implode(' | '),
            $o->items->sum('quantity'),
            $o->subtotal,
            $o->discount,
            $o->coupon_code,
            $o->shipping_fee,
            $o->total,
            $o->payment_method,
            $o->payment_status,
            $o->status,
            $o->notes,
        ]);

        return Csv::download(
            'orders-'.now()->format('Y-m-d'),
            ['Order #', 'Date', 'Customer', 'Phone', 'Email', 'Address', 'District', 'Area', 'Items', 'Total Qty', 'Subtotal', 'Discount', 'Coupon', 'Shipping', 'Total', 'Payment Method', 'Payment Status', 'Status', 'Notes'],
            $rows,
        );
    }

    // ---------------------------------------------------------------- helpers

    /** Shared filter pipeline used by the list, the totals and the export. */
    private function filtered(Request $request)
    {
        return Order::query()
            ->when($request->search, fn ($q, $s) => $q->where(function ($sub) use ($s) {
                $sub->where('order_number', 'like', "%{$s}%")
                    ->orWhere('name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            }))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->payment_status, fn ($q, $ps) => $q->where('payment_status', $ps))
            ->when($request->payment_method, fn ($q, $pm) => $q->where('payment_method', $pm))
            ->when($request->area, fn ($q, $area) => $q->where('delivery_area', $area))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->to))
            ->when($request->filled('min_total'), fn ($q) => $q->where('total', '>=', (float) $request->min_total))
            ->when($request->filled('max_total'), fn ($q) => $q->where('total', '<=', (float) $request->max_total))
            ->when($request->sort === 'oldest', fn ($q) => $q->oldest(), fn ($q) => $q->when(
                $request->sort === 'total_high',
                fn ($q2) => $q2->orderByDesc('total'),
                fn ($q2) => $q2->when($request->sort === 'total_low', fn ($q3) => $q3->orderBy('total'), fn ($q3) => $q3->latest())
            ));
    }

    /** $direction: +1 returns stock to the shelf, -1 takes it back off. */
    private function adjustStock(Order $order, int $direction): void
    {
        foreach ($order->items()->with('product')->get() as $item) {
            $item->product?->increment('stock', $direction * $item->quantity);
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
