<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f3f4f6;
            margin: 0;
            /* Penting untuk mereset default margin */
        }

        .kotak {
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            /* Ini akan membuat kotak selalu di tengah dan punya jarak 50px dari atas */
            margin: 50px auto 0 auto;
        }

        /* Agar inputan di dalam kotak rapi dan seragam */
        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            /* Agar padding tidak merusak lebar input */
        }

        .tombol {
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="kotak">
        <h2>Edit Data Mahasiswa</h2>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->npm) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="input-group">
                <label>NPM:</label>
                <input type="text" value="{{ $mahasiswa->npm }}" disabled style="background: #eee;">
            </div>

            <div class="input-group">
                <label>Nama Mahasiswa:</label>
                <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
            </div>

            <div class="input-group">
                <label>Dosen Wali:</label>
                <select name="nidn" required>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->nidn }}" {{ $mahasiswa->nidn == $dosen->nidn ? 'selected' : '' }}>
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="tombol">Simpan Perubahan</button>
           <a href="{{ route('admin.mahasiswa.index') }}" style="margin-left: 15px; color: #64748b; text-decoration: none;">Batal</a>
        </form>
    </div>

</body>

</html>
