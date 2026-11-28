@extends('layouts.auth')

@section('title', 'Masuk | DS Store')
@section('description', 'Masuk ke akun DS Store untuk membuat dan memantau pesanan joki Mobile Legends.')

@section('content')
    <div class="auth-heading">
        <p class="eyebrow">Player workspace</p>
        <h1>Masuk ke DS Store.</h1>
        <p>Lanjutkan pesanan, cek progres, atau buat order baru dari dashboard.</p>
    </div>

    <form action="{{ route('login.store') }}" method="POST" class="form-stack">
        @csrf

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
                autofocus
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            >
            <x-input-error name="email" />
        </div>

        <div class="field">
            <div class="field__label-row">
                <label for="password">Password</label>
                <span class="field__hint">Minimal 8 karakter</span>
            </div>
            <div class="password-field">
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                >
                <button type="button" class="password-toggle" data-password-toggle aria-label="Tampilkan password">
                    <x-icon name="eye" />
                </button>
            </div>
            <x-input-error name="password" />
        </div>

        <label class="checkbox-row">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Ingat saya di perangkat ini</span>
        </label>

        <button type="submit" class="btn btn--primary btn--block">
            Masuk ke dashboard
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
@endsection
