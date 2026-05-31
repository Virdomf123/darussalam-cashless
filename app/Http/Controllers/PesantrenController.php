<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaksi;
use App\Models\Santri;

class PesantrenController extends Controller
{
    /**
     * Dashboard Utama & AI Assistant (Pemisahan Fokus Analisis vs Investasi)
     */
    public function index(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $santri = Santri::where('nim', $nimInput)->first();

        if (!$santri) {
            return "Data santri dengan NIM $nimInput tidak ditemukan di database.";
        }

        $role = $request->get('role', 'parent');

        // --- LOGIKA DINAMIS KALKULATOR IMPIAN (VERSI REAL-TIME & INDIKATOR) ---
        
        // 1. Ambil total belanja santri KHUSUS HARI INI saja
        $totalBelanjaHariIni = Transaksi::where('nim', $nimInput)
                                     ->whereDate('created_at', now())
                                     ->sum('harga');

        // 2. Setting Jatah Saku / Plafon Harian
        $uangSakuHarian = 50000; 
        
        // 3. Estimasi sisa uang harian (Plafon dikurangi belanja riil hari ini)
        $estimasiSisaHarian = $uangSakuHarian - $totalBelanjaHariIni;

        // --- TAMBAHAN: LOGIKA INDIKATOR STATUS ---
        if ($totalBelanjaHariIni == 0) {
            $statusHemat = "Sempurna! Belum ada pengeluaran hari ini.";
            $warnaStatus = "text-info"; // Biru Muda
        } elseif ($estimasiSisaHarian > ($uangSakuHarian * 0.5)) {
            $statusHemat = "Hemat! Pertahankan gaya hidup sehatmu.";
            $warnaStatus = "text-success"; // Hijau
        } elseif ($estimasiSisaHarian > 0) {
            $statusHemat = "Waspada! Pengeluaran mulai mendekati batas.";
            $warnaStatus = "text-warning"; // Kuning/Oranye
        } else {
            $statusHemat = "Over-budget! Jatah hari ini sudah habis.";
            $warnaStatus = "text-danger"; // Merah
            $estimasiSisaHarian = 0;
        }
        
        // 4. Kalkulasi Prediksi Otomatis untuk Dashboard (365 hari * 3 tahun)
        $prediksiTabungan = $estimasiSisaHarian * 365 * 3;
        $potensiEmas = $prediksiTabungan * 1.12; // Asumsi kenaikan emas 12%

        // --- AKHIR LOGIKA DINAMIS ---

        // Ambal 3 transaksi terbaru dari database untuk tabel dashboard
        $transaksi = Transaksi::where('nim', $nimInput)
                               ->orderBy('created_at', 'desc')
                               ->take(3)
                               ->get();

        // LOGIKA DINAMIS: Menghitung total pengeluaran 7 hari terakhir untuk grafik
        $grafikPengeluaran = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $totalHarian = Transaksi::where('nim', $nimInput)
                                    ->whereDate('created_at', $tanggal)
                                    ->sum('harga');
            $grafikPengeluaran[] = $totalHarian;
        }

        // Deklarasi awal variabel hasil AI agar tidak error saat view pertama kali dimuat
        $aiResult = null;
        $aiInvestasi = null;

