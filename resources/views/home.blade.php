@extends('layouts.app')

@section('title', 'DS Store | Joki Akun Mobile Legends Season 42')
@section('description', 'Jasa joki akun Mobile Legends Season 42. Harga per bintang mulai Rp 5.000, full grading Mythic Rp 150.000, progres bisa dipantau dari dashboard.')
@section('canonical', route('home'))
@section('og_title', 'DS Store | Promo Joki Akun Mobile Legends Season 42')
@section('og_description', 'Respon cepat, progres kilat, 100% amanah. Pilih paket joki Mobile Legends dan pantau progres pesanan di dashboard DS Store.')

@section('content')
    @include('partials.home.hero')
    @include('partials.home.pricelist')
    @include('partials.home.features')
    @include('partials.home.process')
    @include('partials.home.grading')
    @include('partials.home.faq')
    @include('partials.home.cta')
@endsection
