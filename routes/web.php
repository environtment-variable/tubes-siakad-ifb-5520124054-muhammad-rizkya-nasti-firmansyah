<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;

// Halaman utama otomatis arahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi (Bypass middleware guest bawaan agar tidak loop)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rute Logout (Harus login dulu)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ======================== AREA PROTEKSI ROLE ========================

// Kelompok Rute Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Tempat CRUD Dosen, Mahasiswa, Matakuliah, Jadwal disimpan
    Route::resource('matakuliah', MatakuliahController::class)->parameters([
        'matakuliah' => 'kode_matakuliah' // Menggunakan kode_matakuliah sebagai primary key di URL
    ]);

    // Di dalam group admin:
    Route::resource('jadwal', JadwalController::class);

    Route::resource('dosen', DosenController::class)->parameters([
        'dosen' => 'nidn' // Beritahu Laravel kalau parameter URL-nya adalah {nidn}
    ]);

    Route::resource('mahasiswa', MahasiswaController::class)->parameters([
        'mahasiswa' => 'npm' // Beritahu Laravel parameter URL-nya menggunakan {npm}
    ]);
});

// Kelompok Rute Khusus Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    // Pasang KrsController langsung di rute /dashboard utama mahasiswa
    // URL di browser: 127.0.0.1:8000/mahasiswa/dashboard
    Route::get('/dashboard', [KrsController::class, 'index'])->name('dashboard');

    // URL di browser otomatis menjadi: 127.0.0.1:8000/mahasiswa/krs/pilih
    Route::get('/krs/pilih', [KrsController::class, 'create'])->name('pilih_krs');
    Route::post('/krs/simpan', [KrsController::class, 'store'])->name('simpan_krs');
    Route::delete('/krs/batal/{kode_matakuliah}', [KrsController::class, 'destroy'])->name('batal_krs');
});