        // FITUR AI ANALISA (Dipicu lewat tombol 'Analisa Pengeluaran Sekarang')
        if ($request->has('tanya_ai') || $request->isMethod('post')) {
            $apiKey = env('GEMINI_API_KEY'); 
            
            // PROMPT 1: KHUSUS UNTUK EVALUASI & ANALISA PENGELUARAN HARI INI
            $promptAnalisis = "Nama Santri: {$santri->nama}. Saldo Aktif Saat Ini (Sudah Bersih Setelah Dipotong Belanja): Rp" . number_format($santri->saldo, 0, ',', '.') . ". 
                               Total belanja hari ini: Rp" . number_format($totalBelanjaHariIni, 0, ',', '.') . ". 
                               Estimasi sisa uang jajan harian dari plafon: Rp" . number_format($estimasiSisaHarian, 0, ',', '.') . ".
                               Status hemat harian: {$statusHemat}.
                               Tugasmu: Berikan analisis singkat, padat, and ramah mengenai kondisi pengeluaran hari ini saja. Ketika menyebutkan saldo aktif, gunakan angka Rp" . number_format($santri->saldo, 0, ',', '.') . " sebagai saldo mutakhirnya saat ini and JANGAN dikurangi lagi dengan total belanja karena saldo tersebut sudah otomatis terpotong oleh sistem kasir. Berikan kalimat motivasi hangat khas pesantren agar dia konsisten menabung. JANGAN bahas cara investasi, emas, bank syariah, riba, maysir, atau gharar di sini. Tampilkan dalam 1-2 paragraf rapi.";

            // PROMPT 2: KHUSUS UNTUK POJOK INVESTASI SYARIAH
            $promptInvestasi = "Nama Santri: {$santri->nama}. 
                                Potensi tabungan 3 tahun jika konsisten menyisihkan uang jajan hari ini: Rp" . number_format($prediksiTabungan, 0, ',', '.') . ".
                                Potensi konversi nilai emas harian: Rp" . number_format($potensiEmas, 0, ',', '.') . ".
                                Tugasmu: Berikan panduan investasi syariah tingkat pemula yang mendidik untuk orang tua/santri berdasarkan potensi tabungan tersebut. Jelaskan mengapa penting berinvestasi emas atau instrumen syariah (wadiah/mudharabah) demi masa depan. Jelaskan singkat prinsip menjauhi maysir, gharar, and riba. JANGAN menganalisis atau menyebut pengeluaran belanja jajannya hari ini. Tampilkan dalam bentuk poin-poin yang edukatif and rapi.";

            try {
                // Request AI Pertama (Analisis Cashflow)
                $response1 = Http::withOptions(['verify' => false])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey, [
                    'contents' => [['parts' => [['text' => $promptAnalisis]]]]
                ]);

                // Request AI Kedua (Edukasi Investasi)
                $response2 = Http::withOptions(['verify' => false])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey, [
                    'contents' => [['parts' => [['text' => $promptInvestasi]]]]
                ]);

                if (!$response1->failed()) {
                    $resJson1 = $response1->json();
                    $aiResult = $resJson1['candidates'][0]['content']['parts'][0]['text'] ?? "Asisten sedang menganalisis.";
                }

