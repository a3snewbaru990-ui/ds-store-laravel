<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('is_admin', false)->withCount('orders')->latest();

        if ($request->filled('q')) {
            $search = trim($request->string('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        return view('admin.customers.index', [
            'customers' => $query->paginate(12)->withQueryString(),
        ]);
    }
}
