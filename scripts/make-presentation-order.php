<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\ServicePackage;
use App\Models\User;

$admin = User::where('email', 'a3snewbaru990@gmail.com')->firstOrFail();
$package = ServicePackage::where('slug', 'mythic')->firstOrFail();

$order = Order::firstOrCreate(
    ['order_code' => 'DS-261128-ABC12'],
    [
        'user_id' => $admin->id,
        'service_package_id' => $package->id,
        'package_name' => $package->name,
        'package_unit' => $package->unit,
        'unit_price' => $package->price,
        'stars' => 10,
        'total_price' => $package->price * 10,
        'game_nickname' => 'DSPlayer',
        'game_id' => '123456789',
        'server_id' => '2001',
        'login_method' => 'Moonton',
        'whatsapp' => '6281807283181',
        'payment_method' => 'QRIS',
        'notes' => 'Pesanan contoh untuk keperluan dokumentasi.',
        'status' => Order::STATUS_IN_PROGRESS,
        'payment_status' => 'paid',
        'admin_note' => 'Progres sedang berjalan.',
    ]
);

if ($order->wasRecentlyCreated) {
    $order->events()->create([
        'status' => Order::STATUS_PENDING,
        'title' => 'Pesanan dibuat',
        'note' => 'Pesanan masuk ke sistem.',
    ]);
    $order->events()->create([
        'status' => Order::STATUS_CONFIRMED,
        'title' => 'Pesanan dikonfirmasi',
        'note' => 'Data dicek admin.',
    ]);
    $order->events()->create([
        'status' => Order::STATUS_IN_PROGRESS,
        'title' => 'Pengerjaan dimulai',
        'note' => 'Tim mulai push rank.',
    ]);
}

echo 'Order: ', $order->order_code, PHP_EOL;
