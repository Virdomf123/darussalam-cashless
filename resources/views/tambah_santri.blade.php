<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Santri Baru - Admin Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #ddd; padding-top: 20px; z-index: 100; }
        .sidebar .nav-link { color: #333; padding: 12px 20px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f0f7ff; color: #0d6efd; border-right: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 25px; }
        .form-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: white; }
        .header-top { background-color: #6a1b9a; color: white; padding: 12px 20px; margin-bottom: 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<div class="sidebar shadow-sm">
    <div class="text-center mb-4 px-3">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="bi bi-book-half text-primary fs-3 me-2"></i>
            <div class="text-start" style="font-size: 0.85rem; line-height: 1.2;">
                <span class="fw-bold d-block text-dark">PESANTREN</span>
                <span class="fw-bold text-primary">DARUSSALAM AL-QUR'ANI</span>
            </div>
        </div>
        <hr>
        <div class="mt-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->nama) }}&background=6a1b9a&color=fff" class="rounded-circle mb-2" width="75">
            <p class="mb-0 fw-bold">{{ $santri->nama }}</p>
            <span class="badge rounded-pill bg-warning text-dark mb-2">Admin Mode</span>
            
            <div class="dropdown mt-2 w-100 px-1">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 py-1" type="button" data-bs-toggle="dropdown" style="font-size: 0.8rem;">
                    <i class="bi bi-translate me-1"></i> {{ session()->get('locale') == 'en' ? 'English' : 'Indonesia' }}
                </button>
                <ul class="dropdown-menu shadow w-100" style="font-size: 0.85rem;">
                    <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
                    <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <nav class="nav flex-column mt-4">
        <a class="nav-link" href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}"><i class="bi bi-grid-1x2-fill me-2"></i> {{ __('messages.menu_dashboard') }}</a>
        <a class="nav-link" href="{{ route('topup', ['nim' => $santri->nim, 'role' => $role]) }}"><i class="bi bi-plus-square-fill me-2"></i> {{ __('messages.menu_topup') }}</a>
        <a class="nav-link active" href="{{ route('santri.database', ['nim' => $santri->nim, 'role' => $role]) }}"><i class="bi bi-people-fill me-2"></i> {{ __('messages.menu_database') }}</a>
        <hr class="mx-3">
        <a class="nav-link text-danger" href="{{ route('login.page') }}"><i class="bi bi-power me-2"></i> {{ __('messages.menu_logout') }}</a>
    </nav>
</div>

<div class="main-content">
    <div class="header-top shadow">
        <span class="fw-bold"><i class="bi bi-person-plus me-2"></i> {{ __('messages.add_santri_title') }}</span>
        <a href="{{ route('santri.database', ['role' => $role, 'nim' => $santri->nim]) }}" class="btn btn-sm btn-light fw-bold"><i class="bi bi-arrow-left me-1"></i> {{ __('messages.btn_back') }}</a>
    </div>

    <div class="card form-card p-4 col-md-8 mx-auto">
        <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>{{ __('messages.add_santri_form') }}</h5>
        
        <form action="{{ route('santri.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="admin_nim" value="{{ $santri->nim }}">
            <input type="hidden" name="role" value="{{ $role }}">

            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('messages.add_santri_new_nim') }}</label>
                <input type="text" name="nim_baru" class="form-control" placeholder="Contoh: 9988" required>
                @error('nim_baru') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('messages.add_santri_name') }}</label>
                <input type="text" name="nama_baru" class="form-control" placeholder="{{ __('messages.add_santri_placeholder_name') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('messages.add_santri_wallet') }}</label>
                    <input type="number" name="saldo_awal" class="form-control" value="0" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ __('messages.add_santri_saving') }}</label>
                    <input type="number" name="tabungan_awal" class="form-control" value="0" min="0" required>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success fw-bold px-4"><i class="bi bi-save2-fill me-1"></i> {{ __('messages.btn_register_santri') }}</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>