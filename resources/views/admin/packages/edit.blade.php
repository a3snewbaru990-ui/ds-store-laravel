@extends('layouts.workspace')

@section('title', 'Edit ' . $package->name . ' | Admin DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Edit paket')

@section('content')
    <div class="workspace-toolbar">
        <a href="{{ route('admin.packages.index') }}" class="back-link">
            <x-icon name="arrow-left" />
            Kembali ke paket joki
        </a>
        <span class="badge badge--neutral">{{ $package->orders()->count() }} riwayat order</span>
    </div>

    @include('admin.packages._form')
@endsection
