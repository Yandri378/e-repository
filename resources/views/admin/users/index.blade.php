@extends('layouts.admin')

@section('title', 'Pemantauan & Kelola Akun')

@section('content')
    <section class="page-hero compact">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="eyebrow">Admin Panel</p>
                <h1>Pemantauan Akun Mahasiswa & Dosen</h1>
                <p class="mt-1 text-sm text-slate-400">Pantau aktivitas login, nonaktifkan akun yang jarang aktif, atau reset kata sandi pengguna.</p>
            </div>
            <div>
                <button type="button" onclick="toggleAddUserForm()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Akun Manual</span>
                </button>
            </div>
        </div>
    </section>

    @if (session('status'))
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm font-semibold text-emerald-400 flex items-center justify-between">
                <span>{{ session('status') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">&times;</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm font-semibold text-rose-400">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- STATISTICAL MONITORING CARDS --}}
    <section class="section" style="padding-bottom: 0;">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Users --}}
            <a href="{{ route('admin.users.index', ['status' => 'semua']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Akun Terdaftar</span>
                    <span class="rounded-xl bg-blue-500/10 p-2 text-blue-600 dark:text-blue-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Mahasiswa & Dosen</p>
            </a>

            {{-- Akun Aktif --}}
            <a href="{{ route('admin.users.index', ['status' => 'aktif']) }}" class="rounded-2xl border border-emerald-500/20 bg-emerald-50/30 p-5 shadow-sm transition hover:border-emerald-400 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Akun Aktif</span>
                    <span class="rounded-xl bg-emerald-500/10 p-2 text-emerald-600 dark:text-emerald-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $stats['aktif'] }}</p>
                <p class="mt-1 text-xs text-emerald-600/80 dark:text-emerald-400/80">Dapat login & akses repository</p>
            </a>

            {{-- Jarang Aktif (>30 Hari) --}}
            <a href="{{ route('admin.users.index', ['status' => 'jarang_aktif']) }}" class="rounded-2xl border border-amber-500/20 bg-amber-50/40 p-5 shadow-sm transition hover:border-amber-400 dark:border-amber-900/40 dark:bg-amber-950/20">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Jarang Aktif (&gt;30 Hari)</span>
                    <span class="rounded-xl bg-amber-500/10 p-2 text-amber-600 dark:text-amber-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-amber-700 dark:text-amber-300">{{ $stats['jarang_aktif'] }}</p>
                <p class="mt-1 text-xs text-amber-600/80 dark:text-amber-400/80">Perlu dipantau / dinonaktifkan</p>
            </a>

            {{-- Akun Nonaktif --}}
            <a href="{{ route('admin.users.index', ['status' => 'nonaktif']) }}" class="rounded-2xl border border-rose-500/20 bg-rose-50/30 p-5 shadow-sm transition hover:border-rose-400 dark:border-rose-900/40 dark:bg-rose-950/20">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400">Akun Nonaktif</span>
                    <span class="rounded-xl bg-rose-500/10 p-2 text-rose-600 dark:text-rose-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-rose-700 dark:text-rose-300">{{ $stats['nonaktif'] }}</p>
                <p class="mt-1 text-xs text-rose-600/80 dark:text-rose-400/80">Akses login dinonaktifkan</p>
            </a>
        </div>
    </section>

    {{-- COLLAPSIBLE ADD USER FORM (FOR ADMIN MANUAL BACKUP) --}}
    <section id="add-user-container" class="section hidden">
        <form method="POST" action="{{ route('admin.users.store') }}" class="auth-card wide rounded-2xl border border-slate-200 bg-white p-6 shadow-md dark:border-slate-800 dark:bg-slate-900">
            @csrf
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Formulir Manual</p>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Tambah Akun Mahasiswa / Dosen oleh Admin</h2>
                </div>
                <button type="button" onclick="toggleAddUserForm()" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Tutup &times;</button>
            </div>

            <div class="form-grid mt-4">
                <label>Role Pengguna
                    <select name="role" id="user-role-select" required>
                        <option value="mahasiswa" @selected(old('role', 'mahasiswa') === 'mahasiswa')>Mahasiswa</option>
                        <option value="dosen" @selected(old('role') === 'dosen')>Dosen</option>
                    </select>
                </label>
                <label>Nama Lengkap
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap pengguna" required>
                </label>
                <label id="nim-container">NIM Mahasiswa
                    <input type="text" id="nim-input" name="nim" value="{{ old('nim') }}" placeholder="Masukkan NIM mahasiswa">
                </label>
                <label id="nidn-container" style="display: none;">NIDN Dosen
                    <input type="text" id="nidn-input" name="nidn" value="{{ old('nidn') }}" placeholder="Masukkan NIDN dosen">
                </label>
                <label>Program Studi
                    <select name="program_studi_id" required>
                        <option value="">Pilih prodi</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" @selected(old('program_studi_id') == $prodi->id)>{{ $prodi->nama }} ({{ $prodi->jenjang }})</option>
                        @endforeach
                    </select>
                </label>
                <label>Alamat Email
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required>
                </label>
                <label>No. WhatsApp / HP
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx">
                </label>
                <label>Password Awal
                    <input type="password" name="password" required placeholder="Minimal 8 karakter">
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password">
                </label>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="toggleAddUserForm()" class="btn secondary">Batal</button>
                <button class="btn primary" type="submit">Simpan & Aktifkan Akun</button>
            </div>
        </form>
    </section>

    {{-- MAIN USER LIST & MONITORING TABLE --}}
    <section id="list-akun" class="section scroll-mt-24">
        @php
            $statusTabs = [
                'semua' => ['label' => 'Semua Akun', 'count' => $stats['total']],
                'aktif' => ['label' => 'Aktif', 'count' => $stats['aktif']],
                'jarang_aktif' => ['label' => 'Jarang Aktif (>30 Hari)', 'count' => $stats['jarang_aktif']],
                'nonaktif' => ['label' => 'Nonaktif', 'count' => $stats['nonaktif']],
            ];
        @endphp

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            
            {{-- Filter Toolbar --}}
            <div class="border-b border-slate-200 bg-slate-50/80 p-5 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    
                    {{-- Status Tabs --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($statusTabs as $key => $tab)
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $statusFilter === $key ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-blue-500' }}">
                                <span>{{ $tab['label'] }}</span>
                                <span class="rounded-full px-2 py-0.5 text-xs {{ $statusFilter === $key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                    {{ $tab['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    {{-- Search & Role Form --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        
                        {{-- Filter Role --}}
                        <select name="role" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <option value="semua" @selected($roleFilter === 'semua')>Semua Role</option>
                            <option value="mahasiswa" @selected($roleFilter === 'mahasiswa')>Mahasiswa</option>
                            <option value="dosen" @selected($roleFilter === 'dosen')>Dosen</option>
                        </select>

                        {{-- Sort --}}
                        <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <option value="terbaru" @selected($sort === 'terbaru')>Pendaftaran Baru</option>
                            <option value="terbaru_login" @selected($sort === 'terbaru_login')>Terakhir Aktif (Terbaru)</option>
                            <option value="terlama_login" @selected($sort === 'terlama_login')>Jarang Aktif (Terlama)</option>
                            <option value="nama" @selected($sort === 'nama')>Nama (A-Z)</option>
                        </select>

                        {{-- Search Input --}}
                        <div class="relative">
                            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama/email/NIM..."
                                class="w-48 sm:w-56 rounded-lg border border-slate-300 bg-white pl-8 pr-3 py-1.5 text-xs text-slate-800 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            <svg class="absolute left-2.5 top-2 h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>
                        <button type="submit" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">
                            Cari
                        </button>
                        @if ($search !== '' || $roleFilter !== 'semua' || $statusFilter !== 'semua')
                            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 dark:border-slate-700 dark:text-slate-400">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- User Rows --}}
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($users as $user)
                    @php
                        $isActive = $user->status_akun === 'aktif';
                        $nextStatus = $isActive ? 'nonaktif' : 'aktif';
                        $thirtyDaysAgo = now()->subDays(30);
                        $isRarelyActive = $isActive && (!$user->last_login_at || $user->last_login_at->lt($thirtyDaysAgo));
                    @endphp
                    <article class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            
                            {{-- User Info --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    {{-- Status Badge --}}
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-800' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-800' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $isActive ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>

                                    {{-- Role Badge --}}
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->role === 'dosen' ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300' : 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>

                                    {{-- Rarely Active Indicator --}}
                                    @if ($isRarelyActive)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-300 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-800">
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                            Jarang Aktif
                                        </span>
                                    @endif
                                </div>

                                <h2 class="mt-2 truncate text-base font-bold text-slate-900 dark:text-white">
                                    {{ $user->name }}
                                </h2>

                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->email }}</span>
                                    &middot; {{ $user->role === 'dosen' ? 'NIDN' : 'NIM' }}: <strong class="text-slate-800 dark:text-slate-200">{{ $user->nim ?: $user->nidn ?: '-' }}</strong>
                                    &middot; {{ $user->programStudi?->nama ?: 'Belum pilih prodi' }}
                                    @if($user->whatsapp)
                                        &middot; WA: {{ $user->whatsapp }}
                                    @endif
                                </p>

                                {{-- Last Login Information --}}
                                <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Terakhir Aktif:
                                        @if ($user->last_login_at)
                                            <strong class="{{ $user->last_login_at->lt($thirtyDaysAgo) ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-slate-200' }}">
                                                {{ $user->last_login_at->diffForHumans() }}
                                            </strong>
                                            <span class="text-slate-400">({{ $user->last_login_at->format('d M Y, H:i') }})</span>
                                        @else
                                            <span class="font-semibold text-amber-500">Belum pernah login</span>
                                        @endif
                                    </span>
                                    <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                                    <span>Terdaftar: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-wrap items-center gap-2.5">
                                
                                {{-- Switch Status (Aktif / Nonaktifkan) --}}
                                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="{{ $nextStatus }}">
                                    @if ($isActive)
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-rose-400 hover:bg-rose-50 hover:text-rose-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-rose-500 dark:hover:bg-rose-950/40 dark:hover:text-rose-300"
                                            onclick="return confirm('Nonaktifkan akun {{ $user->name }}? Pengguna tidak akan bisa login sampai diaktifkan kembali.')"
                                            title="Klik untuk menonaktifkan akun">
                                            <svg class="h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                            <span>Nonaktifkan</span>
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 dark:hover:bg-emerald-900/40"
                                            title="Klik untuk mengaktifkan kembali akun">
                                            <svg class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            <span>Aktifkan Akun</span>
                                        </button>
                                    @endif
                                </form>

                                {{-- Ubah Password Button --}}
                                <button type="button"
                                    onclick="togglePasswordForm('pw-form-{{ $user->id }}')"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800 shadow-sm transition hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300 dark:hover:bg-amber-900/40">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <span>Ubah Password</span>
                                </button>

                                {{-- Hapus Akun Button --}}
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen? Data yang berkaitan mungkin akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-500 shadow-sm transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-950/30" type="submit" title="Hapus akun permanen">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Collapsible Password Reset Form --}}
                        <div id="pw-form-{{ $user->id }}" class="hidden rounded-2xl border border-amber-300 bg-amber-50/70 p-4 dark:border-amber-800/60 dark:bg-amber-950/30">
                            <form method="POST" action="{{ route('admin.users.password', $user) }}">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center justify-between pb-2 border-b border-amber-200 dark:border-amber-900/50">
                                    <p class="text-xs font-bold text-amber-900 dark:text-amber-300 flex items-center gap-1.5">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                                        Ganti Password untuk Akun: <span class="underline">{{ $user->name }}</span>
                                    </p>
                                    <button type="button" onclick="togglePasswordForm('pw-form-{{ $user->id }}')" class="text-xs text-amber-700 hover:text-amber-900 dark:text-amber-400">&times; Batal</button>
                                </div>
                                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Password Baru <span class="text-rose-500">*</span></label>
                                        <input type="password" name="password" required placeholder="Minimal 8 karakter" minlength="8"
                                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-amber-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Ulangi Password Baru <span class="text-rose-500">*</span></label>
                                        <input type="password" name="password_confirmation" required placeholder="Konfirmasi password" minlength="8"
                                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-amber-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <button type="submit" class="rounded-lg bg-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-amber-700">
                                            Simpan Password Baru
                                        </button>
                                        <button type="button" onclick="togglePasswordForm('pw-form-{{ $user->id }}')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                        <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>
                        </svg>
                        <p class="mt-3 text-sm font-semibold">Tidak ada akun yang sesuai dengan filter ini.</p>
                        <p class="mt-1 text-xs text-slate-400">Coba ubah filter pencarian atau status akun.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-100 p-5 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        </div>
    </section>

    <script>
        function toggleAddUserForm() {
            const container = document.getElementById('add-user-container');
            if (container) {
                container.classList.toggle('hidden');
                if (!container.classList.contains('hidden')) {
                    container.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }

        function togglePasswordForm(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('user-role-select');
            const nimContainer = document.getElementById('nim-container');
            const nidnContainer = document.getElementById('nidn-container');
            const nimInput = document.getElementById('nim-input');
            const nidnInput = document.getElementById('nidn-input');

            function syncRoleFields() {
                if (!roleSelect) return;
                const isMahasiswa = roleSelect.value === 'mahasiswa';
                if (isMahasiswa) {
                    if (nimContainer) nimContainer.style.display = 'block';
                    if (nidnContainer) nidnContainer.style.display = 'none';
                    if (nimInput) nimInput.required = true;
                    if (nidnInput) nidnInput.required = false;
                } else {
                    if (nimContainer) nimContainer.style.display = 'none';
                    if (nidnContainer) nidnContainer.style.display = 'block';
                    if (nimInput) nimInput.required = false;
                    if (nidnInput) nidnInput.required = true;
                }
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', syncRoleFields);
                syncRoleFields();
            }
        });
    </script>
@endsection
