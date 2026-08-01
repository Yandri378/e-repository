@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.4s ease both;
    }
</style>

<div class="min-h-screen bg-gradient-to-b from-blue-50/60 via-slate-50 to-white pb-12">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        {{-- ============ HERO ============ --}}
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 px-6 py-8 shadow-xl shadow-blue-950/20 sm:rounded-3xl sm:px-10 sm:py-10">
            {{-- Decorative ambient lighting --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-10 h-56 w-56 rounded-full bg-sky-400/20 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/40 backdrop-blur-md sm:h-14 sm:w-14 shadow-inner">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-400/20 border border-sky-300/40 px-3 py-1 text-xs font-bold text-sky-200 backdrop-blur-md shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-sky-300 animate-pulse"></span>
                                Laporan & Statistik Admin
                            </span>
                        </div>
                        <h1 class="mt-2.5 text-2xl font-extrabold tracking-tight text-white sm:text-3xl lg:text-4xl drop-shadow-sm">
                            Laporan & Rekapitulasi Repository
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-xs font-medium text-blue-50 sm:text-sm leading-relaxed">
                            Pantau ringkasan statistik, rekapitulasi data upload, serta kelola dokumen secara langsung.
                        </p>
                    </div>
                </div>

                {{-- Hero Quick Actions --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-blue-700 shadow-md shadow-blue-900/20 transition hover:bg-blue-50 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Dokumen
                    </button>

                    <a href="{{ route('reports.export', ['format' => 'excel'] + request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-900/20 transition hover:bg-emerald-700 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Export Excel
                    </a>

                    <a href="{{ route('reports.export', ['format' => 'pdf'] + request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-rose-900/20 transition hover:bg-rose-700 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                        Export PDF
                    </a>
                </div>
            </div>
        </section>

        {{-- FLASH MESSAGES --}}
        @if (session('status'))
            <div class="mt-6 flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-emerald-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 text-emerald-600"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-xs font-semibold sm:text-sm">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="text-xs font-semibold sm:text-sm">{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- ============ STATISTICAL CARDS ============ --}}
        <section class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Card 1: Total --}}
            <div class="animate-fade-in rounded-2xl border border-blue-100 bg-white p-5 shadow-sm shadow-blue-900/5 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Dokumen</span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($totalDokumen) }}</span>
                    <span class="text-xs font-medium text-slate-400">berkas</span>
                </div>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            {{-- Card 2: Terverifikasi --}}
            <div class="animate-fade-in rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm shadow-emerald-900/5 transition hover:shadow-md" style="animation-delay: 0.05s;">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Terverifikasi</span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($totalTerverifikasi) }}</span>
                    <span class="text-xs font-semibold text-emerald-600">
                        ({{ $totalDokumen > 0 ? round(($totalTerverifikasi / $totalDokumen) * 100, 1) : 0 }}%)
                    </span>
                </div>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $totalDokumen > 0 ? ($totalTerverifikasi / $totalDokumen) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Card 3: Pending --}}
            <div class="animate-fade-in rounded-2xl border border-amber-100 bg-white p-5 shadow-sm shadow-amber-900/5 transition hover:shadow-md" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Pending / Menunggu</span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($totalPending) }}</span>
                    <span class="text-xs font-semibold text-amber-600">
                        ({{ $totalDokumen > 0 ? round(($totalPending / $totalDokumen) * 100, 1) : 0 }}%)
                    </span>
                </div>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ $totalDokumen > 0 ? ($totalPending / $totalDokumen) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Card 4: Ditolak --}}
            <div class="animate-fade-in rounded-2xl border border-rose-100 bg-white p-5 shadow-sm shadow-rose-900/5 transition hover:shadow-md" style="animation-delay: 0.15s;">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Ditolak</span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($totalDitolak) }}</span>
                    <span class="text-xs font-semibold text-rose-600">
                        ({{ $totalDokumen > 0 ? round(($totalDitolak / $totalDokumen) * 100, 1) : 0 }}%)
                    </span>
                </div>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full bg-rose-500 rounded-full" style="width: {{ $totalDokumen > 0 ? ($totalDitolak / $totalDokumen) * 100 : 0 }}%"></div>
                </div>
            </div>
        </section>

        {{-- ============ KATEGORI BREAKDOWN CHART CARD ============ --}}
        <section class="mt-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Distribusi Kategori Dokumen</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Skripsi --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                    <div class="flex items-center justify-between text-xs font-bold text-blue-900">
                        <span>Skripsi</span>
                        <span>{{ number_format($countSkripsi) }}</span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-blue-200/60">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countSkripsi / $totalDokumen) * 100 : 0 }}%"></div>
                    </div>
                </div>
                {{-- Magang --}}
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                    <div class="flex items-center justify-between text-xs font-bold text-indigo-900">
                        <span>Magang</span>
                        <span>{{ number_format($countMagang) }}</span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-indigo-200/60">
                        <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countMagang / $totalDokumen) * 100 : 0 }}%"></div>
                    </div>
                </div>
                {{-- PKM --}}
                <div class="rounded-xl border border-purple-100 bg-purple-50/50 p-4">
                    <div class="flex items-center justify-between text-xs font-bold text-purple-900">
                        <span>PKM</span>
                        <span>{{ number_format($countPkm) }}</span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-purple-200/60">
                        <div class="h-full bg-purple-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countPkm / $totalDokumen) * 100 : 0 }}%"></div>
                    </div>
                </div>
                {{-- Penelitian --}}
                <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                    <div class="flex items-center justify-between text-xs font-bold text-sky-900">
                        <span>Penelitian</span>
                        <span>{{ number_format($countPenelitian) }}</span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-sky-200/60">
                        <div class="h-full bg-sky-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countPenelitian / $totalDokumen) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ FILTER TOOLBAR ============ --}}
        <section class="mt-8 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="min-w-[14rem] flex-1">
                    <label class="sr-only">Cari</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nama, NIM/NIDN..." class="w-full rounded-xl border border-slate-200 pl-9 pr-4 py-2 text-xs font-medium text-slate-700 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <select name="kategori" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="">Semua Kategori</option>
                        <option value="skripsi" {{ request('kategori') === 'skripsi' ? 'selected' : '' }}>Skripsi</option>
                        <option value="magang" {{ request('kategori') === 'magang' ? 'selected' : '' }}>Magang</option>
                        <option value="pkm" {{ request('kategori') === 'pkm' ? 'selected' : '' }}>PKM</option>
                        <option value="penelitian" {{ request('kategori') === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <select name="tahun" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>Tahun {{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="terverifikasi" {{ request('status') === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                    </button>

                    @if(request()->anyFilled(['search', 'kategori', 'tahun', 'status']))
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 sm:text-sm">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        {{-- ============ TAB NAVIGATION ============ --}}
        <section class="mt-8">
            <div class="flex border-b border-slate-200">
                <button type="button" id="tab-crud-btn" onclick="switchTab('crud')" class="border-b-2 border-blue-600 px-5 py-3 text-xs font-bold text-blue-600 transition sm:text-sm">
                    📁 Kelola Data Dokumen (CRUD)
                </button>
                <button type="button" id="tab-rekap-btn" onclick="switchTab('rekap')" class="border-b-2 border-transparent px-5 py-3 text-xs font-bold text-slate-500 hover:text-slate-700 transition sm:text-sm">
                    📊 Rekapitulasi Statistik
                </button>
            </div>

            {{-- TAB 1: CRUD DOCUMENTS TABLE --}}
            <div id="tab-crud-content" class="mt-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                        <h3 class="text-sm font-bold text-slate-800">Daftar Berkas Dokumen</h3>
                        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-blue-700">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Dokumen Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3.5">Judul & Penulis</th>
                                    <th class="px-6 py-3.5">Kategori</th>
                                    <th class="px-6 py-3.5">Tahun</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5">Program Studi</th>
                                    <th class="px-6 py-3.5 text-right">Aksi (CRUD)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse ($documents as $doc)
                                    @php
                                        $badgeClass = [
                                            'terverifikasi' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                                            'ditolak'       => 'bg-rose-100 text-rose-700 ring-rose-200',
                                        ][$doc->status] ?? 'bg-amber-100 text-amber-700 ring-amber-200';
                                    @endphp
                                    <tr class="transition hover:bg-blue-50/40">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-slate-900 line-clamp-1" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500">
                                                {{ $doc->nama }} @if($doc->nim || $doc->nidn) • ({{ $doc->nim ?: $doc->nidn }}) @endif
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide text-blue-700 ring-1 ring-blue-100">
                                                {{ strtoupper($doc->kategori) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $doc->tahun }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $badgeClass }}">
                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                {{ strtoupper($doc->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-600">
                                            {{ $doc->programStudi?->nama ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                {{-- Detail --}}
                                                <button type="button" onclick="openDetailModal({{ json_encode($doc) }})" class="rounded-lg border border-slate-200 bg-white p-1.5 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition" title="Lihat Detail">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                                {{-- Edit --}}
                                                <button type="button" onclick="openEditModal({{ json_encode($doc) }})" class="rounded-lg border border-blue-200 bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 transition" title="Edit Dokumen">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                </button>
                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('reports.destroy', $doc) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dokumen ini secara permanen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 p-1.5 text-rose-600 hover:bg-rose-100 transition" title="Hapus Dokumen">
                                                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                            Tidak ada data dokumen ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($documents->hasPages())
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $documents->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- TAB 2: REKAPITULASI TABLE --}}
            <div id="tab-rekap-content" class="mt-6 hidden">
                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                        <h3 class="text-sm font-bold text-slate-800">Tabel Rekapitulasi Laporan Aggregate</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3.5">Kategori</th>
                                    <th class="px-6 py-3.5">Tahun</th>
                                    <th class="px-6 py-3.5">Bulan</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5">Mode Input</th>
                                    <th class="px-6 py-3.5 text-right">Total Berkas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse ($reports as $row)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-4 font-bold text-slate-900">{{ ucfirst($row->kategori) }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $row->tahun }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{ $row->bulan ?: '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $row->status === 'terverifikasi' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : ($row->status === 'ditolak' ? 'bg-rose-100 text-rose-700 ring-rose-200' : 'bg-amber-100 text-amber-700 ring-amber-200') }}">
                                                {{ strtoupper($row->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 uppercase text-xs font-semibold">{{ $row->jenis_input }}</td>
                                        <td class="px-6 py-4 text-right font-black text-blue-700 text-base">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                            Belum ada data rekapitulasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- ============ MODAL TAMBAH DOKUMEN (CREATE) ============ --}}
<div id="modal-create" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Tambah Dokumen Laporan Baru</h3>
            <button type="button" onclick="closeModal('modal-create')" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('reports.store') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700">Judul Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" required placeholder="Masukkan judul skripsi/laporan..." class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700">Nama Penulis <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Nama Mahasiswa/Dosen" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700">NIM / NIDN</label>
                    <input type="text" name="nim" placeholder="Nomor Induk (opsional)" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="skripsi">Skripsi</option>
                        <option value="magang">Magang</option>
                        <option value="pkm">PKM</option>
                        <option value="penelitian">Penelitian</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">Tahun <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun" value="{{ date('Y') }}" required min="2000" max="2099" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="pending">Pending</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700">Program Studi</label>
                <select name="program_studi_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodiList as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700">Abstrak / Ringkasan</label>
                <textarea name="abstrak" rows="3" placeholder="Deskripsi singkat abstrak..." class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-create')" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white hover:bg-blue-700 transition">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>

{{-- ============ MODAL EDIT DOKUMEN (UPDATE) ============ --}}
<div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Edit Data Dokumen Laporan</h3>
            <button type="button" onclick="closeModal('modal-edit')" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="form-edit" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-700">Judul Dokumen <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-judul" name="judul" required class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700">Nama Penulis <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama" required class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700">NIM / NIDN</label>
                    <input type="text" id="edit-nim" name="nim" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                    <select id="edit-kategori" name="kategori" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="skripsi">Skripsi</option>
                        <option value="magang">Magang</option>
                        <option value="pkm">PKM</option>
                        <option value="penelitian">Penelitian</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">Tahun <span class="text-rose-500">*</span></label>
                    <input type="number" id="edit-tahun" name="tahun" required min="2000" max="2099" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">Status <span class="text-rose-500">*</span></label>
                    <select id="edit-status" name="status" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                        <option value="pending">Pending</option>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700">Program Studi</label>
                <select id="edit-prodi" name="program_studi_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodiList as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700">Abstrak / Ringkasan</label>
                <textarea id="edit-abstrak" name="abstrak" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-sm"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-edit')" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white hover:bg-blue-700 transition">Perbarui Dokumen</button>
            </div>
        </form>
    </div>
</div>

{{-- ============ MODAL DETAIL DOKUMEN ============ --}}
<div id="modal-detail" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Detail Dokumen Laporan</h3>
            <button type="button" onclick="closeModal('modal-detail')" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="mt-4 space-y-3 text-xs sm:text-sm text-slate-700">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Judul</span>
                <p id="detail-judul" class="font-bold text-slate-900 mt-0.5 text-base sm:text-lg"></p>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Penulis</span>
                    <p id="detail-nama" class="font-semibold text-slate-800"></p>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">NIM / NIDN</span>
                    <p id="detail-nim" class="font-semibold text-slate-800"></p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 pt-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Kategori</span>
                    <p id="detail-kategori" class="font-semibold text-blue-700 uppercase"></p>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Tahun</span>
                    <p id="detail-tahun" class="font-semibold text-slate-800"></p>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Status</span>
                    <p id="detail-status" class="font-semibold text-slate-800 uppercase"></p>
                </div>
            </div>

            <div class="pt-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Program Studi</span>
                <p id="detail-prodi" class="font-semibold text-slate-800"></p>
            </div>

            <div class="pt-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Abstrak</span>
                <p id="detail-abstrak" class="mt-1 rounded-xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-600 max-h-48 overflow-y-auto"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-4">
            <button type="button" onclick="closeModal('modal-detail')" class="rounded-xl bg-slate-800 px-5 py-2 text-xs font-bold text-white hover:bg-slate-900 transition">Tutup</button>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const crudBtn = document.getElementById('tab-crud-btn');
        const rekapBtn = document.getElementById('tab-rekap-btn');
        const crudContent = document.getElementById('tab-crud-content');
        const rekapContent = document.getElementById('tab-rekap-content');

        if (tab === 'crud') {
            crudBtn.className = 'border-b-2 border-blue-600 px-5 py-3 text-xs font-bold text-blue-600 transition sm:text-sm';
            rekapBtn.className = 'border-b-2 border-transparent px-5 py-3 text-xs font-bold text-slate-500 hover:text-slate-700 transition sm:text-sm';
            crudContent.classList.remove('hidden');
            rekapContent.classList.add('hidden');
        } else {
            rekapBtn.className = 'border-b-2 border-blue-600 px-5 py-3 text-xs font-bold text-blue-600 transition sm:text-sm';
            crudBtn.className = 'border-b-2 border-transparent px-5 py-3 text-xs font-bold text-slate-500 hover:text-slate-700 transition sm:text-sm';
            rekapContent.classList.remove('hidden');
            crudContent.classList.add('hidden');
        }
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function openCreateModal() {
        openModal('modal-create');
    }

    function openEditModal(doc) {
        document.getElementById('form-edit').action = `/laporan/${doc.id}`;
        document.getElementById('edit-judul').value = doc.judul || '';
        document.getElementById('edit-nama').value = doc.nama || '';
        document.getElementById('edit-nim').value = doc.nim || doc.nidn || '';
        document.getElementById('edit-kategori').value = doc.kategori || 'skripsi';
        document.getElementById('edit-tahun').value = doc.tahun || new Date().getFullYear();
        document.getElementById('edit-status').value = doc.status || 'pending';
        document.getElementById('edit-prodi').value = doc.program_studi_id || '';
        document.getElementById('edit-abstrak').value = doc.abstrak || '';

        openModal('modal-edit');
    }

    function openDetailModal(doc) {
        document.getElementById('detail-judul').innerText = doc.judul || '-';
        document.getElementById('detail-nama').innerText = doc.nama || '-';
        document.getElementById('detail-nim').innerText = doc.nim || doc.nidn || '-';
        document.getElementById('detail-kategori').innerText = doc.kategori || '-';
        document.getElementById('detail-tahun').innerText = doc.tahun || '-';
        document.getElementById('detail-status').innerText = doc.status || '-';
        document.getElementById('detail-prodi').innerText = doc.program_studi ? doc.program_studi.nama : '-';
        document.getElementById('detail-abstrak').innerText = doc.abstrak || 'Tidak ada abstrak yang dicantumkan.';

        openModal('modal-detail');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('modal-create');
            closeModal('modal-edit');
            closeModal('modal-detail');
        }
    });
</script>
@endsection
