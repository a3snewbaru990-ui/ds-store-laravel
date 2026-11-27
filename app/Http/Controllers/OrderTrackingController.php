<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(): View
    {
        return view('track.index', [
            'order' => null,
            'searched' => false,
        ]);
    }

    public function track(Request $request): View
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:30'],
            'whatsapp' => ['required', 'string', 'max:20'],
        ], [
            'order_code.required' => 'Kode pesanan wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $whatsapp = preg_replace('/[^0-9+]/', '', $validated['whatsapp']) ?? '';
        if (str_starts_with($whatsapp, '+62')) {
            $whatsapp = substr($whatsapp, 1);
        } elseif (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        }

        $order = Order::with(['package', 'events'])
            ->where('order_code', strtoupper(trim($validated['order_code'])))
            ->where('whatsapp', $whatsapp)
            ->first();

        if (! $order) {
            throw ValidationException::withMessages([
                'order_code' => 'Pesanan tidak ditemukan. Cek kembali kode pesanan dan nomor WhatsApp.',
            ]);
        }

        return view('track.index', [
            'order' => $order,
            'searched' => true,
            'whatsapp' => $validated['whatsapp'],
        ]);
    }
}
