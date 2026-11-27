<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['required', 'regex:/^(?:\\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email ini sudah terdaftar. Coba login.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Gunakan nomor WhatsApp Indonesia yang aktif, misalnya 081807283181.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password belum sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'terms.accepted' => 'Kamu perlu menyetujui ketentuan layanan.',
        ], [
            'whatsapp' => 'nomor WhatsApp',
            'terms' => 'ketentuan layanan',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Sekarang kamu bisa membuat pesanan.');
    }
}
