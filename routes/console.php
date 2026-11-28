<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email} {--name=Admin DS Store} {--password=}', function () {
    $email = strtolower($this->argument('email'));

    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error("Format email tidak valid: {$email}");

        return 1;
    }

    $password = $this->option('password') ?: $this->secret('Password admin (minimal 8 karakter, huruf dan angka)');

    if (mb_strlen($password) < 8 || ! preg_match('/[a-zA-Z]/', $password) || ! preg_match('/\d/', $password)) {
        $this->error('Password minimal 8 karakter dan harus berisi huruf serta angka.');

        return 1;
    }

    $admin = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $this->option('name'),
            'whatsapp' => config('site.whatsapp'),
            'password' => Hash::make($password),
            'is_admin' => true,
        ]
    );

    $this->info("Admin dibuat/diperbarui: {$admin->email}");

    return 0;
})->purpose('Buat atau perbarui akun admin DS Store');
