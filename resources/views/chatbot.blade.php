<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asisten Chatbot AI - Pesantren Darussalam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; overflow-x: hidden; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #ddd; padding-top: 20px; z-index: 100; }
        .sidebar .nav-link { color: #333; padding: 12px 20px; border-radius: 0; margin-bottom: 5px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f0f7ff; color: #0d6efd; border-right: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 25px; transition: all 0.3s; }
        .header-top { background-color: #6a1b9a; color: white; padding: 12px 20px; margin-bottom: 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(106, 27, 154, 0.2); }
        .chat-message-bubble { max-width: 80%; border-radius: 12px; padding: 12px 18px; shadow: 0 2px 4px rgba(0,0,0,0.02); }
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
            <span class="badge rounded-pill bg-info mb-2">{{ ucfirst($role) }} Mode</span>
            
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
        <a class="nav-link active" href="{{ route('chatbot.page', ['nim' => $santri->nim, 'role' => $role]) }}">
            <i class="bi bi-chat-dots-fill me-2"></i> {{ __('messages.menu_chatbot') }}
        </a>
        <a class="nav-link" href="{{ route('riwayat', ['nim' => $santri->nim, 'role' => $role]) }}">
            <i class="bi bi-receipt me-2"></i> {{ __('messages.menu_riwayat') }}
        </a>
        <a class="nav-link" href="{{ route('tabungan', ['nim' => $santri->nim, 'role' => $role]) }}">
            <i class="bi bi-wallet2 me-2"></i> {{ __('messages.menu_tabungan') }}
        </a>
        <a class="nav-link" href="{{ route('profil', ['nim' => $santri->nim, 'role' => $role]) }}">
            <i class="bi bi-person-circle me-2"></i> {{ __('messages.menu_profil') }}
        </a>
        <hr class="mx-3">
        <a class="nav-link text-danger" href="{{ route('login.page') }}"><i class="bi bi-power me-2"></i> {{ __('messages.menu_logout') }}</a>
    </nav>
</div>

<div class="main-content">
    <div class="header-top shadow">
        <span class="fw-bold"><i class="bi bi-chat-left-dots me-2"></i> {{ __('messages.menu_chatbot') }}</span>
        <small class="me-3 d-none d-md-block">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
    </div>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); color: white; border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <h5 class="fw-bold mb-0"><i class="bi bi-robot me-2"></i> {{ __('messages.chat_header') }}</h5>
            <small class="opacity-75">{{ __('messages.chat_desc') }}</small>
        </div>
        <div class="card-body p-4">
            
            <div class="mb-4 p-3 bg-light rounded-3 border" style="height: 400px; overflow-y: auto;">
                <div class="d-flex mb-3 align-items-start">
                    <div class="bg-primary text-white rounded-circle p-2 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="bg-white border text-dark chat-message-bubble shadow-sm">
                        <small class="fw-bold text-primary d-block mb-1">{{ __('messages.ai_assistant_title') }}</small>
                        <p class="small mb-0">{{ __('messages.chat_welcome_msg', ['name' => $santri->nama]) }}</p>
                    </div>
                </div>

                @if(session('chat_user'))
                <div class="d-flex mb-3 align-items-start justify-content-end">
                    <div class="bg-success text-white chat-message-bubble shadow-sm">
                        <small class="fw-bold text-white-50 d-block mb-1 text-end">{{ __('messages.chat_user_label') }}</small>
                        <p class="small mb-0 text-white">{{ session('chat_user') }}</p>
                    </div>
                    <div class="bg-success text-white rounded-circle p-2 ms-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                @endif

                @if(session('chat_ai'))
                <div class="d-flex mb-3 align-items-start">
                    <div class="bg-primary text-white rounded-circle p-2 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="bg-white border text-dark chat-message-bubble shadow-sm border-primary border-opacity-25">
                        <small class="fw-bold text-primary d-block mb-1">{{ __('messages.ai_assistant_title') }}</small>
                        <p class="small mb-0 text-dark" style="white-space: pre-line;">{{ session('chat_ai') }}</p>
                    </div>
                </div>
                @endif
            </div>

            <form action="{{ route('chatbot.send') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $santri->nim }}">
                <input type="hidden" name="role" value="{{ $role }}">
                
                <div class="input-group">
                    <input type="text" name="user_message" class="form-control border-primary border-opacity-50" placeholder="{{ __('messages.chat_placeholder') }}" required>
                    <button class="btn btn-primary px-4 fw-bold" type="submit">
                        <i class="bi bi-send-fill me-1"></i> {{ __('messages.btn_send_message') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>