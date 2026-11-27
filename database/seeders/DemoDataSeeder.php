<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@dsstore.id'],
            [
                'name' => 'Admin DS Store',
                'whatsapp' => '6281807283181',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'demo@dsstore.id'],
            [
                'name' => 'Demo Player',
                'whatsapp' => '6281200000000',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        if ($customer->orders()->exists()) {
            return;
        }

        $mythic = ServicePackage::where('slug', 'mythic')->first();
        $epic = ServicePackage::where('slug', 'epic')->first();

        if (! $mythic || ! $epic) {
            return;
        }

        $orders = [
            [
                'order_code' => 'DS-DEMO-001',
                'service_package_id' => $mythic->id,
                'package_name' => $mythic->name,
                'package_unit' => $mythic->unit,
                'unit_price' => $mythic->price,
                'stars' => 10,
                'total_price' => $mythic->price * 10,
                'game_nickname' => 'DemoPlayer',
                'game_id' => '123456789',
                'server_id' => '2001',
                'login_method' => 'Moonton',
                'whatsapp' => '6281200000000',
                'payment_method' => 'QRIS',
                'notes' => 'Data contoh untuk demo aplikasi.',
                'status' => Order::STATUS_IN_PROGRESS,
                'payment_status' => 'paid',
                'admin_note' => 'Progres demo sedang berjalan.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDay(),
                'events' => [
                    ['status' => Order::STATUS_PENDING, 'title' => 'Pesanan dibuat', 'note' => 'Pesanan demo masuk ke sistem.', 'created_at' => now()->subDays(2)],
                    ['status' => Order::STATUS_CONFIRMED, 'title' => 'Pesanan dikonfirmasi', 'note' => 'Data akun sudah dicek admin.', 'created_at' => now()->subDays(2)->addHours(2)],
                    ['status' => Order::STATUS_IN_PROGRESS, 'title' => 'Pengerjaan dimulai', 'note' => 'Tim mulai push rank.', 'created_at' => now()->subDay()],
                ],
            ],
            [
                'order_code' => 'DS-DEMO-002',
                'service_package_id' => $epic->id,
                'package_name' => $epic->name,
                'package_unit' => $epic->unit,
                'unit_price' => $epic->price,
                'stars' => 15,
                'total_price' => $epic->price * 15,
                'game_nickname' => 'DemoPlayer',
                'game_id' => '123456789',
                'server_id' => '2001',
                'login_method' => 'Moonton',
                'whatsapp' => '6281200000000',
                'payment_method' => 'Transfer bank',
                'notes' => 'Data contoh untuk demo aplikasi.',
                'status' => Order::STATUS_COMPLETED,
                'payment_status' => 'paid',
                'admin_note' => 'Pesanan demo selesai.',
                'completed_at' => now()->subDays(8),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8),
                'events' => [
                    ['status' => Order::STATUS_PENDING, 'title' => 'Pesanan dibuat', 'note' => 'Pesanan demo masuk ke sistem.', 'created_at' => now()->subDays(10)],
                    ['status' => Order::STATUS_CONFIRMED, 'title' => 'Pesanan dikonfirmasi', 'note' => 'Pembayaran dan data dicek.', 'created_at' => now()->subDays(10)->addHours(1)],
                    ['status' => Order::STATUS_IN_PROGRESS, 'title' => 'Pengerjaan dimulai', 'note' => 'Tim mulai push rank.', 'created_at' => now()->subDays(9)],
                    ['status' => Order::STATUS_COMPLETED, 'title' => 'Pesanan selesai', 'note' => 'Target bintang tercapai.', 'created_at' => now()->subDays(8)],
                ],
            ],
        ];

        foreach ($orders as $data) {
            $events = $data['events'];
            unset($data['events']);

            $order = Order::create([
                ...$data,
                'user_id' => $customer->id,
            ]);

            foreach ($events as $event) {
                OrderEvent::create([
                    'order_id' => $order->id,
                    ...$event,
                ]);
            }
        }
    }
}
