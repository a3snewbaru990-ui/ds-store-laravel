<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_customer_cannot_open_the_admin_dashboard(): void
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_update_order_status_and_the_timeline_is_recorded(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();
        $package = $this->makePackage();
        $order = $this->makeOrder($customer, $package);

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), [
                'status' => Order::STATUS_CONFIRMED,
                'payment_status' => 'paid',
                'admin_note' => 'Pembayaran sudah diverifikasi.',
            ])
            ->assertRedirect();

        $order->refresh();

        $this->assertSame(Order::STATUS_CONFIRMED, $order->status);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('Pembayaran sudah diverifikasi.', $order->admin_note);
        $this->assertDatabaseHas('order_events', [
            'order_id' => $order->id,
            'status' => Order::STATUS_CONFIRMED,
            'title' => 'Dikonfirmasi',
        ]);
    }

    public function test_admin_can_create_a_new_package(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.packages.store'), [
                'name' => 'Mythic Immortal',
                'short_name' => 'Immortal',
                'slug' => 'mythic-immortal',
                'rank_key' => 'mythic-immortal',
                'price' => 25000,
                'unit' => 'star',
                'badge' => 'New',
                'tagline' => 'Level tertinggi',
                'description' => 'Paket push rank Mythic Immortal.',
                'sort_order' => 8,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.packages.index'));

        $this->assertDatabaseHas('service_packages', [
            'slug' => 'mythic-immortal',
            'price' => 25000,
            'emblem' => 'images/rank-mythic-immortal.webp',
        ]);
    }

    public function test_package_with_order_history_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();
        $package = $this->makePackage();
        $this->makeOrder($customer, $package);

        $this->actingAs($admin)
            ->delete(route('admin.packages.destroy', $package))
            ->assertRedirect();

        $this->assertDatabaseHas('service_packages', ['id' => $package->id]);
    }

    private function makePackage(): ServicePackage
    {
        return ServicePackage::create([
            'name' => 'Mythic',
            'short_name' => 'Mythic',
            'slug' => 'mythic',
            'rank_key' => 'mythic',
            'price' => 12000,
            'unit' => 'star',
            'sort_order' => 1,
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    private function makeOrder(User $user, ServicePackage $package): Order
    {
        return Order::create([
            'order_code' => 'DS-ADMIN-001',
            'user_id' => $user->id,
            'service_package_id' => $package->id,
            'package_name' => $package->name,
            'package_unit' => $package->unit,
            'unit_price' => $package->price,
            'stars' => 10,
            'total_price' => $package->price * 10,
            'game_nickname' => 'Tester ML',
            'game_id' => '123456789',
            'server_id' => '2001',
            'login_method' => 'Moonton',
            'whatsapp' => '6281234567890',
            'status' => Order::STATUS_PENDING,
            'payment_status' => 'unpaid',
        ]);
    }
}
