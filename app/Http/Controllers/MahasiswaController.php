<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Tampilkan Semua Data Mahasiswa
    public function index()
    {
        // Menggunakan eager loading 'dosen' agar query lebih ringan
        $mahasiswas = Mahasiswa::with('dosen')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    // Form Tambah Mahasiswa
    public function create()
    {
        $dosens = Dosen::all(); // Mengambil semua dosen untuk pilihan Dosen Wali
        return view('admin.mahasiswa.create', compact('dosens'));
    }

    // Simpan Data Mahasiswa Baru
    public function store(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|size:10|unique:mahasiswas,npm',
            'nama' => 'required|string|max:50',
            'nidn' => 'required|exists:dosens,nidn', // Validasi harus ada di tabel dosen
        ], [
            'npm.required' => 'NPM wajib diisi.',
            'npm.size' => 'NPM harus tepat 10 karakter.',
            'npm.unique' => 'NPM sudah terdaftar.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 50 karakter.',
            'nidn.required' => 'Dosen wali wajib dipilih.',
            'nidn.exists' => 'Dosen wali tidak valid.',
        ]);

        Mahasiswa::create($request->all());

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan!');
    }

    // Form Edit Data Mahasiswa
    public function edit($npm)
    {
        // Gunakan where agar Laravel mencari di kolom 'npm'
        $mahasiswa = Mahasiswa::where('npm', $npm)->firstOrFail();
        $dosens = Dosen::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosens'));
    }

    // Update Data Mahasiswa
    public function update(Request $request, $npm)
    {
        // Gunakan where untuk mencocokkan npm
        $mahasiswa = Mahasiswa::where('npm', $npm)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:50',
            'nidn' => 'required|exists:dosens,nidn',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 50 karakter.',
            'nidn.required' => 'Dosen wali wajib dipilih.',
        ]);

        $mahasiswa->update($request->only('nama', 'nidn'));

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diubah!');
    }

    // Hapus Data Mahasiswa
    public function destroy($npm)
    {
        $mahasiswa = Mahasiswa::findOrFail($npm);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
