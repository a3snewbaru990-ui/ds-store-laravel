<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Grandmaster',
                'short_name' => 'GM',
                'slug' => 'grandmaster',
                'rank_key' => 'grandmaster',
                'price' => 5000,
                'unit' => 'star',
                'badge' => null,
                'tagline' => 'Mulai dari sini',
                'description' => 'Paket push rank dari Grandmaster. Harga dihitung per bintang.',
                'emblem' => 'images/rank-grandmaster.webp',
                'sort_order' => 1,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Epic',
                'short_name' => 'Epic',
                'slug' => 'epic',
                'rank_key' => 'epic',
                'price' => 6000,
                'unit' => 'star',
                'badge' => null,
                'tagline' => 'Naik satu tier',
                'description' => 'Paket push rank dari Epic. Harga dihitung per bintang.',
                'emblem' => 'images/rank-epic.webp',
                'sort_order' => 2,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Legend',
                'short_name' => 'Legend',
                'slug' => 'legend',
                'rank_key' => 'legend',
                'price' => 7000,
                'unit' => 'star',
                'badge' => null,
                'tagline' => 'Menuju Mythic',
                'description' => 'Paket push rank dari Legend. Harga dihitung per bintang.',
                'emblem' => 'images/rank-legend.webp',
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Mythic',
                'short_name' => 'Mythic',
                'slug' => 'mythic',
                'rank_key' => 'mythic',
                'price' => 12000,
                'unit' => 'star',
                'badge' => 'Paling dicari',
                'tagline' => 'Masuk zona Mythic',
                'description' => 'Paket push rank dari Mythic. Harga dihitung per bintang.',
                'emblem' => 'images/rank-mythic.webp',
                'sort_order' => 4,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Mythic Honor',
                'short_name' => 'Honor',
                'slug' => 'mythic-honor',
                'rank_key' => 'mythic-honor',
                'price' => 15000,
                'unit' => 'star',
                'badge' => null,
                'tagline' => 'Push lebih tinggi',
                'description' => 'Paket push rank dari Mythic Honor. Harga dihitung per bintang.',
                'emblem' => 'images/rank-mythic-honor.webp',
                'sort_order' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Mythic Glory',
                'short_name' => 'Glory',
                'slug' => 'mythic-glory',
                'rank_key' => 'mythic-glory',
                'price' => 20000,
                'unit' => 'star',
                'badge' => null,
                'tagline' => 'Level tertinggi',
                'description' => 'Paket push rank dari Mythic Glory. Harga dihitung per bintang.',
                'emblem' => 'images/rank-mythic-glory.webp',
                'sort_order' => 6,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Full Grading Mythic',
                'short_name' => 'Full Grading',
                'slug' => 'full-grading-mythic',
                'rank_key' => 'mythic',
                'price' => 150000,
                'unit' => 'package',
                'badge' => 'Paket lengkap',
                'tagline' => '10 match grading',
                'description' => 'Paket Full Grading Mythic untuk 10 match. Cocok untuk menyelesaikan grading awal season.',
                'emblem' => 'images/rank-mythic.webp',
                'sort_order' => 7,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            ServicePackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}
