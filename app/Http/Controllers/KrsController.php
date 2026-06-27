<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index()
    {
        $npmLogon = Auth::user()->username_kaitan ?? '';

        $mhs = Mahasiswa::where('npm', $npmLogon)->first() ?? (object)[
            'nama_mahasiswa' => Auth::user()->name,
            'npm' => $npmLogon ?: 'NPM Tidak Terdeteksi'
        ];

        // Menggunakan relasi model yang sudah didefinisikan
        $krsAktif = Krs::with(['matakuliah', 'jadwal.dosen'])
            ->where('npm', $npmLogon)
            ->get();

        return view('mahasiswa.dashboard', compact('mhs', 'krsAktif'));
    }

    public function create()
    {
        $npmLogon = Auth::user()->username_kaitan ?? '';

        $pilihanJadwal = Jadwal::with(['matakuliah', 'dosen'])->get();

        // Menggunakan Eloquent pluck langsung dari model Krs
        $krsTerpilih = Krs::where('npm', $npmLogon)->pluck('kode_matakuliah')->toArray();

        return view('mahasiswa.pilih_krs', compact('pilihanJadwal', 'krsTerpilih'));
    }

    public function store(Request $request)
    {
        $npmLogon = Auth::user()->username_kaitan;
        $kodeMkBaru = $request->input('kode_matakuliah');

        // Ambil data matakuliah untuk SKS
        $mkBaru = Matakuliah::findOrFail($kodeMkBaru);

        // Hitung total SKS dengan relasi
        $totalSksDiambil = Krs::where('npm', $npmLogon)
            ->with('matakuliah')
            ->get()
            ->sum(fn($krs) => $krs->matakuliah->sks);

        if (($totalSksDiambil + $mkBaru->sks) > 24) {
            return redirect()->back()->with('error', 'Gagal! Total SKS melebihi batas maksimal (24 SKS).');
        }

        // Simpan menggunakan Eloquent (Otomatis handle timestamps)
        Krs::updateOrCreate(
            ['npm' => $npmLogon, 'kode_matakuliah' => $kodeMkBaru],
            [] // Data yang ingin diupdate atau created
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function destroy($kode_matakuliah)
    {
        $npmLogon = Auth::user()->username_kaitan;

        // Eloquent delete yang aman
        $deleted = Krs::where('npm', $npmLogon)
            ->where('kode_matakuliah', $kode_matakuliah)
            ->delete();

        return $deleted
            ? redirect()->route('mahasiswa.dashboard')->with('success', 'Mata kuliah berhasil dibatalkan.')
            : redirect()->route('mahasiswa.dashboard')->with('error', 'Gagal membatalkan mata kuliah.');
    }
}
