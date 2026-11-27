<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(ServicePackage $package): View
    {
        abort_unless($package->is_active, 404);

        return view('orders.create', [
            'package' => $package,
            'user' => request()->user(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $package = ServicePackage::active()->findOrFail($request->integer('service_package_id'));
        $stars = $package->isStarPackage() ? $request->integer('stars') : 10;
        $totalPrice = $package->isStarPackage() ? $package->price * $stars : $package->price;

        $order = DB::transaction(function () use ($request, $package, $stars, $totalPrice) {
            $order = Order::create([
                'order_code' => Order::generateOrderCode(),
                'user_id' => $request->user()->id,
                'service_package_id' => $package->id,
                'package_name' => $package->name,
                'package_unit' => $package->unit,
                'unit_price' => $package->price,
                'stars' => $stars,
                'total_price' => $totalPrice,
                'game_nickname' => $request->string('game_nickname')->trim(),
                'game_id' => $request->string('game_id')->trim(),
                'server_id' => $request->string('server_id')->trim(),
                'login_method' => $request->string('login_method'),
                'whatsapp' => $this->normalizeWhatsapp($request->string('whatsapp')),
                'payment_method' => $request->input('payment_method'),
                'notes' => $request->input('notes'),
                'status' => Order::STATUS_PENDING,
                'payment_status' => 'unpaid',
            ]);

            $order->events()->create([
                'status' => Order::STATUS_PENDING,
                'title' => 'Pesanan dibuat',
                'note' => 'Pesanan masuk ke sistem dan menunggu konfirmasi admin.',
            ]);

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Simpan kode pesanan dan tunggu konfirmasi admin.');
    }

    private function normalizeWhatsapp(string $number): string
    {
        $number = preg_replace('/[^0-9+]/', '', $number) ?? '';

        if (str_starts_with($number, '+62')) {
            return substr($number, 1);
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        return $number;
    }
}
