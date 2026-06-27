<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    // Menampilkan semua daftar mata kuliah
    public function index()
    {
        $matakuliahs = Matakuliah::all();
        return view('admin.matakuliah.index', compact('matakuliahs'));
    }

    // Menampilkan form tambah mata kuliah
    public function create()
    {
        return view('admin.matakuliah.create');
    }

    // Menyimpan data mata kuliah baru ke database 'SQLite'
    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|string|max:10|unique:matakuliahs,kode_matakuliah',
            'nama_matakuliah' => 'required|string|max:50',
            'sks'             => 'required|integer|min:1|max:6',
        ], [
            'kode_matakuliah.required' => 'Kode mata kuliah wajib diisi.',
            'kode_matakuliah.unique'   => 'Kode mata kuliah sudah digunakan.',
            'nama_matakuliah.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required'             => 'Jumlah SKS wajib diisi.',
            'sks.integer'              => 'SKS harus berupa angka.',
        ]);

        Matakuliah::create($request->all());

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    // Menampilkan form edit mata kuliah
    public function edit($kode)
    {
        $matakuliah = Matakuliah::findOrFail($kode);
        return view('admin.matakuliah.edit', compact('matakuliah'));
    }

    // Memperbarui data mata kuliah di database
    public function update(Request $request, $kode)
    {
        $matakuliah = Matakuliah::findOrFail($kode);

        $request->validate([
            'nama_matakuliah' => 'required|string|max:50',
            'sks'             => 'required|integer|min:1|max:6',
        ], [
            'nama_matakuliah.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required'             => 'Jumlah SKS wajib diisi.',
            'sks.integer'              => 'SKS harus berupa angka.',
        ]);

        $matakuliah->update($request->only('nama_matakuliah', 'sks'));

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil diubah!');
    }

    // Menghapus data mata kuliah
    public function destroy($kode)
    {
        $matakuliah = Matakuliah::findOrFail($kode);
        $matakuliah->delete();

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata Kuliah berhasil dihapus!');
    }
}
