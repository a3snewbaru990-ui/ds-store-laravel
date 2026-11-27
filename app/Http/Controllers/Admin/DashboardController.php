<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalOrders = Order::count();
        $activeOrders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_IN_PROGRESS,
        ])->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $revenue = Order::where('payment_status', 'paid')->sum('total_price');
        $customerCount = User::where('is_admin', false)->count();

        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentOrders = Order::with(['user', 'package'])
            ->latest()
            ->limit(6)
            ->get();

        $topPackages = ServicePackage::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(4)
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'activeOrders' => $activeOrders,
            'completedOrders' => $completedOrders,
            'revenue' => $revenue,
            'customerCount' => $customerCount,
            'statusCounts' => $statusCounts,
            'recentOrders' => $recentOrders,
            'topPackages' => $topPackages,
        ]);
    }
}
