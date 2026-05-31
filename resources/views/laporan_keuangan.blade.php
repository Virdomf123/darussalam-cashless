<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Master - Pesantren Darussalam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; overflow-x: hidden; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #ddd; padding-top: 20px; z-index: 100; }
        .sidebar .nav-link { color: #333; padding: 12px 20px; border-radius: 0; margin-bottom: 5px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f0f7ff; color: #0d6efd; border-right: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 25px; transition: all 0.3s; }
        .header-top { background-color: #6a1b9a; color: white; padding: 12px 20px; margin-bottom: 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(106, 27, 154, 0.2); }
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .brand-text { font-size: 0.85rem; line-height: 1.2; }
    </style>
</head>
<body>

<div class="sidebar shadow-sm">
    <div class="text-center mb-4 px-3">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="bi bi-book-half text-primary fs-3 me-2"></i>
            <div class="text-start brand-text">
                <span class="fw-bold d-block text-dark">PESANTREN</span>
                <span class="fw-bold text-primary">DARUSSALAM AL-QUR'ANI</span>
            </div>
        </div>
        <hr>
        <div class="mt-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->nama) }}&background=6a1b9a&color=fff" class="rounded-circle mb-2 shadow-sm" width="75">
            <p class="mb-0 fw-bold text-dark">{{ $santri->nama }}</p>
            <span class="badge rounded-pill bg-warning text-dark mb-2">{{ ucfirst($role) }} Mode</span>
            
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
        <a class="nav-link" href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}">
            <i class="bi bi-grid-1x2-fill me-2"></i> {{ __('messages.menu_dashboard') }}
        </a>
        
        @if($role == 'admin')
            <a class="nav-link text-primary fw-bold" href="{{ route('topup', ['nim' => $santri->nim, 'role' => $role, 'admin_nim' => $santri->nim]) }}">
                <i class="bi bi-plus-square-fill me-2"></i> {{ __('messages.menu_topup') }}
            </a>
            <a class="nav-link" href="{{ route('santri.database', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-people-fill me-2"></i> {{ __('messages.menu_database') }}
            </a>
            <a class="nav-link active" href="{{ route('laporan.keuangan', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-journal-text me-2"></i> {{ __('messages.menu_laporan') }}
            </a>
            <hr class="mx-3">
        @endif

        @if($role == 'admin' || $role == 'kasir')
            <a class="nav-link text-success fw-bold" href="{{ route('kantin.index', ['role' => $role, 'nim' => $santri->nim]) }}">
                <i class="bi bi-cart-plus-fill me-2"></i> {{ __('messages.menu_kantin') }}
            </a>
        @endif
        
        <a class="nav-link" href="{{ route('profil', ['nim' => $santri->nim, 'role' => $role]) }}">
            <i class="bi bi-person-circle me-2"></i> {{ __('messages.menu_profil') }}
        </a>
        
        <hr class="mx-3">
        <a class="nav-link text-danger" href="{{ route('login.page') }}"><i class="bi bi-power me-2"></i> {{ __('messages.menu_logout') }}</a>
    </nav>
</div>

<div class="main-content">
    <div class="header-top shadow">
        <span class="fw-bold"><i class="bi bi-calculator me-2"></i> {{ __('messages.laporan_title') }}</span>
        <small class="me-3 d-none d-md-block">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">{{ strtoupper(__('messages.active_balance')) }}</p>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($totalSaldoBeredar, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-light-primary p-2 rounded text-primary fs-3"><i class="bi bi-wallet2"></i></div>
                </div>
                <small class="text-muted mt-2 d-block">Dana aktif di dompet santri</small>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">{{ strtoupper(__('messages.total_savings')) }}</p>
                        <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalTabunganSantri, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-light-success p-2 rounded text-success fs-3"><i class="bi bi-piggy-bank"></i></div>
                </div>
                <small class="text-muted mt-2 d-block">Simpanan impian jangka panjang</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">{{ strtoupper(__('messages.today_omset')) }}</p>
                        <h4 class="fw-bold text-warning mb-0">Rp {{ number_format($omsetKantinHariIni, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-light-warning p-2 rounded text-warning fs-3"><i class="bi bi-graph-up-arrow"></i></div>
                </div>
                <small class="text-muted mt-2 d-block">Total belanja kantin hari ini</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 fw-bold">{{ strtoupper(__('messages.total_transactions')) }}</p>
                        <h4 class="fw-bold text-info mb-0">{{ $totalTransaksiKantin }} Kali</h4>
                    </div>
                    <div class="bg-light-info p-2 rounded text-info fs-3"><i class="bi bi-receipt-cutoff"></i></div>
                </div>
                <small class="text-muted mt-2 d-block">Akumulasi nota belanja kasir</small>
            </div>
        </div>
    </div>

    <div class="card stat-card bg-white p-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-list-columns-reverse text-primary me-2"></i>{{ __('messages.jurnal_title') }}</h5>
        <p class="text-muted small mb-4">Berikut adalah daftar rekaman audit log finansial seluruh santri secara berurutan:</p>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle border-top">
                <thead class="table-light">
                    <tr>
                        <th>TANGGAL & WAKTU</th>
                        <th>NIM</th>
                        <th>KATEGORI</th>
                        <th>KETERANGAN PRODUK</th>
                        <th class="text-end">NOMINAL NOMER</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semuaTransaksi as $t)
                    <tr>
                        <td class="text-muted small">{{ $t->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td class="fw-bold text-secondary">{{ $t->nim }}</td>
                        <td>
                            <span class="badge bg-opacity-10 bg-danger text-danger border border-danger border-opacity-20 px-2 py-1" style="font-size: 0.75rem;">
                                {{ $t->kategori }}
                            </span>
                        </td>
                        <td class="fw-bold text-dark">{{ $t->produk }}</td>
                        <td class="text-end text-danger fw-bold">- Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-40"></i>
                            Belum ada riwayat transaksi komersial di database jurnal.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>