<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Santri - Pesantren Darussalam Al-Qur'ani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .profile-header { background: linear-gradient(135deg, #6a1b9a 0%, #8e24aa 100%); color: white; padding: 60px 0 100px 0; position: relative; }
        .profile-card { margin-top: -80px; border: none; border-radius: 15px; }
        .nav-tabs-custom .nav-link { border: none; color: #666; font-weight: 600; padding: 15px 20px; }
        .nav-tabs-custom .nav-link.active { color: #6a1b9a; border-bottom: 3px solid #6a1b9a; background: none; }
        .info-label { color: #888; font-size: 0.85rem; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: #333; }
        .section-title { color: #6a1b9a; font-weight: bold; border-left: 4px solid #6a1b9a; padding-left: 10px; margin-bottom: 20px; }
        .empty-data { color: #ccc; font-style: italic; font-weight: normal; }
        .lang-dropdown-profile { position: absolute; top: 20px; right: 20px; z-index: 105; }
    </style>
</head>
<body>

<div class="profile-header text-center">
    <div class="lang-dropdown-profile">
        <button class="btn btn-sm btn-light dropdown-toggle py-1 px-3 shadow-sm rounded-pill" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
            <i class="bi bi-translate me-1"></i> {{ session()->get('locale') == 'en' ? 'English' : 'Indonesia' }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
            <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇲🇩 Indonesia</a></li>
            <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
        </ul>
    </div>

    <div class="container">
        @if(request()->get('admin_nim'))
            <a href="{{ route('santri.database', ['role' => 'admin', 'nim' => request()->get('admin_nim')]) }}" class="btn btn-sm btn-light mb-3 rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Database Santri
            </a>
        @else
            <a href="{{ route('dashboard', ['role' => $role, 'nim' => $santri->nim]) }}" class="btn btn-sm btn-light mb-3 rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        @endif
        
        <h2 class="fw-bold">{{ __('messages.profile_title') }}</h2>
        <p class="opacity-75">{{ __('messages.profile_system') }}</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card profile-card shadow-lg">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-custom px-4 border-bottom" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#biodata" type="button">{{ __('messages.tab_biodata') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#keluarga" type="button">{{ __('messages.tab_parents') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#alamat" type="button">{{ __('messages.tab_address') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="biodata" role="tabpanel">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-2 text-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->nama) }}&background=6a1b9a&color=fff&size=128" class="rounded-circle shadow" alt="Foto Profil">
                                </div>
                                <div class="col-md-10">
                                    <h4 class="fw-bold mb-1">{{ strtoupper($santri->nama) }}</h4>
                                    <p class="text-muted mb-0">Nomor Induk Santri: {{ $santri->nim }}</p>
                                </div>
                            </div>
                            
                            <h6 class="section-title">{{ __('messages.info_personal') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <p class="info-label">{{ __('messages.info_pob_dob') }}</p>
                                    <p class="info-value">{{ $santri->nim == '5829' ? 'BEKASI, 26-11-2004' : '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label">{{ __('messages.info_gender') }}</p>
                                    <p class="info-value">{{ $santri->nim == '5829' ? (__('messages.info_gender_male')) : '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label">{{ __('messages.info_religion') }}</p>
                                    <p class="info-value">Islam</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label">{{ __('messages.info_school') }}</p>
                                    <p class="info-value">{{ $santri->nim == '5829' ? 'SMA DARUSSALAM KANDANGHAUR' : '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label">{{ __('messages.info_ustadz') }}</p>
                                    <p class="info-value text-primary">{{ $santri->nim == '5829' ? 'Ust. Ahmad Fauzi, S.Pd.I' : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="keluarga" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="section-title"><i class="bi bi-person-fill me-2"></i>{{ __('messages.father_data') }}</h6>
                                    <div class="mb-3">
                                        <p class="info-label">{{ __('messages.full_name') }}</p>
                                        <p class="info-value">{{ $santri->nim == '5829' ? 'Wahyu Setiawan' : '-' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="info-label">{{ __('messages.occupation') }}</p>
                                        <p class="info-value">{{ $santri->nim == '5829' ? (__('messages.private_employee')) : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="section-title"><i class="bi bi-person-heart me-2"></i>{{ __('messages.mother_data') }}</h6>
                                    <div class="mb-3">
                                        <p class="info-label">{{ __('messages.full_name') }}</p>
                                        <p class="info-value">{{ $santri->nim == '5829' ? 'Siti Maemunah' : '-' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="info-label">{{ __('messages.occupation') }}</p>
                                        <p class="info-value">{{ $santri->nim == '5829' ? (__('messages.housewife')) : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="alamat" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="section-title">{{ __('messages.origin_address') }}</h6>
                                    @if($santri->nim == '5829')
                                        <p class="info-value">Citra Villa Blok 9 No. 25</p>
                                        <p class="mb-1 small text-muted">Kab. Bekasi, Jawa Barat - 17510</p>
                                    @else
                                        <p class="empty-data">Data alamat belum dilengkapi.</p>
                                    @endif
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <h6 class="section-title">{{ __('messages.parent_contact') }}</h6>
                                    <div class="mb-3">
                                        <p class="info-label">{{ __('messages.wa_number') }}</p>
                                        <p class="info-value">{{ $santri->nim == '5829' ? '085215268510' : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light p-4 text-center border-0 rounded-bottom-4">
                        <div class="alert alert-warning d-inline-block small mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {!! __('messages.profile_alert') !!}
                        </div>
                        <br>
                        <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>{{ __('messages.btn_request_change') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>