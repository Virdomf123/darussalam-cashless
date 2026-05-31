<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabungan Santri - Pesantren Darussalam Al-Qur'ani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .card { border-radius: 15px; }
        .progress { border-radius: 10px; background-color: #e9ecef; }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}" class="btn btn-sm btn-secondary rounded-pill px-3 mb-0">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-1 px-3 shadow-sm rounded-pill bg-white" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
                    <i class="bi bi-translate me-1"></i> {{ session()->get('locale') == 'en' ? 'English' : 'Indonesia' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
                    <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
                    <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card p-4 shadow-sm border-0 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-piggy-bank me-2"></i>{{ session()->get('locale') == 'en' ? 'Santri Savings Status' : 'Status Tabungan Santri' }}</h5>
                <span class="badge bg-light text-dark border">{{ $santri->nama }}</span>
            </div>
            
            <h2 class="fw-bold mb-1">Rp {{ number_format($tabungan->saldo_tabungan, 0, ',', '.') }}</h2>
            <p class="text-muted small mb-3">{{ session()->get('locale') == 'en' ? 'Target Achievement' : 'Target Pencapaian' }}: Rp {{ number_format($tabungan->target_tabungan, 0, ',', '.') }}</p>
            
            @php
                $persen = ($tabungan->saldo_tabungan / $tabungan->target_tabungan) * 100;
                $persen = $persen > 100 ? 100 : $persen;
            @endphp

            <div class="progress mb-2" style="height: 25px;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: {{ $persen }}%;" 
                     aria-valuenow="{{ $persen }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                     {{ number_format($persen, 1) }}% {{ session()->get('locale') == 'en' ? 'Collected' : 'Terkumpul' }}
                </div>
            </div>
            
            <p class="text-end small text-muted">
                {{ session()->get('locale') == 'en' ? 'Remaining Target' : 'Sisa Target' }}: 
                <span class="fw-bold text-danger">
                    Rp {{ number_format(max(0, $tabungan->target_tabungan - $tabungan->saldo_tabungan), 0, ',', '.') }}
                </span>
            </p>

            <hr>

            <h6 class="fw-bold mb-3">{{ session()->get('locale') == 'en' ? 'Recent Deposit History' : 'Riwayat Setoran Terakhir' }}</h6>
            <ul class="list-group list-group-flush">
                @foreach($tabungan->riwayat_setoran as $s)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <span class="d-block fw-bold small">{{ $s['ket'] }}</span>
                            <span class="text-muted small">{{ $s['tanggal'] }}</span>
                        </div>
                        <span class="fw-bold text-success">+ Rp {{ number_format($s['jumlah'], 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-send-fill me-2"></i>{{ session()->get('locale') == 'en' ? 'Transfer Deposit Confirmation' : 'Konfirmasi Setoran Transfer' }}</h6>
            </div>
            <div class="card-body p-4 bg-white">
                <p class="small text-muted mb-4">
                    {{ session()->get('locale') == 'en' ? 'Please fill out this form after making a transfer so that the boarding school treasurer can verify the balance immediately.' : 'Silakan isi form ini setelah Anda melakukan transfer agar bendahara pesantren dapat segera memverifikasi saldo.' }}
                </p>
                
                <form action="{{ route('konfirmasi.kirim') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="nim" value="{{ $santri->nim }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">{{ session()->get('locale') == 'en' ? 'Transfer Date' : 'Tanggal Transfer' }}</label>
                            <input type="date" name="tanggal_transfer" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">{{ session()->get('locale') == 'en' ? 'Transfer Amount' : 'Nominal Transfer' }} (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="Contoh: 500000" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ session()->get('locale') == 'en' ? 'Upload Proof of Transfer (Image)' : 'Unggah Bukti Transfer (Gambar)' }}</label>
                        <input type="file" name="bukti" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ session()->get('locale') == 'en' ? 'Additional Notes (Optional)' : 'Catatan Tambahan (Opsional)' }}</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Setoran untuk SPP Mei"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>{{ session()->get('locale') == 'en' ? 'Send Report to Admin' : 'Kirim Laporan ke Admin' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>