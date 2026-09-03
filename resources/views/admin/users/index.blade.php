@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
    @php
        $showList = request('show') === 'list';
    @endphp

    <section class="page-hero compact">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="eyebrow">Admin</p>
                <h1>Kelola User Mahasiswa & Dosen</h1>
            </div>
        </div>
    </section>

    <section class="section">
        <form method="POST" action="{{ route('admin.users.store') }}" class="auth-card wide">
            @csrf
            <p class="eyebrow">Buat Akun</p>
            <h2>Username dan Password dari Admin</h2>
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm font-semibold text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="error-box mb-4">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-grid">
                <label>Role
                    <select name="role" id="user-role-select" required>
                        <option value="mahasiswa" class="text-gray-500" @selected(old('role', 'mahasiswa') === 'mahasiswa')>Mahasiswa
                        </option>
                        <option value="dosen" class="text-gray-500" @selected(old('role') === 'dosen')>Dosen</option>
                    </select>
                </label>
                <label>Nama Lengkap
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label id="nim-container">NIM Mahasiswa
                    <input type="text" id="nim-input" class="nim-input" name="nim" value="{{ old('nim') }}"
                        placeholder="Masukkan NIM mahasiswa">
                </label>
                <label id="nidn-container" style="display: none;">NIDN Dosen
                    <input type="text" id="nidn-input" name="nidn" value="{{ old('nidn') }}" placeholder="Masukkan NIDN dosen">
                </label>
                <label>Program Studi
                    <select name="program_studi_id" required>
                        <option value="">Pilih prodi</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" class="text-gray-500"
                                @selected(old('program_studi_id') == $prodi->id)>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>No. WhatsApp
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}">
                </label>
                <label>Password Awal
                    <input type="password" name="password" required placeholder="Minimal 8 karakter">
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password awal">
                </label>
            </div>
            <button class="btn primary full" type="submit">Buat Akun Aktif</button>
        </form>
    </section>

    <script>
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
                    nimContainer.style.display = 'block';
                    nidnContainer.style.display = 'none';
                    nimInput.required = true;
                    nidnInput.required = false;
                } else {
                    nimContainer.style.display = 'none';
                    nidnContainer.style.display = 'block';
                    nimInput.required = false;
                    nidnInput.required = true;
                }
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', syncRoleFields);
                syncRoleFields();
            }
        });
    </script>

    <section id="list-akun" class="section scroll-mt-24">
        @php
            $activeCount = $userStatusCounts['aktif'] ?? 0;
            $inactiveCount = $userStatusCounts['nonaktif'] ?? 0;
            $filters = [
                'aktif' => ['label' => 'Akun Aktif', 'count' => $activeCount],
                'nonaktif' => ['label' => 'Akun Nonaktif', 'count' => $inactiveCount],
                'semua' => ['label' => 'Semua Akun', 'count' => $activeCount + $inactiveCount],
            ];
        @endphp

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-800 dark:bg-slate-950/40 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 11h-6" />
                            <path d="M19 8v6" />
                        </svg>
                        Daftar Akun
                    </p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">Akun Mahasiswa dan Dosen</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola status keaktifan, ubah password, atau hapus akun pengguna.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($filters as $key => $filter)
                        <a href="{{ route('admin.users.index', ['status' => $key]) }}#list-akun"
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition {{ $statusFilter === $key ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:text-blue-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-500 dark:hover:text-blue-300' }}">
                            {{ $filter['label'] }}
                            <span class="rounded-full px-2 py-0.5 text-xs {{ $statusFilter === $key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-200' }}">
                                {{ $filter['count'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($users as $user)
                    @php
                        $isActive = $user->status_akun === 'aktif';
                        $nextStatus = $isActive ? 'nonaktif' : 'aktif';
                    @endphp
                    <article class="flex flex-col gap-4 p-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $isActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-800' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' }}">
                                        {{ $isActive ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <h2 class="mt-2 truncate text-base font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->email }}</span> &middot; Identitas: {{ $user->nim ?: $user->nidn ?: '-' }} &middot; {{ $user->programStudi?->nama ?: 'Prodi belum dipilih' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                {{-- Status Switch --}}
                                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="{{ $nextStatus }}">
                                    <button type="submit"
                                        class="group inline-flex items-center gap-2 rounded-full border px-2.5 py-1.5 text-xs font-semibold transition {{ $isActive ? 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:border-slate-300 hover:bg-slate-100 hover:text-slate-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:border-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200' : 'border-slate-200 bg-slate-100 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300' }}"
                                        title="{{ $isActive ? 'Nonaktifkan akun' : 'Aktifkan akun' }}">
                                        <span class="relative h-5 w-9 rounded-full transition {{ $isActive ? 'bg-emerald-500' : 'bg-slate-400 dark:bg-slate-600' }}">
                                            <span class="absolute top-1 h-3 w-3 rounded-full bg-white shadow transition {{ $isActive ? 'left-5' : 'left-1' }}"></span>
                                        </span>
                                        {{ $isActive ? 'On' : 'Off' }}
                                    </button>
                                </form>

                                {{-- Edit Password Trigger --}}
                                <button type="button"
                                    onclick="togglePasswordForm('pw-form-{{ $user->id }}')"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                                    </svg>
                                    Edit Password
                                </button>

                                {{-- Hapus Akun --}}
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm shadow-rose-600/20 transition hover:bg-rose-700" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Collapsible Password Edit Form --}}
                        <div id="pw-form-{{ $user->id }}" class="hidden mt-3 rounded-2xl border border-amber-200 bg-amber-50/60 p-4 dark:border-amber-800/40 dark:bg-amber-950/20">
                            <form method="POST" action="{{ route('admin.users.password', $user) }}">
                                @csrf
                                @method('PATCH')
                                <p class="text-xs font-bold text-amber-800 dark:text-amber-300">Ubah Password Akun: {{ $user->name }}</p>
                                <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="text-xs text-slate-600 dark:text-slate-300">Password Baru</label>
                                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-slate-600 dark:text-slate-300">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <button type="submit" class="rounded-lg bg-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-amber-700">
                                            Simpan Password
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
                    <div class="p-10 text-center text-sm text-slate-500 dark:text-slate-400">
                        Belum ada user mahasiswa/dosen pada filter ini.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-5 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        </div>
    </section>

    <script>
        function togglePasswordForm(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }
    </script>
@endsection
