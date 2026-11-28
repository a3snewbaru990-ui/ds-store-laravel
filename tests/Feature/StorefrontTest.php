<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ServicePackage;
use App\Models\User;
use Database\Seeders\HeroSlideSeeder;
use Database\Seeders\ServicePackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_hero_and_official_pricelist(): void
    {
        $this->seed([HeroSlideSeeder::class, ServicePackageSeeder::class]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('PROMO JOKI AKUN SEASON 42');
        $response->assertSee('Rp 5.000');
        $response->assertSee('Full Grading Mythic');
        $response->assertSee('Mythic Glory');
    }

    public function test_guest_is_redirected_to_login_before_ordering(): void
    {
        $package = $this->makePackage();

        $this->get(route('orders.create', $package))
            ->assertRedirect(route('login'));
    }

    public function test_customer_can_create_an_order_and_total_is_calculated_server_side(): void
    {
        $customer = User::factory()->create();
        $package = $this->makePackage(['price' => 12000]);

        $response = $this->actingAs($customer)->post(route('orders.store'), [
            'service_package_id' => $package->id,
            'game_nickname' => 'Tester ML',
            'game_id' => '123456789',
            'server_id' => '2001',
            'login_method' => 'Moonton',
            'whatsapp' => '081234567890',
            'payment_method' => 'QRIS',
            'notes' => 'Tolong pakai hero favorit.',
            'stars' => 10,
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertSame(120000, $order->total_price);
        $this->assertSame(10, $order->stars);
        $this->assertSame('6281234567890', $order->whatsapp);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertDatabaseHas('order_events', [
            'order_id' => $order->id,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    public function test_full_grading_package_uses_package_price_instead_of_multiplying_stars(): void
    {
        $customer = User::factory()->create();
        $package = $this->makePackage([
            'name' => 'Full Grading Mythic',
            'short_name' => 'Full Grading',
            'slug' => 'full-grading-mythic',
            'price' => 150000,
            'unit' => 'package',
        ]);

        $this->actingAs($customer)->post(route('orders.store'), [
            'service_package_id' => $package->id,
            'game_nickname' => 'Tester ML',
            'game_id' => '123456789',
            'server_id' => '2001',
            'login_method' => 'Moonton',
            'whatsapp' => '081234567890',
            'stars' => 10,
        ])->assertRedirect();

        $this->assertSame(150000, Order::firstOrFail()->total_price);
    }

    public function test_order_validation_rejects_an_invalid_game_id(): void
    {
        $customer = User::factory()->create();
        $package = $this->makePackage();

        $this->actingAs($customer)->post(route('orders.store'), [
            'service_package_id' => $package->id,
            'game_nickname' => 'Tester ML',
            'game_id' => 'abc',
            'server_id' => '2001',
            'login_method' => 'Moonton',
            'whatsapp' => '081234567890',
            'stars' => 10,
        ])->assertSessionHasErrors('game_id');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $package = $this->makePackage();
        $otherOrder = $this->makeOrder($otherCustomer, $package, [
            'order_code' => 'DS-OTHER-001',
        ]);

        $this->actingAs($customer)
            ->get(route('orders.show', $otherOrder))
            ->assertNotFound();
    }

    public function test_order_can_be_tracked_with_code_and_whatsapp(): void
    {
        $customer = User::factory()->create();
        $package = $this->makePackage();
        $this->makeOrder($customer, $package, [
            'order_code' => 'DS-TRACK-001',
            'whatsapp' => '6281234567890',
        ]);

        $response = $this->post(route('track'), [
            'order_code' => 'ds-track-001',
            'whatsapp' => '081234567890',
        ]);

        $response->assertOk();
        $response->assertSee('DS-TRACK-001');
        $response->assertSee('Menunggu konfirmasi');
    }

    public function test_tracking_rejects_a_mismatched_whatsapp_number(): void
    {
        $customer = User::factory()->create();
        $package = $this->makePackage();
        $this->makeOrder($customer, $package, [
            'order_code' => 'DS-TRACK-002',
            'whatsapp' => '6281234567890',
        ]);

        $this->post(route('track'), [
            'order_code' => 'DS-TRACK-002',
            'whatsapp' => '081299999999',
        ])->assertSessionHasErrors('order_code');
    }

    public function test_admin_can_be_created_with_the_artisan_command(): void
    {
        $this->artisan('admin:create', [
            'email' => 'admin@example.test',
            '--name' => 'Admin Utama',
            '--password' => 'rahasia123',
        ])->assertSuccessful();

        $admin = User::where('email', 'admin@example.test')->firstOrFail();

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('rahasia123', $admin->password));

        $this->post(route('login.store'), [
            'email' => 'admin@example.test',
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_registration_creates_a_customer_account(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Pemain Baru',
            'email' => 'pemain@example.test',
            'whatsapp' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'pemain@example.test',
            'is_admin' => false,
        ]);
    }

    private function makePackage(array $overrides = []): ServicePackage
    {
        return ServicePackage::create(array_merge([
            'name' => 'Mythic',
            'short_name' => 'Mythic',
            'slug' => 'mythic',
            'rank_key' => 'mythic',
            'price' => 12000,
            'unit' => 'star',
            'sort_order' => 1,
            'is_featured' => true,
            'is_active' => true,
        ], $overrides));
    }

    private function makeOrder(User $user, ServicePackage $package, array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_code' => 'DS-TEST-001',
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
        ], $overrides));
    }
}