                if (!$response2->failed()) {
                    $resJson2 = $response2->json();
                    $aiInvestasi = $resJson2['candidates'][0]['content']['parts'][0]['text'] ?? "Panduan investasi sedang disiapkan.";
                }

            } catch (\Exception $e) {
                $aiResult = "Koneksi gagal.";
                $aiInvestasi = "Koneksi panduan investasi gagal.";
            }
        }

        return view('dashboard', compact(
            'santri', 'transaksi', 'aiResult', 'aiInvestasi', 'role', 'grafikPengeluaran',
            'estimasiSisaHarian', 'prediksiTabungan', 'potensiEmas', 'statusHemat', 'warnaStatus'
        ));
    }

    /**
     * MENU BARU: Halaman Utama Menu Chatbot Diskusi Terpisah
     */
    public function chatbotPage(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $role = $request->get('role', 'parent');
        $santri = Santri::where('nim', $nimInput)->first();

        if (!$santri) {
            return "Data santri tidak ditemukan.";
        }

        return view('chatbot', compact('santri', 'role'));
    }

    /**
     * Memproses Input Chatbot and Me-redirect Kembali ke View Chatbot (Bukan Dashboard)
     */
    public function chatWithAi(Request $request)
    {
        $request->validate([
            'user_message' => 'required|string',
            'nim' => 'required',
            'role' => 'required'
        ]);

        $nimInput = $request->nim;
        $role = $request->role;
        $userMessage = $request->user_message;

        $santri = Santri::where('nim', $nimInput)->first();
        if (!$santri) {
            return redirect()->back()->with('error', 'Data santri tidak ditemukan.');
        }

        // Ambil data belanja khusus hari ini sebagai context tambahan untuk sistem AI
        $totalBelanjaHariIni = Transaksi::where('nim', $nimInput)
                                     ->whereDate('created_at', now())
                                     ->sum('harga');

        $uangSakuHarian = 50000;
        $estimasiSisaHarian = $uangSakuHarian - $totalBelanjaHariIni;
        if($estimasiSisaHarian < 0) $estimasiSisaHarian = 0;

        // Menyusun Sistem Context / Instruksi awal agar AI mengenali data finansial santri secara real-time
        $apiKey = env('GEMINI_API_KEY');
        $systemContext = "Kamu adalah Asisten Finansial Digital Pesantren Darussalam bernama 'Asisten Darussalam'. 
                          Kamu sedang berdiskusi dengan user mengenai keuangan santri bernama {$santri->nama} (NIM: {$santri->nim}).
                          Data keuangan riil saat ini: Saldo Aktif Rp" . number_format($santri->saldo, 0, ',', '.') . ", 
                          Total belanja hari ini Rp" . number_format($totalBelanjaHariIni, 0, ',', '.') . ", 
                          Sisa uang jajan hari ini Rp" . number_format($estimasiSisaHarian, 0, ',', '.') . ".
                          Jawablah pertanyaan user dengan bahasa Indonesia yang ramah, sopan khas pesantren, memotivasi untuk menabung, and mendidik secara finansial syariah.";

        // Gabungkan context dengan pertanyaan chat yang diinput oleh user
        $fullPrompt = $systemContext . "\n\nUser bertanya: " . $userMessage;

        $aiResponse = "Maaf, asisten sedang tidak dapat dihubungi.";

        try {
            $response = Http::withOptions(['verify' => false])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey, [
                'contents' => [['parts' => [['text' => $fullPrompt]]]]
            ]);

            if (!$response->failed()) {
                $resJson = $response->json();
                $aiResponse = $resJson['candidates'][0]['content']['parts'][0]['text'] ?? "AI sedang berpikir.";
            }
        } catch (\Exception $e) {
            $aiResponse = "Koneksi ke AI gagal.";
        }

        // REDIRECT KEMBALI KE HALAMAN MENU CHATBOT.PAGE (BUKAN DASHBOARD)
        return redirect()->route('chatbot.page', ['role' => $role, 'nim' => $nimInput])->with([
            'chat_user' => $userMessage,
            'chat_ai' => $aiResponse
        ]);
    }

    /**
     * Fitur Admin: Halaman Daftar Database Santri
     */
    public function databaseSantri(Request $request)
    {
        $nimInput = $request->get('nim', '5829'); 
        $role = $request->get('role', 'admin');
        
        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin Utama yang dapat melihat database santri.');
        }

        $santri = Santri::where('nim', $nimInput)->first();

        // Logika Fitur Pencarian Santri Master
        $search = $request->get('search');
        if ($search) {
            $allSantri = Santri::where('nama', 'LIKE', "%{$search}%")
                               ->orWhere('nim', 'LIKE', "%{$search}%")
                               ->get();
        } else {
            $allSantri = Santri::all(); 
        }

        return view('database_santri', compact('santri', 'allSantri', 'role', 'search'));
    }

    /**
     * Fitur Admin: Form Tambah Santri Baru
     */
    public function tambahSantriForm(Request $request)
    {
        $nimInput = $request->get('admin_nim'); 
        $role = $request->get('role', 'admin');

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $santri = Santri::where('nim', $nimInput)->first(); 

        return view('tambah_santri', compact('santri', 'role'));
    }

    /**
     * Fitur Admin: Proses Simpan Data Santri Baru ke DB
     */
    public function simpanSantri(Request $request)
    {
        $request->validate([
            'nim_baru' => 'required|unique:santris,nim', 
            'nama_baru' => 'required|string|max:255',
            'saldo_awal' => 'required|numeric|min:0',
            'tabungan_awal' => 'required|numeric|min:0',
            'admin_nim' => 'required',
            'role' => 'required'
        ]);

        Santri::create([
            'nim' => $request->nim_baru,
            'nama' => $request->nama_baru,
            'saldo' => $request->saldo_awal,
            'tabungan' => $request->tabungan_awal
        ]);

        return redirect()->route('santri.database', [
            'role' => $request->role, 
            'nim' => $request->admin_nim
        ])->with('success', 'Santri baru berhasil didaftarkan ke sistem master!');
    }

    /**
     * Fitur: Kasir Kantin
     */
    public function kantin(Request $request)
    {
        $role = $request->get('role', 'kasir');
        $nimPetugas = $request->get('nim'); 
        $nimCari = $request->get('nim_cari'); 
        $santri = null;

        if ($nimCari) {
            $santri = Santri::where('nim', $nimCari)->first();
        }

        return view('kantin', compact('santri', 'role'));
    }

    /**
     * Fitur: Proses Bayar Kantin
     */
    public function prosesBayar(Request $request)
    {
        $request->validate([
            'nim_pembeli' => 'required',
            'produk' => 'required',
            'harga' => 'required|numeric|min:500',
            'petugas_nim' => 'required',
            'petugas_role' => 'required',
        ]);

        $pembeli = Santri::where('nim', $request->nim_pembeli)->first();

        if (!$pembeli) {
            return redirect()->back()->with('error', 'Data santri pembeli tidak ditemukan!');
        }

        if ($pembeli->saldo < $request->harga) {
            return redirect()->back()->with('error', 'Saldo ' . $pembeli->nama . ' tidak cukup!');
        }

        $pembeli->saldo -= $request->harga;
        $pembeli->save();

        Transaksi::create([
            'nim' => $pembeli->nim,
            'produk' => $request->produk,
            'harga' => $request->harga,
            'kategori' => 'Makanan/Minuman'
        ]);

        return redirect()->route('dashboard', [
            'role' => $request->petugas_role, 
            'nim' => $request->petugas_nim
        ])->with('success', 'Berhasil! Saldo ' . $pembeli->nama . ' telah dipotong.');
    }

    /**
     * Fitur Admin: Update Saldo (Top Up)
     */
    public function updateSaldo(Request $request)
    {
        $role = $request->get('role');
        $adminNim = $request->get('admin_nim'); 
        
        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
            'nim' => 'required',
            'jenis_saldo' => 'required',
            'admin_nim' => 'required'
        ]);
        
        $santri = Santri::where('nim', $request->nim)->first();
        
        if ($santri) {
            $jumlah = (int) $request->jumlah;
            
            if ($request->jenis_saldo == 'tabungan') {
                $santri->tabungan += $jumlah; 
            } else {
                $santri->saldo += $jumlah;
            }
            
            $santri->save();

            return redirect()->route('dashboard', [
                'role' => 'admin', 
                'nim' => $adminNim
            ])->with('success', 'Saldo berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Santri tidak ditemukan.');
    }

    /**
     * Fitur Admin: Mengarahkan halaman Top Up secara dinamis
     */
    public function topup(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $adminNim = $request->get('admin_nim', '5829');
        $role = $request->get('role', 'admin'); 
        
        $santri = Santri::where('nim', $nimInput)->first();

        if (!$santri && $request->has('proses_target')) {
            return redirect()->route('topup', ['role' => 'admin', 'admin_nim' => $adminNim, 'nim' => $adminNim])
                             ->with('error', 'Data santri dengan NIM ' . $nimInput . ' tidak ditemukan di database.');
        }

        return view('topup', compact('santri', 'role'));
    }

    /**
     * Fitur Admin: Hapus Data Master Santri dari Database
     */
    public function hapusSantri(Request $request, $target_nim)
    {
        $adminNim = $request->get('admin_nim'); 
        $role = $request->get('role', 'admin');

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin Utama yang dapat menghapus data.');
        }

        $santriTarget = Santri::where('nim', $target_nim)->first();

        if ($santriTarget) {
            $namaSantri = $santriTarget->nama;
            $santriTarget->delete(); 

            return redirect()->route('santri.database', [
                'role' => $role,
                'nim' => $adminNim
            ])->with('success', 'Data master santri bernama ' . $namaSantri . ' (NIM: ' . $target_nim . ') berhasil dihapus dari sistem.');
        }

        return redirect()->back()->with('error', 'Data santri tidak ditemukan.');
    }

    /**
     * Fitur Admin: Halaman Rekapitulasi Laporan Keuangan Pesantren
     */
    public function laporanKeuangan(Request $request)
    {
        $nimInput = $request->get('nim', '5829'); 
        $role = $request->get('role', 'admin');
        
        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin Utama yang dapat melihat laporan keuangan.');
        }

        $santri = Santri::where('nim', $nimInput)->first();

        // LOGIKA REKAP KEUANGAN GLOBAL
        $totalSaldoBeredar = Santri::sum('saldo'); 
        $totalTabunganSantri = Santri::sum('tabungan'); 
        $totalTransaksiKantin = Transaksi::count(); 
        $omsetKantinHariIni = Transaksi::whereDate('created_at', now())->sum('harga'); 
        
        $semuaTransaksi = Transaksi::orderBy('created_at', 'desc')->get();

        return view('laporan_keuangan', compact(
            'santri', 'role', 'totalSaldoBeredar', 'totalTabunganSantri', 
            'totalTransaksiKantin', 'omsetKantinHariIni', 'semuaTransaksi'
        ));
    }

    /**
     * Fitur: Tabungan Santri
     */
    public function tabungan(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $role = $request->get('role', 'parent');
        $santri = Santri::where('nim', $nimInput)->first();

        if (!$santri) return "Data santri tidak ditemukan.";

        $tabungan = (object) [
            'saldo_tabungan' => $santri->tabungan, 
            'target_tabungan' => 2000000,
            'riwayat_setoran' => [
                ['tanggal' => date('Y-m-d'), 'jumlah' => $santri->tabungan, 'ket' => 'Total Tabungan Terkumpul'],
            ]
        ];

        return view('tabungan', compact('santri', 'role', 'tabungan'));
    }

    /**
     * Halaman Riwayat Belanja
     */
    public function riwayat(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $role = $request->get('role', 'parent');
        $santri = Santri::where('nim', $nimInput)->first();

        $semuaTransaksi = Transaksi::where('nim', $nimInput)
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('riwayat', compact('santri', 'semuaTransaksi', 'role'));
    }

    public function kirimKonfirmasi(Request $request)
    {
        return redirect()->back()->with('success', 'Laporan setoran telah dikirim!');
    }

    /**
     * Fitur: Halaman Profil Santri
     */
    public function profil(Request $request)
    {
        $nimInput = $request->get('nim', '5829');
        $role = $request->get('role', 'parent');
        $santri = Santri::where('nim', $nimInput)->first();

        if (!$santri) {
            return "Data santri tidak ditemukan.";
        }

        return view('profil', compact('santri', 'role'));
    }

    /**
     * Mengubah bahasa aplikasi dan menyimpannya ke dalam session
     */
    public function setLanguage($lang)
    {
        // Validasi input bahasa agar hanya menerima 'id' atau 'en'
        if (in_array($lang, ['id', 'en'])) {
            session()->put('locale', $lang);
        }
        
        return redirect()->back();
    }
}