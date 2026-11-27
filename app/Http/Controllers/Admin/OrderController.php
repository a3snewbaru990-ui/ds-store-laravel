<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'package'])->latest();

        if ($request->filled('q')) {
            $search = trim($request->string('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('order_code', 'like', "%{$search}%")
                    ->orWhere('game_nickname', 'like', "%{$search}%")
                    ->orWhere('game_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && array_key_exists($request->string('status')->toString(), Order::STATUSES)) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('admin.orders.index', [
            'orders' => $query->paginate(10)->withQueryString(),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'package', 'events']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
            'payment_status' => ['required', Rule::in(array_keys(Order::PAYMENT_STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $oldStatus = $order->status;

            $order->fill([
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'admin_note' => $validated['admin_note'] ?? null,
            ]);

            if ($validated['status'] === Order::STATUS_COMPLETED && ! $order->completed_at) {
                $order->completed_at = now();
            }

            if ($validated['status'] !== Order::STATUS_COMPLETED) {
                $order->completed_at = null;
            }

            $order->save();

            if ($oldStatus !== $order->status) {
                $order->events()->create([
                    'status' => $order->status,
                    'title' => $order->status_label,
                    'note' => $validated['admin_note'] ?: $order->status_description,
                ]);
            }
        });

        return back()->with('success', 'Pesanan ' . $order->order_code . ' berhasil diperbarui.');
    }
}
