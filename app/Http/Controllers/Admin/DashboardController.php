<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'this_month_revenue' => Order::where('payment_status', 'paid')
                                         ->whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->sum('total'),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'customers' => User::where('is_admin', false)->count(),
            'products' => Product::count(),
            'categories' => Category::count(),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
        ];

        // Chart 1: Revenue last 7 days
        $last7Days = collect(range(6, 0))->map(function ($days) {
            return Carbon::now()->subDays($days)->format('M d');
        });

        $revenueData = [];
        foreach (range(6, 0) as $days) {
            $date = Carbon::now()->subDays($days)->toDateString();
            $sum = Order::where('payment_status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total');
            $revenueData[] = (float) round($sum, 2);
        }

        $revenueChart = [
            'labels' => $last7Days->toArray(),
            'data' => $revenueData,
        ];

        // Chart 2: Order Statuses
        $orderStatuses = Order::select('status', DB::raw('count(*) as total'))
                              ->groupBy('status')
                              ->pluck('total', 'status')
                              ->toArray();
                              
        $allStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        $statusChartData = [];
        foreach ($allStatuses as $status) {
            $statusChartData[] = (int) ($orderStatuses[$status] ?? 0);
        }
        
        $statusChart = [
            'labels' => array_map('ucfirst', $allStatuses),
            'data' => $statusChartData,
        ];

        $recentOrders = Order::with('items')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'revenueChart', 'statusChart', 'recentOrders'));
    }
}
