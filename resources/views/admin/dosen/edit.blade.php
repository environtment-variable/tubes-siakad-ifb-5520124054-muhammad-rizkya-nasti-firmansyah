<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Dosen</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f3f4f6;
            /* Tambahkan ini untuk memusatkan secara vertikal & horizontal */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin: 0;
        }

        .kotak {
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            /* Ubah max-width ke width agar responsif */
            max-width: 500px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .tombol {
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="kotak">
        <h2>Edit Data Dosen</h2>

        {{-- Menampilkan pesan error validasi jika ada --}}
        @if ($errors->any())
            <div style="color: #dc2626; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dosen.update', $dosen->nidn) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="input-group">
                <label>NIDN (Tidak bisa diubah):</label>
                <input type="text" value="{{ $dosen->nidn }}" disabled style="background: #e5e7eb;">
            </div>

            <div class="input-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}" required>
            </div>

            <button type="submit" class="tombol">Simpan Perubahan</button>
            <a href="{{ route('admin.dosen.index') }}" style="margin-left: 15px; color: #64748b; text-decoration: none;">Batal</a>
        </form>
    </div>

</body>

</html>
