<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Transaksi - {{ $santri->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Darussalam Digital</a>
            <div class="navbar-nav align-items-center gap-2">
                <a class="nav-link" href="{{ route('dashboard', ['nim' => $santri->nim, 'role' => $role]) }}">{{ __('messages.menu_dashboard') }}</a>
                <a class="nav-link active" href="#">{{ __('messages.menu_riwayat') }}</a>
                
                <div class="dropdown ms-2">
                    <button class="btn btn-sm btn-light dropdown-toggle py-1 px-3 rounded-pill" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
                        <i class="bi bi-translate me-1"></i> {{ session()->get('locale') == 'en' ? 'English' : 'Indonesia' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
                        <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
                        <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                    </ul>
                </div>
                
                <a class="nav-link text-warning fw-bold ms-3" href="{{ route('login.page') }}">{{ __('messages.menu_logout') }}</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow border-0 p-4" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">{{ __('messages.last_activity') }}</h4>
                <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Santri: {{ $santri->nama }} ({{ $santri->nim }})</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mt-3 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('messages.th_time') }}</th>
                            <th>{{ __('messages.th_product') }}</th>
                            <th>{{ __('messages.th_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaTransaksi as $t)
                        <tr>
                            <td class="text-muted">{{ $t->created_at->locale(session()->get('locale', 'id'))->format('d M Y, H:i') }}</td>
                            <td class="fw-bold">{{ $t->produk }}</td>
                            <td class="fw-bold text-danger">Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                Belum ada riwayat belanja untuk santri ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}" class="btn btn-secondary px-4 rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>