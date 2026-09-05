<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        $searched = false;

        if ($request->filled('order_number')) {
            $searched = true;

            $order = Order::with('items')
                ->where('order_number', trim((string) $request->input('order_number')))
                ->first();
        }

        return view('track-order', compact('order', 'searched'));
    }
}
