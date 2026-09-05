<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Support\Csv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = $this->filtered($request)
            ->paginate((int) $request->input('per_page', 20))
            ->withQueryString();

        $stats = [
            'total' => User::where('is_admin', false)->count(),
            'with_orders' => Order::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'guests' => Order::whereNull('user_id')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        abort_if($customer->is_admin, 404);

        $orders = $customer->orders()->with('items')->latest()->get();

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders' => $orders,
            'totalSpent' => $orders->where('payment_status', 'paid')->sum('total'),
        ]);
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get()->map(fn (User $u) => [
            $u->id,
            $u->name,
            $u->email,
            $u->phone,
            $u->address,
            $u->city,
            $u->orders_count,
            $u->orders_total,
            $u->created_at?->format('Y-m-d'),
        ]);

        return Csv::download(
            'customers-'.now()->format('Y-m-d'),
            ['ID', 'Name', 'Email', 'Phone', 'Address', 'City', 'Orders', 'Total Spent', 'Joined'],
            $rows,
        );
    }

    /** Customers with their order count and lifetime value attached. */
    private function filtered(Request $request)
    {
        return User::where('is_admin', false)
            ->withCount('orders')
            ->withSum(['orders as orders_total' => fn ($q) => $q->where('payment_status', 'paid')], 'total')
            ->when($request->search, fn ($q, $s) => $q->where(function ($sub) use ($s) {
                $sub->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            }))
            ->when($request->has_orders === 'yes', fn ($q) => $q->has('orders'))
            ->when($request->has_orders === 'no', fn ($q) => $q->doesntHave('orders'))
            ->when($request->sort === 'spent', fn ($q) => $q->orderByDesc('orders_total'))
            ->when($request->sort === 'orders', fn ($q) => $q->orderByDesc('orders_count'))
            ->when($request->sort === 'name', fn ($q) => $q->orderBy('name'))
            ->when(! in_array($request->sort, ['spent', 'orders', 'name']), fn ($q) => $q->latest());
    }
}
