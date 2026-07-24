<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Admin') - Universitas Metamedia</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    @if (auth()->check() && auth()->user()->role === 'admin')
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia">
            <span>Admin Repository</span>
        </a>

        <nav class="admin-menu">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="{{ request()->routeIs('admin.data.mahasiswa') ? 'active' : '' }}" href="{{ route('admin.data.mahasiswa') }}">Data Mahasiswa</a>
            <a class="{{ request()->routeIs('admin.data.dosen') ? 'active' : '' }}" href="{{ route('admin.data.dosen') }}">Data Dosen</a>
            <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Kelola User</a>
            <a class="{{ request()->routeIs('admin.documents.pending') ? 'active' : '' }}" href="{{ route('admin.documents.pending') }}">Verifikasi Upload</a>
            <a class="{{ request()->routeIs('admin.documents.index') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">Kelola Upload</a>
            <a class="{{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Laporan</a>
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}#setting-upload">Setting</a>
            <a href="{{ route('home') }}">Lihat Situs</a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="admin-logout">
            @csrf
            <span>{{ auth()->user()->name }}</span>
            <button type="submit">Keluar</button>
        </form>
    </aside>

    <main class="admin-main">
        @if (session('status'))
            <div class="flash-message admin-flash">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
    @else
        <div style="padding: 40px; text-align: center; color: #666;">
            <p>Anda tidak memiliki akses ke halaman ini.</p>
            <a href="{{ route('home') }}" style="color: #0066cc; text-decoration: none;">Kembali ke beranda</a>
        </div>
    @endif
</body>
</html>
