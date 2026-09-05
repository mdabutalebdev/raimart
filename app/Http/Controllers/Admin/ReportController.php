<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Support\Csv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        $paid = fn () => Order::whereBetween('created_at', [$from, $to])->where('payment_status', 'paid');
        $all = fn () => Order::whereBetween('created_at', [$from, $to]);

        $summary = [
            'revenue' => (float) $paid()->sum('total'),
            'orders' => $all()->count(),
            'paid_orders' => $paid()->count(),
            'items_sold' => (int) OrderItem::whereHas('order', fn ($q) => $q->whereBetween('created_at', [$from, $to]))->sum('quantity'),
            'avg_order' => 0.0,
            'discount' => (float) $all()->sum('discount'),
            'shipping' => (float) $all()->sum('shipping_fee'),
        ];
        $summary['avg_order'] = $summary['paid_orders'] ? round($summary['revenue'] / $summary['paid_orders'], 2) : 0.0;

        // Revenue per day across the range, zero-filled so the chart has no gaps.
        $daily = Order::selectRaw('DATE(created_at) as day, SUM(total) as revenue, COUNT(*) as orders')
            ->whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->groupBy('day')
            ->pluck('revenue', 'day');

        $chart = ['labels' => [], 'revenue' => []];
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            $key = $date->toDateString();
            $chart['labels'][] = $date->format('d M');
            $chart['revenue'][] = (float) ($daily[$key] ?? 0);
        }

        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', fn ($q) => $q->whereBetween('created_at', [$from, $to]))
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        $byStatus = Order::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('status')
            ->get();

        $byArea = Order::select('delivery_area', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('delivery_area')
            ->get();

        return view('admin.reports.index', compact('summary', 'chart', 'topProducts', 'byStatus', 'byArea', 'from', 'to'));
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->range($request);

        $rows = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', fn ($q) => $q->whereBetween('created_at', [$from, $to]))
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn ($r) => [$r->product_name, $r->qty, $r->revenue]);

        return Csv::download(
            'sales-report-'.$from->format('Y-m-d').'-to-'.$to->format('Y-m-d'),
            ['Product', 'Quantity Sold', 'Revenue'],
            $rows,
        );
    }

    /** Defaults to the last 30 days when no range is given. */
    private function range(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->subDays(29)->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        return [$from, $to];
    }
}
