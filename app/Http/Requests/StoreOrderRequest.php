<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'service_package_id' => ['required', 'integer', 'exists:service_packages,id'],
            'game_nickname' => ['required', 'string', 'max:80'],
            'game_id' => ['required', 'regex:/^[0-9]{5,20}$/'],
            'server_id' => ['required', 'regex:/^[0-9]{1,6}$/'],
            'login_method' => ['required', 'in:Moonton,Facebook,Google,TikTok'],
            'whatsapp' => ['required', 'regex:/^(?:\\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'payment_method' => ['nullable', 'in:QRIS,Transfer bank,E-wallet'],
            'notes' => ['nullable', 'string', 'max:500'],
            'stars' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_package_id.required' => 'Paket joki belum dipilih.',
            'service_package_id.exists' => 'Paket joki tidak ditemukan.',
            'game_nickname.required' => 'Nama in-game wajib diisi.',
            'game_id.required' => 'User ID Mobile Legends wajib diisi.',
            'game_id.regex' => 'User ID hanya boleh berisi angka, 5 sampai 20 digit.',
            'server_id.required' => 'Server ID wajib diisi.',
            'server_id.regex' => 'Server ID hanya boleh berisi angka.',
            'login_method.required' => 'Pilih metode login akun.',
            'login_method.in' => 'Metode login yang dipilih tidak tersedia.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Gunakan nomor WhatsApp Indonesia yang aktif, misalnya 081807283181.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak tersedia.',
            'stars.required' => 'Jumlah bintang wajib diisi.',
            'stars.min' => 'Minimal 1 bintang.',
            'stars.max' => 'Maksimal 100 bintang untuk satu pesanan.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'game_nickname' => 'nama in-game',
            'game_id' => 'User ID',
            'server_id' => 'Server ID',
            'login_method' => 'metode login',
            'payment_method' => 'metode pembayaran',
            'notes' => 'catatan',
            'stars' => 'jumlah bintang',
        ];
    }
}
