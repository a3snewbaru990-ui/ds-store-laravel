<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        return view('admin.packages.index', [
            'packages' => ServicePackage::withCount('orders')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.packages.create', [
            'package' => new ServicePackage(['unit' => 'star', 'is_active' => true, 'sort_order' => 1]),
            'rankOptions' => ServicePackage::RANK_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);
        $validated['emblem'] = 'images/rank-' . $validated['rank_key'] . '.webp';

        ServicePackage::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket baru berhasil ditambahkan.');
    }

    public function edit(ServicePackage $package): View
    {
        return view('admin.packages.edit', [
            'package' => $package,
            'rankOptions' => ServicePackage::RANK_OPTIONS,
        ]);
    }

    public function update(Request $request, ServicePackage $package): RedirectResponse
    {
        $validated = $this->validatePackage($request, $package);
        $validated['emblem'] = 'images/rank-' . $validated['rank_key'] . '.webp';

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket ' . $package->name . ' berhasil diperbarui.');
    }

    public function destroy(ServicePackage $package): RedirectResponse
    {
        if ($package->orders()->exists()) {
            return back()->with('error', 'Paket ini sudah punya riwayat pesanan. Nonaktifkan paket saja agar data lama tetap utuh.');
        }

        $package->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePackage(Request $request, ?ServicePackage $package = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'short_name' => ['required', 'string', 'max:30'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('service_packages', 'slug')->ignore($package)],
            'rank_key' => ['required', Rule::in(array_keys(ServicePackage::RANK_OPTIONS))],
            'price' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'unit' => ['required', Rule::in(['star', 'package'])],
            'badge' => ['nullable', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:99'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
