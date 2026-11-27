<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('package')
            ->latest()
            ->get();

        $activeOrders = $orders->filter(fn (Order $order) => $order->isActive())->count();
        $completedOrders = $orders->where('status', Order::STATUS_COMPLETED)->count();
        $totalSpent = $orders->where('status', Order::STATUS_COMPLETED)->sum('total_price');

        return view('dashboard.index', [
            'orders' => $orders->take(6),
            'totalOrders' => $orders->count(),
            'activeOrders' => $activeOrders,
            'completedOrders' => $completedOrders,
            'totalSpent' => $totalSpent,
        ]);
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === request()->user()->id, 404);

        $order->load(['package', 'events', 'user']);

        return view('dashboard.orders.show', [
            'order' => $order,
        ]);
    }
}
