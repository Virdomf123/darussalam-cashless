<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pesantren Darussalam Al-Qur'ani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { margin-top: 100px; max-width: 400px; position: relative; }
        .card { border-radius: 15px; border: none; }
        .btn-primary { background-color: #6a1b9a; border: none; }
        .btn-primary:hover { background-color: #4a148c; }
        .lang-dropdown-login { position: absolute; top: 15px; right: 15px; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center" style="position: relative;">
        <div class="login-container card shadow p-4 w-100">
            
            <div class="lang-dropdown-login">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0 px-2 text-muted" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                    🌐 {{ session()->get('locale') == 'en' ? 'EN' : 'ID' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.85rem;">
                    <li><a class="dropdown-item" href="{{ route('set.language', 'id') }}">🇮🇩 Indonesia</a></li>
                    <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">🇬🇧 English</a></li>
                </ul>
            </div>

            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary">Darussalam Digital</h4>
                <p class="text-muted">{{ __('messages.login_welcome') }}</p>
            </div>
            
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.login_as') }}</label>
                    <select name="role" class="form-select">
                        <option value="parent">{{ __('messages.login_role_parent') }}</option>
                        <option value="admin">{{ __('messages.login_role_admin') }}</option>
                        <option value="kasir">{{ __('messages.login_role_cashier') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label id="nim" class="form-label fw-bold">{{ __('messages.login_nim_id') }}</label>
                    <input type="text" name="nim" class="form-control" id="nim" placeholder="Masukkan NIM (Contoh: 1234)" required>
                    <div class="form-text">{{ __('messages.login_nim_help') }}</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">{{ __('messages.login_password') }}</label>
                    <input type="password" class="form-control" id="password" placeholder="********">
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">{{ __('messages.btn_login_dashboard') }}</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>