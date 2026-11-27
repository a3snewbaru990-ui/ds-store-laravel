<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\ServicePackage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::active()->orderBy('sort_order')->get();
        $packages = ServicePackage::active()->orderBy('sort_order')->get();
        $starPackages = $packages->where('unit', 'star')->values();
        $featuredPackage = $packages->firstWhere('unit', 'package');

        return view('home', [
            'slides' => $slides,
            'packages' => $packages,
            'starPackages' => $starPackages,
            'featuredPackage' => $featuredPackage,
            'faqs' => config('site.faqs'),
        ]);
    }
}
