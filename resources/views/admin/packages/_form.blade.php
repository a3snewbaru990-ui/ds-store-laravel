@php
    $isEdit = $package->exists;
    $action = $isEdit ? route('admin.packages.update', $package) : route('admin.packages.store');
@endphp

<form action="{{ $action }}" method="POST" class="package-form" data-package-form>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="package-form__main">
        <section class="form-panel">
            <div class="form-panel__head">
                <span class="form-panel__step"><x-icon name="package" /></span>
                <div>
                    <h2>Informasi paket</h2>
                    <p>Nama, rank, dan deskripsi yang tampil di halaman publik.</p>
                </div>
            </div>

            <div class="form-panel__body">
                <div class="field-grid">
                    <div class="field">
                        <label for="name">Nama paket</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        <x-input-error name="name" />
                    </div>
                    <div class="field">
                        <label for="short_name">Nama pendek</label>
                        <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $package->short_name) }}" maxlength="30" required @error('short_name') aria-invalid="true" aria-describedby="short_name-error" @enderror>
                        <x-input-error name="short_name" />
                    </div>
                </div>

                <div class="field-grid">
                    <div class="field">
                        <label for="slug">Slug URL</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $package->slug) }}" placeholder="otomatis-dari-nama" aria-describedby="slug-help @error('slug') slug-error @enderror" @error('slug') aria-invalid="true" @enderror>
                        <p class="field-help" id="slug-help">Kosongkan untuk otomatis dari nama paket.</p>
                        <x-input-error name="slug" />
                    </div>
                    <div class="field">
                        <label for="rank_key">Rank</label>
                        <select id="rank_key" name="rank_key" required data-rank-select>
                            @foreach ($rankOptions as $key => $label)
                                <option value="{{ $key }}" data-image="{{ asset('images/rank-' . $key . '.webp') }}" @selected(old('rank_key', $package->rank_key) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error name="rank_key" />
                    </div>
                </div>

                <div class="rank-preview">
                    <img src="{{ $package->emblem_url }}" alt="" width="64" height="64" data-rank-preview>
                    <p>Emblem mengikuti rank yang dipilih. File gambar tersedia di folder <code>public/images</code>.</p>
                </div>

                <div class="field">
                    <label for="tagline">Tagline singkat</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $package->tagline) }}" maxlength="100" placeholder="Contoh: Masuk zona Mythic">
                    <x-input-error name="tagline" />
                </div>

                <div class="field">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" maxlength="1000">{{ old('description', $package->description) }}</textarea>
                    <x-input-error name="description" />
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-panel__head">
                <span class="form-panel__step"><x-icon name="wallet" /></span>
                <div>
                    <h2>Harga dan pengaturan</h2>
                    <p>Harga disimpan sebagai angka rupiah tanpa titik.</p>
                </div>
            </div>

            <div class="form-panel__body">
                <div class="field-grid">
                    <div class="field">
                        <label for="price">Harga</label>
                        <div class="input-prefix">
                            <span>Rp</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}" min="1000" max="10000000" step="500" required @error('price') aria-invalid="true" aria-describedby="price-error" @enderror>
                        </div>
                        <x-input-error name="price" />
                    </div>
                    <div class="field">
                        <label for="sort_order">Urutan tampil</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 1) }}" min="0" max="99" required>
                        <x-input-error name="sort_order" />
                    </div>
                </div>

                <fieldset class="field field--fieldset">
                    <legend>Satuan harga</legend>
                    <div class="choice-grid">
                        <label class="choice-card">
                            <input type="radio" name="unit" value="star" @checked(old('unit', $package->unit ?? 'star') === 'star') required>
                            <span>Per bintang</span>
                        </label>
                        <label class="choice-card">
                            <input type="radio" name="unit" value="package" @checked(old('unit', $package->unit) === 'package') required>
                            <span>Per paket</span>
                        </label>
                    </div>
                    <x-input-error name="unit" />
                </fieldset>

                <div class="field">
                    <label for="badge">Badge <span class="label-optional">(opsional)</span></label>
                    <input type="text" id="badge" name="badge" value="{{ old('badge', $package->badge) }}" maxlength="50" placeholder="Contoh: Paling dicari">
                    <x-input-error name="badge" />
                </div>

                <div class="switch-list">
                    <label class="switch-row">
                        <span>
                            <strong>Aktif di pricelist</strong>
                            <small>Pelanggan bisa melihat dan memesan paket ini.</small>
                        </span>
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true))>
                    </label>
                    <label class="switch-row">
                        <span>
                            <strong>Tandai unggulan</strong>
                            <small>Dipakai untuk highlight paket di halaman publik.</small>
                        </span>
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured))>
                    </label>
                </div>
            </div>
        </section>
    </div>

    <aside class="package-form__side">
        <div class="summary-card">
            <p class="eyebrow">{{ $isEdit ? 'Edit paket' : 'Paket baru' }}</p>
            <h2>{{ $isEdit ? $package->name : 'Paket joki' }}</h2>
            <p class="summary-note">Setelah disimpan, perubahan langsung terlihat di halaman pricelist.</p>
            <div class="form-actions form-actions--stack">
                <button type="submit" class="btn btn--primary btn--block">
                    {{ $isEdit ? 'Simpan perubahan' : 'Tambah paket' }}
                    <x-icon name="check" />
                </button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn--ghost btn--block">Batal</a>
            </div>
        </div>
    </aside>
</form>
