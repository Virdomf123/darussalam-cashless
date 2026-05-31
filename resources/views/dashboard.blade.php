<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pesantren Darussalam Al-Qur'ani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; overflow-x: hidden; }
        /* Sidebar Styling */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #ddd; padding-top: 20px; z-index: 100; }
        .sidebar .nav-link { color: #333; padding: 12px 20px; border-radius: 0; margin-bottom: 5px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f0f7ff; color: #0d6efd; border-right: 4px solid #0d6efd; }
        /* Main Content Styling */
        .main-content { margin-left: 260px; padding: 25px; transition: all 0.3s; }
        .header-top { background-color: #6a1b9a; color: white; padding: 12px 20px; margin-bottom: 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(106, 27, 154, 0.2); }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .brand-text { font-size: 0.85rem; line-height: 1.2; }
        /* Badge Status Custom */
        .status-indicator { font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; background: rgba(255,255,255,0.9); display: inline-block; margin-top: 5px; }
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
            <span class="badge rounded-pill {{ $role == 'admin' ? 'bg-warning text-dark' : ($role == 'kasir' ? 'bg-success' : 'bg-info') }} mb-2">
                {{ ucfirst($role) }} Mode
            </span>
            
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
        <a class="nav-link active" href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}">
            <i class="bi bi-grid-1x2-fill me-2"></i> {{ __('messages.menu_dashboard') }}
        </a>
        
        @if($role == 'admin')
            <a class="nav-link text-primary fw-bold" href="{{ route('topup', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-plus-square-fill me-2"></i> {{ __('messages.menu_topup') }}
            </a>
            <a class="nav-link" href="{{ route('santri.database', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-people-fill me-2"></i> {{ __('messages.menu_database') }}
            </a>
            <a class="nav-link" href="{{ route('laporan.keuangan', ['nim' => $santri->nim, 'role' => $role]) }}"><i class="bi bi-journal-text me-2"></i> {{ __('messages.menu_laporan') }}</a>
            <hr class="mx-3">
        @endif

        @if($role == 'admin' || $role == 'kasir')
            <a class="nav-link text-success fw-bold" href="{{ route('kantin.index', ['role' => $role, 'nim' => $santri->nim]) }}">
                <i class="bi bi-cart-plus-fill me-2"></i> {{ __('messages.menu_kantin') }}
            </a>
        @endif

        @if($role == 'parent')
            <a class="nav-link fw-bold" href="{{ route('chatbot.page', ['nim' => $santri->nim, 'role' => $role]) }}" style="color: #6a1b9a;">
                <i class="bi bi-chat-dots-fill me-2"></i> {{ __('messages.menu_chatbot') }}
            </a>
            <a class="nav-link" href="{{ route('riwayat', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-receipt me-2"></i> {{ __('messages.menu_riwayat') }}
            </a>
            <a class="nav-link" href="{{ route('tabungan', ['nim' => $santri->nim, 'role' => $role]) }}">
                <i class="bi bi-wallet2 me-2"></i> {{ __('messages.menu_tabungan') }}
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
        <span class="fw-bold"><i class="bi bi-display me-2"></i> {{ __('messages.dashboard_title') }} — {{ strtoupper($role) }}</span>
        <div class="d-flex align-items-center">
            <small class="me-3 d-none d-md-block">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
            <a href="{{ route('login.page') }}" class="btn btn-sm btn-light text-danger fw-bold">LOGOUT</a>
        </div>
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

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card h-100 p-4 text-center bg-white">
                <p class="text-muted small mb-1">{{ __('messages.card_digital') }}</p>
                <h5 class="fw-bold mb-3">{{ $santri->nim }}</h5>
                <div class="py-2">
                    <i class="bi bi-qr-code text-dark" style="font-size: 4.5rem;"></i>
                </div>
                <button class="btn btn-sm btn-outline-dark mt-3 rounded-pill">{{ __('messages.btn_print') }}</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100 p-4 text-center border-top border-primary border-5 bg-white">
                <p class="text-muted small mb-1">{{ strtoupper(__('messages.active_balance')) }}</p>
                <h2 class="text-primary fw-bold mb-2">Rp {{ number_format($santri->saldo, 0, ',', '.') }}</h2>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <span class="badge bg-success">Status: Aktif</span>
                    <span class="badge bg-light text-dark border">Limit Harian: Aman</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100 p-4 bg-white">
                <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-megaphone text-warning me-2"></i> {{ __('messages.announcement') }}</h6>
                <div class="small">
                    <p class="mb-2"><strong>Info:</strong> Pembayaran SPP bulan Mei telah dibuka melalui sistem Cashless.</p>
                    <p class="text-muted mb-0"><em>Semester Genap TA 2025/2026</em></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card shadow-sm border-0 bg-white mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-line text-primary me-2"></i>{{ __('messages.chart_title') }}</h5>
            <div style="height: 300px;">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card stat-card shadow-sm border-0 bg-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">{{ __('messages.last_activity') }}</h5>
                <a href="{{ route('riwayat', ['nim' => $santri->nim, 'role' => $role]) }}" class="btn btn-sm btn-link text-decoration-none">{{ __('messages.btn_see_all') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_product') }}</th>
                            <th>{{ __('messages.th_price') }}</th>
                            <th>{{ __('messages.th_time') }}</th>
                            <th>{{ __('messages.th_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-2 rounded me-3"><i class="bi bi-cart-check text-primary"></i></div>
                                    <span class="fw-bold">{{ $t->produk }}</span>
                                </div>
                            </td>
                            <td class="text-danger fw-bold">- Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                            <td><i class="bi bi-calendar3 me-2 text-muted"></i>{{ $t->created_at->format('d M Y, H:i') }}</td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle">Selesai</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi terakhir.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($role == 'parent')
    <div class="card stat-card mt-4 border-0 shadow" style="background: linear-gradient(135deg, #6a1b9a 0%, #8e24aa 100%); color: white;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h3 class="fw-bold"><i class="bi bi-robot me-2"></i> {{ __('messages.ai_assistant_title') }}</h3>
                    <p class="opacity-75">{{ __('messages.ai_assistant_desc') }}</p>
                    
                    @if($aiResult)
                        <div class="bg-white text-dark p-3 rounded-3 mb-3 shadow-sm border-start border-warning border-5">
                            <i class="bi bi-quote fs-4 text-warning"></i>
                            <p class="mb-0 text-dark">{{ $aiResult }}</p>
                        </div>
                        <a href="{{ route('dashboard', ['role' => 'parent', 'nim' => $santri->nim]) }}" class="btn btn-light btn-sm fw-bold">{{ __('messages.btn_analyze_again') }}</a>
                    @else
                        <form action="{{ route('dashboard') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="parent">
                            <input type="hidden" name="nim" value="{{ $santri->nim }}">
                            <button type="submit" name="tanya_ai" value="1" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="bi bi-lightning-charge-fill me-1"></i> {{ __('messages.btn_analyze_now') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background-color: #fff;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-graph-up-arrow text-success me-2"></i> 
                        {{ __('messages.syariah_corner_title') }}
                    </h6>
                    <div class="d-flex align-items-start bg-light p-3 rounded-3">
                        <div class="me-3">
                            <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <p class="small mb-0 text-muted" style="white-space: pre-line;">
                                @if($aiInvestasi)
                                    {!! nl2br(e($aiInvestasi)) !!}
                                @else
                                    {{ __('messages.syariah_corner_default') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('messages.badge_safe') }}</span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle">{{ __('messages.badge_syariah') }}</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ __('messages.badge_low_risk') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card stat-card border-0 shadow-sm" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); color: white; border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-rocket-takeoff me-2 text-warning"></i> 
                            {{ __('messages.simulation_title') }} {{ $santri->nama }}
                        </h6>
                        <span class="badge bg-warning text-dark small">Smart Prediction</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-25 h-100">
                                <p class="small mb-1 opacity-75">{{ __('messages.sim_est_pocket') }}</p>
                                <h5 class="fw-bold mb-0 text-warning">Rp {{ number_format($estimasiSisaHarian, 0, ',', '.') }}</h5>
                                
                                <div class="status-indicator">
                                    <span class="{{ $warnaStatus ?? 'text-dark' }} fw-bold">
                                        <i class="bi bi-activity me-1"></i> {{ $statusHemat ?? 'Menghitung...' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-25 h-100">
                                <p class="small mb-1 opacity-75">{{ __('messages.sim_pred_3years') }}</p>
                                <h5 class="fw-bold mb-0 text-white">Rp {{ number_format($prediksiTabungan, 0, ',', '.') }}</h5>
                                <small class="opacity-50 text-white" style="font-size: 0.7rem;">{{ __('messages.sim_pred_note') }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-25 h-100">
                                <p class="small mb-1 opacity-75">{{ __('messages.sim_gold_convert') }}</p>
                                <h5 class="fw-bold mb-0" style="color: #deff9a;">± Rp {{ number_format($potensiEmas, 0, ',', '.') }}</h5>
                                <small class="opacity-50" style="font-size: 0.7rem;">{{ __('messages.sim_gold_note') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-black bg-opacity-25 rounded-3 border-start border-warning border-4">
                        <p class="small mb-0">
                            <i class="bi bi-info-circle me-1 text-warning"></i>
                            {{ __('messages.sim_footer_info', ['name' => $santri->nama]) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            // Label Hari Grafik Dinamis Mengikuti Sesi Bahasa yang Sedang Aktif
            labels: @json(collect(range(6, 0))->map(fn($i) => now()->subDays($i)->locale(session()->get('locale', 'id'))->isoFormat('dddd'))->toArray()),
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: @json($grafikPengeluaran),
                borderColor: '#6a1b9a',
                backgroundColor: 'rgba(106, 27, 154, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#6a1b9a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
</script>
</body>
</html>