@extends('layouts.workspace')

@section('title', 'Profil | DS Store')
@section('page-kicker', 'Akun saya')
@section('page-title', 'Profil')

@php
    $user = auth()->user();
@endphp

@section('content')
    <div class="profile-layout">
        <section class="form-panel" aria-labelledby="profile-title">
            <div class="form-panel__head">
                <span class="form-panel__step"><x-icon name="user" /></span>
                <div>
                    <h2 id="profile-title">Informasi akun</h2>
                    <p>Perbarui data kontak supaya admin mudah menghubungi kamu.</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="form-panel__body">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    <x-input-error name="name" />
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    <x-input-error name="email" />
                </div>

                <div class="field">
                    <label for="whatsapp">Nomor WhatsApp</label>
                    <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" autocomplete="tel" required aria-describedby="whatsapp-help @error('whatsapp') whatsapp-error @enderror" @error('whatsapp') aria-invalid="true" @enderror>
                    <p class="field-help" id="whatsapp-help">Dipakai untuk konfirmasi pesanan.</p>
                    <x-input-error name="whatsapp" />
                </div>

                <div class="form-divider"></div>

                <div class="form-subhead">
                    <h3>Ganti password</h3>
                    <p>Kosongkan bagian ini jika tidak ingin mengganti password.</p>
                </div>

                <div class="field">
                    <label for="current_password">Password saat ini</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password" @error('current_password') aria-invalid="true" aria-describedby="current_password-error" @enderror>
                    <x-input-error name="current_password" />
                </div>

                <div class="field-grid">
                    <div class="field">
                        <label for="password">Password baru</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" aria-describedby="password-help @error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror>
                        <p class="field-help" id="password-help">Minimal 8 karakter, berisi huruf dan angka.</p>
                        <x-input-error name="password" />
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Ulangi password baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        Simpan perubahan
                        <x-icon name="check" />
                    </button>
                </div>
            </form>
        </section>

        <aside class="profile-side">
            <div class="profile-card">
                <span class="profile-card__avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <x-status-badge :tone="$user->isAdmin() ? 'gold' : 'info'" :label="$user->isAdmin() ? 'Administrator' : 'Pelanggan'" />
                <dl class="profile-facts">
                    <div>
                        <dt>Bergabung</dt>
                        <dd>{{ $user->created_at->translatedFormat('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt>Login terakhir</dt>
                        <dd>{{ $user->last_login_at?->translatedFormat('d M Y, H:i') ?? 'Belum tercatat' }}</dd>
                    </div>
                    <div>
                        <dt>Total pesanan</dt>
                        <dd>{{ $user->orders()->count() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="side-note">
                <x-icon name="lock" />
                <p>Password game tidak pernah diminta di website. Jangan simpan password game di catatan pesanan.</p>
            </div>
        </aside>
    </div>
@endsection
