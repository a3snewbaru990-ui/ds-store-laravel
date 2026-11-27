@extends('layouts.auth')

@section('title', 'Daftar | DS Store')
@section('description', 'Buat akun DS Store untuk memesan joki Mobile Legends dan memantau progres pesanan.')

@section('content')
    <div class="auth-heading">
        <p class="eyebrow">Buat akun</p>
        <h1>Mulai dari satu akun.</h1>
        <p>Semua pesanan, status, dan riwayat joki tersimpan di dashboard kamu.</p>
    </div>

    <form action="{{ route('register.store') }}" method="POST" class="form-stack">
        @csrf

        <div class="field">
            <label for="name">Nama</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                autocomplete="name"
                required
                autofocus
                @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
            >
            <x-input-error name="name" />
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                inputmode="email"
                required
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            >
            <x-input-error name="email" />
        </div>

        <div class="field">
            <label for="whatsapp">Nomor WhatsApp</label>
            <input
                type="tel"
                id="whatsapp"
                name="whatsapp"
                value="{{ old('whatsapp') }}"
                autocomplete="tel"
                inputmode="tel"
                placeholder="081807283181"
                required
                aria-describedby="whatsapp-help @error('whatsapp') whatsapp-error @enderror"
                @error('whatsapp') aria-invalid="true" @enderror
            >
            <p class="field-help" id="whatsapp-help">Dipakai admin untuk konfirmasi pesanan.</p>
            <x-input-error name="whatsapp" />
        </div>

        <div class="field-grid">
            <div class="field">
                <label for="password">Password</label>
                <div class="password-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        required
                        aria-describedby="password-help @error('password') password-error @enderror"
                        @error('password') aria-invalid="true" @enderror
                    >
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Tampilkan password">
                        <x-icon name="eye" />
                    </button>
                </div>
                <p class="field-help" id="password-help">Minimal 8 karakter, berisi huruf dan angka.</p>
                <x-input-error name="password" />
            </div>

            <div class="field">
                <label for="password_confirmation">Ulangi password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >
            </div>
        </div>

        <label class="checkbox-row">
            <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
            <span>Saya setuju dengan ketentuan layanan DS Store.</span>
        </label>
        <x-input-error name="terms" />

        <button type="submit" class="btn btn--primary btn--block">
            Buat akun
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
@endsection
