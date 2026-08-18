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
        <!-- Mobile Header -->
        <header class="admin-mobile-header">
            <button type="button" id="admin-sidebar-toggle" class="sidebar-toggle-btn" aria-label="Buka menu admin" aria-expanded="false" aria-controls="admin-sidebar">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div class="mobile-brand">
                <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia" style="background: transparent;">
                <span>Admin Repository</span>
            </div>
            <div style="width: 24px;"></div>
        </header>

        <!-- Overlay Backdrop -->
        <div id="admin-sidebar-overlay" class="sidebar-overlay"></div>

        <aside class="admin-sidebar" id="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia" style="background: transparent;">
                <span>Admin Repository</span>
            </a>

            <nav class="admin-menu">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a class="{{ request()->routeIs('admin.data.mahasiswa') ? 'active' : '' }}"
                    href="{{ route('admin.data.mahasiswa') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                    <span>Data Mahasiswa</span>
                </a>
                <a class="{{ request()->routeIs('admin.data.dosen') ? 'active' : '' }}"
                    href="{{ route('admin.data.dosen') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <polyline points="17 11 19 13 23 9" />
                    </svg>
                    <span>Data Dosen</span>
                </a>
                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span>Kelola User</span>
                </a>
                <a class="{{ request()->routeIs('admin.documents.pending') ? 'active' : '' }}"
                    href="{{ route('admin.documents.pending') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                    <span>Verifikasi Upload</span>
                </a>
                <a class="{{ request()->routeIs('admin.documents.index') ? 'active' : '' }}"
                    href="{{ route('admin.documents.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <line x1="10" y1="9" x2="8" y2="9" />
                    </svg>
                    <span>Kelola Upload</span>
                </a>
                <a class="{{ request()->routeIs('admin.documents.create*') ? 'active' : '' }}"
                    href="{{ route('admin.documents.create') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <span>Upload Manual</span>
                </a>
                <a class="{{ request()->routeIs('admin.documents.import*') ? 'active' : '' }}"
                    href="{{ route('admin.documents.import') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <span>Import Excel</span>
                </a>
                <a class="{{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    <span>Laporan</span>
                </a>
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}#setting-upload">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    <span>Setting</span>
                </a>
                <a href="{{ route('home') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        <polyline points="15 3 21 3 21 9" />
                        <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                    <span>Lihat Situs</span>
                </a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="admin-logout">
                @csrf
                <div class="admin-user-info">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <button type="submit">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    <span>Keluar</span>
                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');

            if (toggleBtn && sidebar && overlay) {
                const desktopQuery = window.matchMedia('(min-width: 993px)');

                function setExpanded(isOpen) {
                    toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    toggleBtn.setAttribute('aria-label', isOpen ? 'Tutup menu admin' : 'Buka menu admin');
                }

                function toggleSidebar() {
                    const willOpen = !sidebar.classList.contains('open');

                    sidebar.classList.toggle('open', willOpen);
                    overlay.classList.toggle('active', willOpen);
                    document.body.classList.toggle('sidebar-open', willOpen);
                    setExpanded(willOpen);
                }

                function closeSidebar() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                    setExpanded(false);
                }

                toggleBtn.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', closeSidebar);
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeSidebar();
                    }
                });

                desktopQuery.addEventListener('change', function(event) {
                    if (event.matches) {
                        closeSidebar();
                    }
                });

                // Close sidebar when clicking menu links on mobile
                const menuLinks = sidebar.querySelectorAll('.admin-menu a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', closeSidebar);
                });
            }
        });
    </script>
</body>
</html>
