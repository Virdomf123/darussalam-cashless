<!DOCTYPE html>
<html>
<head>
    <title>Top Up Saldo - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .card { border-radius: 15px; border: none; }
        .card-header { border-radius: 15px 15px 0 0 !important; }
        .lang-dropdown-topup { position: absolute; top: 15px; right: 20px; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px; position: relative;">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark py-3" style="position: relative;">
                <h5 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2"></i>{{ __('messages.topup_title') }}</h5>
                
                <div class="lang-dropdown-topup">
                    <button class="btn btn-xs btn-outline-dark dropdown-toggle py-0 px-2" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                         {{ session()->get('locale') == 'en' ? 'EN' : 'ID' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
                        <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
                        <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    </div>
                @endif

                @if(!request()->has('admin_nim') || request()->get('nim') == request()->get('admin_nim') || request()->has('cari_santri'))
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="bi bi-search me-2"></i> {{ __('messages.topup_admin_mode') }}
                    </div>

                    <form action="{{ route('topup') }}" method="GET">
                        <input type="hidden" name="role" value="admin">
                        <input type="hidden" name="admin_nim" value="{{ request()->get('admin_nim', request()->get('nim', '5829')) }}">
                        <input type="hidden" name="proses_target" value="true">

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('messages.topup_target_label') }}</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-person-vcard"></i></span>
                                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Target (Contoh: 1234)" required>
                            </div>
                            <div class="form-text">{{ __('messages.topup_target_help') }}</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mb-2">
                            <i class="bi bi-arrow-right-circle me-1"></i> {{ __('messages.btn_continue_nominal') }}
                        </button>
                        
                        <a href="{{ route('santri.database', ['role' => 'admin', 'nim' => request()->get('admin_nim', request()->get('nim', '5829'))]) }}" class="btn btn-outline-secondary w-100 border-0">
                            {{ __('messages.btn_cancel_db') }}
                        </a>
                    </form>

                @else
                    <div class="alert alert-info border-0 shadow-sm">
                        <p class="mb-1 text-muted small">{{ __('messages.topup_for') }}</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $santri->nama }} ({{ $santri->nim }})</h5>
                    </div>
                    
                    <p class="text-center my-3">{{ __('messages.topup_current_balance') }} <br>
                        <span class="fs-4 fw-bold text-success">Rp {{ number_format($santri->saldo, 0, ',', '.') }}</span>
                    </p>
                    
                    <form action="{{ route('saldo.update') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="admin_nim" value="{{ request()->get('admin_nim', '5829') }}">
                        <input type="hidden" name="nim" value="{{ $santri->nim }}">
                        <input type="hidden" name="role" value="{{ $role }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.topup_destination') }}</label>
                            <select name="jenis_saldo" class="form-select shadow-sm" required>
                                <option value="aktif">{{ __('messages.topup_dest_active') }}</option>
                                <option value="tabungan">{{ __('messages.topup_dest_saving') }}</option>
                            </select>
                            <div class="form-text small text-muted mt-1">{{ __('messages.topup_dest_help') }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('messages.topup_amount') }}</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0">Rp</span>
                                <input type="number" name="jumlah" class="form-control border-start-0 ps-0" placeholder="Contoh: 50000" required min="1000">
                            </div>
                            <div class="form-text">{{ __('messages.topup_min_help') }}</div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm mb-2">
                            <i class="bi bi-check-circle me-1"></i> {{ __('messages.btn_confirm_topup') }}
                        </button>
                        
                        <a href="{{ route('topup', ['role' => 'admin', 'nim' => request()->get('admin_nim'), 'cari_santri' => 'true']) }}" class="btn btn-outline-warning w-100 fw-bold py-2 shadow-sm mb-2 text-dark">
                            <i class="bi bi-arrow-clockwise dash me-1"></i> {{ __('messages.btn_change_target') }}
                        </a>

                        <a href="{{ route('santri.database', ['role' => 'admin', 'nim' => request()->get('admin_nim', '5829')]) }}" class="btn btn-outline-secondary w-100 border-0">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('messages.btn_cancel_db') }}
                        </a>
                    </form>
                @endif
                
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>