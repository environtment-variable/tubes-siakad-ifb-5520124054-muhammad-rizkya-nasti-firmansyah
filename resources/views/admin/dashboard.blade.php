<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background: #f3f4f6;
        }

        nav {
            background: #1e293b;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            /* Tambahkan 3 baris ini untuk mensejajarkan konten */
            display: flex;
            align-items: center; 
            justify-content: space-between;
        }

        nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-logout {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.dosen.index') }}">Data Dosen</a>
        <a href="{{ route('admin.mahasiswa.index') }}">Data Mahasiswa</a>
        <a href="{{ route('admin.matakuliah.index') }}">Data Matakuliah</a>
        <a href="{{ route('admin.jadwal.index') }}">Data Jadwal</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <div class="container">
        <h1>Selamat datang, Admin!</h1>
        <p>Silakan gunakan menu navigasi di atas untuk mengelola data akademik.</p>
    </div>

</body>

</html>