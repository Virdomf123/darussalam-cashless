<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Santri - Pesantren Darussalam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; overflow-x: hidden; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #ddd; padding-top: 20px; z-index: 100; }
        .sidebar .nav-link { color: #333; padding: 12px 20px; border-radius: 0; margin-bottom: 5px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f0f7ff; color: #0d6efd; border-right: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 25px; transition: all 0.3s; }
        .header-top { background-color: #6a1b9a; color: white; padding: 12px 20px; margin-bottom: 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(106, 27, 154, 0.2); }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
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
            <a class="nav-link active" href="{{ route('santri.database', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-people-fill me-2"></i> {{ __('messages.menu_database') }}
            </a>
            <a class="nav-link" href="{{ route('laporan.keuangan', ['nim' => $santri->nim, 'role' => $role]) }}">
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
        <span class="fw-bold"><i class="bi bi-people me-2"></i> {{ __('messages.database_title') }}</span>
        <small class="me-3 d-none d-md-block">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card stat-card shadow-sm border-0 bg-white p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Rekapitulasi Santri</h5>
                <p class="text-muted small mb-0">Total terdaftar: <span class="fw-bold text-dark">{{ $allSantri->count() }} Santri</span></p>
            </div>
            
            <div class="d-flex gap-2 flex-grow-1 justify-content-md-end" style="max-width: 650px; width: 100%;">
                <a href="{{ route('santri.tambah.form', ['role' => $role, 'admin_nim' => $santri->nim]) }}" class="btn btn-success fw-bold px-3 d-flex align-items-center">
                    <i class="bi bi-person-plus-fill me-2"></i> Tambah Santri
                </a>

                <form action="{{ route('santri.database') }}" method="GET" class="d-flex gap-2 flex-grow-1 mb-0">
                    <input type="hidden" name="role" value="{{ $role }}">
                    <input type="hidden" name="nim" value="{{ $santri->nim }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIM santri..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @if($search)
                        <a href="{{ route('santri.database', ['role' => $role, 'nim' => $santri->nim]) }}" class="btn btn-secondary" title="Reset Pencarian">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border-top">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">FOTO</th>
                        <th>NIM</th>
                        <th>NAMA LENGKAP</th>
                        <th>{{ strtoupper(__('messages.active_balance')) }}</th>
                        <th>{{ strtoupper(__('messages.total_savings')) }}</th>
                        <th class="text-center" style="width: 260px;">AKSI ADMINISTRASI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allSantri as $s)
                    <tr>
                        <td>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama) }}&background=random&color=fff" class="rounded-circle border" width="45" height="45">
                        </td>
                        <td class="fw-bold text-secondary">{{ $s->nim }}</td>
                        <td>
                            <span class="fw-bold text-dark d-block">{{ $s->nama }}</span>
                            <small class="badge bg-light text-muted border" style="font-size: 0.7rem;">Santri Darussalam</small>
                        </td>
                        <td class="text-primary fw-bold">Rp {{ number_format($s->saldo, 0, ',', '.') }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($s->tabungan, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('topup', ['nim' => $s->nim, 'role' => $role, 'admin_nim' => $santri->nim]) }}" class="btn btn-sm btn-outline-primary" title="Top Up Saldo">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Top Up
                                </a>
                                <a href="{{ route('profil', ['nim' => $s->nim, 'role' => $role, 'admin_nim' => $santri->nim]) }}" class="btn btn-sm btn-outline-dark" title="Lihat Profil">
                                    <i class="bi bi-eye-fill"></i> Detail
                                </a>

                                @if($s->nim != $santri->nim)
                                    <form action="{{ route('santri.hapus', ['target_nim' => $s->nim]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data master santri bernama {{ $s->nama }} secara permanen dari sistem?');" class="d-inline mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="admin_nim" value="{{ $santri->nim }}">
                                        <input type="hidden" name="role" value="{{ $role }}">
                                        
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Santri">
                                            <i class="bi bi-trash3-fill"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                            Data santri tidak ditemukan atau belum ada dalam master database.
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