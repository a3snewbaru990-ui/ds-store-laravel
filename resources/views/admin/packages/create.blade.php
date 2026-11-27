@extends('layouts.workspace')

@section('title', 'Tambah Paket | Admin DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Tambah paket')

@section('content')
    <div class="workspace-toolbar">
        <a href="{{ route('admin.packages.index') }}" class="back-link">
            <x-icon name="arrow-left" />
            Kembali ke paket joki
        </a>
    </div>

    @include('admin.packages._form')
@endsection
