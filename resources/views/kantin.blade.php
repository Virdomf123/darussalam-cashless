<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Kantin - Pesantren Darussalam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('dashboard', ['role' => request('role'), 'nim' => request('nim')]) }}" class="btn btn-secondary rounded-pill mb-0">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle py-1 px-3 shadow-sm rounded-pill bg-white" type="button" data-bs-toggle="dropdown" style="font-size: 0.9rem;">
                    <i class="bi bi-translate me-1"></i> {{ session()->get('locale') == 'en' ? 'English' : 'Indonesia' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
                    <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
                    <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                </ul>
            </div>
        </div>

        <div class="card shadow border-0 overflow-hidden" style="border-radius: 20px;">
            <div class="card-header bg-success text-white p-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-cart4 me-2"></i>{{ __('messages.menu_kantin') }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kantin.index') }}" method="GET" class="mb-4">
                    <input type="hidden" name="nim" value="{{ request('nim') }}">
                    <input type="hidden" name="role" value="{{ request('role') }}">
                    
                    <label class="form-label fw-bold small">SCAN / INPUT NIM SANTRI</label>
                    <div class="input-group">
                        <input type="text" name="nim_cari" class="form-control form-control-lg" placeholder="Masukkan NIM Santri..." value="{{ request('nim_cari') }}" autofocus>
                        <button class="btn btn-success" type="submit"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </form>

                @if($santri)
                <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->nama) }}&background=0d6efd&color=fff" class="rounded-circle me-3" width="50">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $santri->nama }}</h6>
                        <small>{{ __('messages.active_balance') }}: <strong>Rp {{ number_format($santri->saldo, 0, ',', '.') }}</strong></small>
                    </div>
                </div>

                <form action="{{ route('kantin.bayar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="petugas_nim" value="{{ request('nim') }}">
                    <input type="hidden" name="petugas_role" value="{{ request('role') }}">
                    
                    <input type="hidden" name="nim_pembeli" value="{{ $santri->nim }}">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NAMA PRODUK</label>
                        <input type="text" name="produk" class="form-control" placeholder="Contoh: Paket Nasi Ayam" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">TOTAL HARGA (RP)</label>
                        <input type="number" name="harga" class="form-control form-control-lg text-danger fw-bold" placeholder="0" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-credit-card-2-back me-2"></i> PROSES PEMBAYARAN
                    </button>
                </form>
                @elseif(request('nim_cari'))
                    <div class="alert alert-danger text-center shadow-sm">Data santri dengan NIM {{ request('nim_cari') }} tidak ditemukan!</div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>