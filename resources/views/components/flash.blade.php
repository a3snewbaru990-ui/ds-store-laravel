@if (session('success'))
    <div class="alert alert--success" role="status">
        <x-icon name="check" />
        <p>{{ session('success') }}</p>
        <button type="button" class="alert__close" data-dismiss-alert aria-label="Tutup notifikasi">
            <x-icon name="close" />
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert--danger" role="alert">
        <x-icon name="alert" />
        <p>{{ session('error') }}</p>
        <button type="button" class="alert__close" data-dismiss-alert aria-label="Tutup notifikasi">
            <x-icon name="close" />
        </button>
    </div>
@endif

@if ($errors->any() && ! isset($hideErrorSummary))
    <div class="alert alert--danger" role="alert">
        <x-icon name="alert" />
        <div>
            <p>Ada data yang perlu dicek.</p>
            <ul class="alert__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
