<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'kicker' => 'Mobile Legends · Season 42',
                'title' => 'PROMO JOKI AKUN SEASON 42',
                'description' => 'Naik rank tanpa harus begadang. Pilih paket, isi data akun, lalu pantau progres pesanan dari dashboard.',
                'image' => 'images/hero-art-2.webp',
                'price_note' => 'Mulai Rp 5.000 / bintang',
                'primary_label' => 'Pilih paket joki',
                'primary_url' => '/#pricelist',
                'secondary_label' => 'Tanya admin',
                'secondary_url' => config('site.whatsapp_url'),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'kicker' => 'Paket unggulan',
                'title' => 'FULL GRADING MYTHIC',
                'description' => 'Selesaikan 10 match grading dengan tim yang paham ritme rank Mythic. Cocok untuk akun yang baru masuk season.',
                'image' => 'images/hero-art-3.webp',
                'price_note' => 'Rp 150.000 / 10 match',
                'primary_label' => 'Ambil paket grading',
                'primary_url' => '/order/full-grading-mythic',
                'secondary_label' => 'Lihat pricelist',
                'secondary_url' => '/#pricelist',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'kicker' => 'Admin online 24 jam',
                'title' => 'RESPON CEPAT, PROGRES KILAT.',
                'description' => 'Pesanan masuk, admin cek, progres jalan. Setiap update status tercatat supaya kamu tahu posisi pesanan.',
                'image' => 'images/hero-art-1.webp',
                'price_note' => '100% amanah · data akun dijaga',
                'primary_label' => 'Mulai dari sini',
                'primary_url' => '/#pricelist',
                'secondary_label' => 'Chat WhatsApp',
                'secondary_url' => config('site.whatsapp_url'),
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                ['sort_order' => $slide['sort_order']],
                $slide
            );
        }
    }
}
