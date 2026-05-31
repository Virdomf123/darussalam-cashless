<?php

use App\Http\Controllers\PesantrenController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Login (Pintu Masuk Utama)
Route::get('/', function () {
    return view('login');
})->name('login.page');

// 2. Dashboard Utama (Mendukung GET untuk Login & POST untuk fitur AI analisa lama)
Route::match(['get', 'post'], '/dashboard', [PesantrenController::class, 'index'])->name('dashboard');

// =========================================================================
// MENU BARU: Halaman Chatbot Diskusi Terpisah
// =========================================================================
Route::get('/chatbot', [PesantrenController::class, 'chatbotPage'])->name('chatbot.page');
Route::post('/chatbot/send', [PesantrenController::class, 'chatWithAi'])->name('chatbot.send');
// =========================================================================

// 3. Fitur Kasir / Kantin (Bisa diakses Admin & Kasir)
Route::get('/kantin', [PesantrenController::class, 'kantin'])->name('kantin.index');
Route::post('/kantin/bayar', [PesantrenController::class, 'prosesBayar'])->name('kantin.bayar');

// 4. Fitur Top Up Saldo & Database Master (Khusus Admin Utama)
Route::get('/topup', [PesantrenController::class, 'topup'])->name('topup');
Route::post('/topup/update', [PesantrenController::class, 'updateSaldo'])->name('saldo.update');

// Tambahan Fitur Baru: Database & Pendaftaran Santri Baru
Route::get('/database-santri', [PesantrenController::class, 'databaseSantri'])->name('santri.database');
Route::get('/database-santri/tambah', [PesantrenController::class, 'tambahSantriForm'])->name('santri.tambah.form');
Route::post('/database-santri/simpan', [PesantrenController::class, 'simpanSantri'])->name('santri.simpan');

// FITUR BARU: Hapus Data Master Santri
Route::delete('/database-santri/hapus/{target_nim}', [PesantrenController::class, 'hapusSantri'])->name('santri.hapus');

// FITUR BARU: Laporan Keuangan Master Admin
Route::get('/laporan-keuangan', [PesantrenController::class, 'laporanKeuangan'])->name('laporan.keuangan');

// 5. Fitur Tabungan & Riwayat (Untuk Orang Tua / Santri)
Route::get('/tabungan', [PesantrenController::class, 'tabungan'])->name('tabungan');
Route::get('/riwayat', [PesantrenController::class, 'riwayat'])->name('riwayat');
Route::post('/konfirmasi-kirim', [PesantrenController::class, 'kirimKonfirmasi'])->name('konfirmasi.kirim');

// 6. Profil Santri
Route::get('/profil', [PesantrenController::class, 'profil'])->name('profil');

// Route untuk mengganti sesi bahasa aplikasi secara dinamis
Route::get('/set-language/{lang}', [PesantrenController::class, 'setLanguage'])->name('set.language');