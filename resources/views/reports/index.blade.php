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
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-blue-700 shadow-md shadow-blue-900/20 transition hover:bg-blue-50 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Dokumen
                    </button>

                    <button type="button" onclick="openModal('modal-export')" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-950/20 transition hover:bg-emerald-700 sm:text-sm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Pusat Ekspor Laporan
                    </button>

                    <a href="{{ route('reports.export', ['format' => 'excel', 'scope' => 'all']) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600/90 px-3.5 py-2.5 text-xs font-bold text-white border border-white/20 backdrop-blur-sm transition hover:bg-blue-600 sm:text-sm" title="Ekspor Semua Data (Seluruh Tahun) ke Excel">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Excel (Semua)
                    </a>

                    <a href="{{ route('reports.export', ['format' => 'pdf', 'scope' => 'all']) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-sky-600/90 px-3.5 py-2.5 text-xs font-bold text-white border border-white/20 backdrop-blur-sm transition hover:bg-sky-600 sm:text-sm" title="Ekspor Semua Data (Seluruh Tahun) ke PDF">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                        PDF (Semua)
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

        {{-- ============ EKSPOR & DISTRIBUSI DOKUMEN PER KATEGORI & TAHUN ============ --}}
        <section class="mt-8 rounded-2xl sm:rounded-3xl border border-blue-100/90 bg-white p-5 sm:p-7 shadow-sm shadow-blue-900/5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-100">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Ekspor Per Kategori & Tahun
                        </span>
                        <span class="text-xs font-medium text-slate-400">| Unduh Langsung Excel & PDF</span>
                    </div>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900 sm:text-xl">
                        Unduh Laporan Repository Berdasarkan Kategori & Tahun
                    </h2>
                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Pilih tahun yang diinginkan untuk <strong>Skripsi</strong>, <strong>Magang</strong>, <strong>PKM</strong>, atau <strong>Penelitian Dosen</strong> lalu klik tombol Excel atau PDF.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="openModal('modal-export')" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition shadow-sm">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Pusat Ekspor Lengkap
                    </button>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
                {{-- 1. KARTU SKRIPSI --}}
                <div class="group relative flex flex-col justify-between rounded-2xl border border-blue-200/90 bg-gradient-to-b from-blue-50/70 via-white to-blue-50/30 p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide text-white shadow-sm shadow-blue-600/30">
                                🎓 Skripsi
                            </span>
                            <span class="text-xs font-bold text-blue-900">{{ number_format($countSkripsi) }} berkas</span>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900">Laporan Skripsi</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Tugas akhir sarjana / skripsi mahasiswa.</p>

                        <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-blue-100">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countSkripsi / $totalDokumen) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Dropdown Pilih Tahun --}}
                        <div class="mt-4">
                            <label for="export-year-skripsi" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Pilih Tahun Skripsi:
                            </label>
                            <div class="relative">
                                <select id="export-year-skripsi" onchange="updateCategoryExportLinks('skripsi')" class="w-full appearance-none rounded-xl border border-blue-200 bg-white py-2 pl-3 pr-8 text-xs font-bold text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-sm cursor-pointer">
                                    <option value="">Semua Tahun ({{ number_format($countSkripsi) }} berkas)</option>
                                    @foreach ($tahunList as $th)
                                        @php $thCount = $kategoriTahunCounts->get('skripsi')?->get($th) ?? 0; @endphp
                                        <option value="{{ $th }}" {{ request('kategori') === 'skripsi' && request('tahun') == $th ? 'selected' : '' }}>
                                            Tahun {{ $th }} ({{ $thCount }} berkas)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-blue-600">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Unduh Excel & PDF --}}
                    <div class="mt-4 pt-3 border-t border-blue-100">
                        <div class="grid grid-cols-2 gap-2">
                            <a id="btn-export-skripsi-excel" href="{{ route('reports.export', ['format' => 'excel', 'kategori' => 'skripsi']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition" title="Unduh Laporan Skripsi format Excel">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Excel
                            </a>
                            <a id="btn-export-skripsi-pdf" href="{{ route('reports.export', ['format' => 'pdf', 'kategori' => 'skripsi']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition" title="Unduh Laporan Skripsi format PDF">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 2. KARTU MAGANG --}}
                <div class="group relative flex flex-col justify-between rounded-2xl border border-indigo-200/90 bg-gradient-to-b from-indigo-50/70 via-white to-indigo-50/30 p-5 shadow-sm transition hover:border-indigo-400 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-600 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide text-white shadow-sm shadow-indigo-600/30">
                                💼 Magang
                            </span>
                            <span class="text-xs font-bold text-indigo-900">{{ number_format($countMagang) }} berkas</span>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900">Laporan Magang</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Kerja Praktik & Magang industri mahasiswa.</p>

                        <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-indigo-100">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countMagang / $totalDokumen) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Dropdown Pilih Tahun --}}
                        <div class="mt-4">
                            <label for="export-year-magang" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Pilih Tahun Magang:
                            </label>
                            <div class="relative">
                                <select id="export-year-magang" onchange="updateCategoryExportLinks('magang')" class="w-full appearance-none rounded-xl border border-indigo-200 bg-white py-2 pl-3 pr-8 text-xs font-bold text-slate-800 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-sm cursor-pointer">
                                    <option value="">Semua Tahun ({{ number_format($countMagang) }} berkas)</option>
                                    @foreach ($tahunList as $th)
                                        @php $thCount = $kategoriTahunCounts->get('magang')?->get($th) ?? 0; @endphp
                                        <option value="{{ $th }}" {{ request('kategori') === 'magang' && request('tahun') == $th ? 'selected' : '' }}>
                                            Tahun {{ $th }} ({{ $thCount }} berkas)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-indigo-600">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Unduh Excel & PDF --}}
                    <div class="mt-4 pt-3 border-t border-indigo-100">
                        <div class="grid grid-cols-2 gap-2">
                            <a id="btn-export-magang-excel" href="{{ route('reports.export', ['format' => 'excel', 'kategori' => 'magang']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition" title="Unduh Laporan Magang format Excel">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Excel
                            </a>
                            <a id="btn-export-magang-pdf" href="{{ route('reports.export', ['format' => 'pdf', 'kategori' => 'magang']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition" title="Unduh Laporan Magang format PDF">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3. KARTU PKM --}}
                <div class="group relative flex flex-col justify-between rounded-2xl border border-purple-200/90 bg-gradient-to-b from-purple-50/70 via-white to-purple-50/30 p-5 shadow-sm transition hover:border-purple-400 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-600 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide text-white shadow-sm shadow-purple-600/30">
                                💡 PKM
                            </span>
                            <span class="text-xs font-bold text-purple-900">{{ number_format($countPkm) }} berkas</span>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900">Laporan PKM</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Program Kreativitas Mahasiswa Dikti.</p>

                        <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-purple-100">
                            <div class="h-full bg-purple-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countPkm / $totalDokumen) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Dropdown Pilih Tahun --}}
                        <div class="mt-4">
                            <label for="export-year-pkm" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Pilih Tahun PKM:
                            </label>
                            <div class="relative">
                                <select id="export-year-pkm" onchange="updateCategoryExportLinks('pkm')" class="w-full appearance-none rounded-xl border border-purple-200 bg-white py-2 pl-3 pr-8 text-xs font-bold text-slate-800 transition focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 shadow-sm cursor-pointer">
                                    <option value="">Semua Tahun ({{ number_format($countPkm) }} berkas)</option>
                                    @foreach ($tahunList as $th)
                                        @php $thCount = $kategoriTahunCounts->get('pkm')?->get($th) ?? 0; @endphp
                                        <option value="{{ $th }}" {{ request('kategori') === 'pkm' && request('tahun') == $th ? 'selected' : '' }}>
                                            Tahun {{ $th }} ({{ $thCount }} berkas)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-purple-600">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Unduh Excel & PDF --}}
                    <div class="mt-4 pt-3 border-t border-purple-100">
                        <div class="grid grid-cols-2 gap-2">
                            <a id="btn-export-pkm-excel" href="{{ route('reports.export', ['format' => 'excel', 'kategori' => 'pkm']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition" title="Unduh Laporan PKM format Excel">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Excel
                            </a>
                            <a id="btn-export-pkm-pdf" href="{{ route('reports.export', ['format' => 'pdf', 'kategori' => 'pkm']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition" title="Unduh Laporan PKM format PDF">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 4. KARTU PENELITIAN DOSEN --}}
                <div class="group relative flex flex-col justify-between rounded-2xl border border-sky-200/90 bg-gradient-to-b from-sky-50/70 via-white to-sky-50/30 p-5 shadow-sm transition hover:border-sky-400 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-600 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide text-white shadow-sm shadow-sky-600/30">
                                🔬 Penelitian
                            </span>
                            <span class="text-xs font-bold text-sky-900">{{ number_format($countPenelitian) }} berkas</span>
                        </div>
                        <h3 class="mt-3 text-base font-bold text-slate-900">Penelitian Dosen</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Riset, publikasi & hibah penelitian dosen.</p>

                        <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-sky-100">
                            <div class="h-full bg-sky-600 rounded-full" style="width: {{ $totalDokumen > 0 ? ($countPenelitian / $totalDokumen) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Dropdown Pilih Tahun --}}
                        <div class="mt-4">
                            <label for="export-year-penelitian" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                Pilih Tahun Penelitian:
                            </label>
                            <div class="relative">
                                <select id="export-year-penelitian" onchange="updateCategoryExportLinks('penelitian')" class="w-full appearance-none rounded-xl border border-sky-200 bg-white py-2 pl-3 pr-8 text-xs font-bold text-slate-800 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20 shadow-sm cursor-pointer">
                                    <option value="">Semua Tahun ({{ number_format($countPenelitian) }} berkas)</option>
                                    @foreach ($tahunList as $th)
                                        @php $thCount = $kategoriTahunCounts->get('penelitian')?->get($th) ?? 0; @endphp
                                        <option value="{{ $th }}" {{ request('kategori') === 'penelitian' && request('tahun') == $th ? 'selected' : '' }}>
                                            Tahun {{ $th }} ({{ $thCount }} berkas)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-sky-600">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Unduh Excel & PDF --}}
                    <div class="mt-4 pt-3 border-t border-sky-100">
                        <div class="grid grid-cols-2 gap-2">
                            <a id="btn-export-penelitian-excel" href="{{ route('reports.export', ['format' => 'excel', 'kategori' => 'penelitian']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition" title="Unduh Laporan Penelitian format Excel">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Excel
                            </a>
                            <a id="btn-export-penelitian-pdf" href="{{ route('reports.export', ['format' => 'pdf', 'kategori' => 'penelitian']) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition" title="Unduh Laporan Penelitian format PDF">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                                PDF
                            </a>
                        </div>
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
                                    <th class="px-4 py-3.5 text-center">No</th>
                                    <th class="px-5 py-3.5">Judul</th>
                                    <th class="px-4 py-3.5">Penulis</th>
                                    <th class="px-4 py-3.5">NIM / NIDN</th>
                                    <th class="px-4 py-3.5">Kategori</th>
                                    <th class="px-4 py-3.5 text-center">Tahun</th>
                                    <th class="px-4 py-3.5">Status</th>
                                    <th class="px-4 py-3.5">Program Studi</th>
                                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Aksi Dokumen</th>
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
                                        <td class="px-4 py-4 text-center text-xs font-semibold text-slate-400">
                                            {{ $documents->firstItem() ? $documents->firstItem() + $loop->index : $loop->iteration }}
                                        </td>
                                        <td class="px-5 py-4 max-w-xs">
                                            <p class="font-bold text-slate-900 line-clamp-2" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                                        </td>
                                        <td class="px-4 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                            {{ $doc->nama }}
                                        </td>
                                        <td class="px-4 py-4 text-slate-600 font-mono text-xs whitespace-nowrap">
                                            {{ $doc->nim ?: ($doc->nidn ?: '-') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide text-blue-700 ring-1 ring-blue-100">
                                                {{ strtoupper($doc->kategori) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center font-semibold text-slate-800 whitespace-nowrap">{{ $doc->tahun }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $badgeClass }}">
                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                {{ strtoupper($doc->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $doc->programStudi?->nama ?: '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-center whitespace-nowrap">
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                {{-- Tombol Detail --}}
                                                <button type="button" onclick="openDetailModal({{ json_encode($doc) }})" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" title="Lihat detail lengkap dokumen ini">
                                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <span>Detail</span>
                                                </button>

                                                {{-- Tombol Edit --}}
                                                <button type="button" onclick="openEditModal({{ json_encode($doc) }})" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700 shadow-sm transition hover:border-blue-400 hover:bg-blue-600 hover:text-white" title="Ubah informasi dokumen">
                                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    <span>Edit</span>
                                                </button>

                                                {{-- Tombol Hapus --}}
                                                <form method="POST" action="{{ route('reports.destroy', $doc) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dokumen: &quot;{{ addslashes($doc->judul) }}&quot;?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition hover:border-rose-400 hover:bg-rose-600 hover:text-white" title="Hapus dokumen ini secara permanen">
                                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center text-slate-400 font-medium">
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

            <div id="detail-file-container" class="pt-2 hidden">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Berkas Dokumen Lampiran</span>
                <a id="detail-file-link" href="#" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-blue-50 border border-blue-200 px-3.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 transition shadow-sm">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                    <span>Buka / Unduh File PDF Dokumen</span>
                </a>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-4">
            <button type="button" onclick="closeModal('modal-detail')" class="rounded-xl bg-slate-800 px-5 py-2 text-xs font-bold text-white hover:bg-slate-900 transition">Tutup</button>
        </div>
    </div>
</div>

{{-- ============ MODAL EKSPOR LAPORAN ============ --}}
<div id="modal-export" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pusat Ekspor Laporan Dokumen</h3>
                    <p class="text-xs text-slate-500">Unduh data spesifik (Judul, Penulis, NIM, Kategori, Tahun, Status, Prodi).</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-export')" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="mt-5 space-y-4">
            {{-- Opsi 1: Ekspor Kustom Fleksibel (Pilih Kategori, Tahun & Status) --}}
            <div class="rounded-2xl border-2 border-blue-200 bg-gradient-to-br from-blue-50/60 via-white to-indigo-50/40 p-5 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-black text-white shadow-sm">1</span>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-950 sm:text-sm">
                        Ekspor Fleksibel Berdasarkan Kategori & Tahun
                    </h4>
                </div>
                <p class="mt-1 text-xs text-slate-500">
                    Tentukan kombinasi kategori dokumen (Skripsi, Magang, PKM, Penelitian), tahun berkas, dan status sesuai kebutuhan laporan Anda.
                </p>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Kategori Dokumen:
                        </label>
                        <select id="modal-custom-kategori" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Kategori</option>
                            <option value="skripsi">🎓 Skripsi</option>
                            <option value="magang">💼 Magang</option>
                            <option value="pkm">💡 PKM</option>
                            <option value="penelitian">🔬 Penelitian Dosen</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Pilih Tahun:
                        </label>
                        <select id="modal-custom-tahun" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $th)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>Tahun {{ $th }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Status Verifikasi:
                        </label>
                        <select id="modal-custom-status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Status</option>
                            <option value="terverifikasi">Terverifikasi</option>
                            <option value="pending">Pending</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-end gap-2.5 pt-3 border-t border-blue-100">
                    <button type="button" onclick="triggerModalCustomExport('excel')" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Unduh Format Excel (.xls)
                    </button>
                    <button type="button" onclick="triggerModalCustomExport('pdf')" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                        Unduh Format PDF (.pdf)
                    </button>
                </div>
            </div>

            {{-- Opsi 2: Ekspor Semua Data (Seluruh Tahun & Kategori) --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-slate-300">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-600 text-[10px] text-white">2</span>
                            Ekspor Seluruh Repository (Semua Tahun & Kategori)
                        </span>
                        <p class="mt-1 text-xs text-slate-500">Unduh seluruh berkas repository tanpa batasan apapun.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('reports.export', ['format' => 'excel', 'scope' => 'all']) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-slate-900 transition">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            Excel (Semua)
                        </a>
                        <a href="{{ route('reports.export', ['format' => 'pdf', 'scope' => 'all']) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-slate-900 transition">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                            PDF (Semua)
                        </a>
                    </div>
                </div>
            </div>

            {{-- Opsi 3: Ekspor Sesuai Filter Halaman Ini --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-slate-300">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-600 text-[10px] text-white">3</span>
                            Ekspor Sesuai Filter Tabel Halaman Ini
                        </span>
                        <p class="mt-1 text-xs text-slate-500">
                            Unduh data sesuai kombinasi pencarian, kategori, tahun, & status pada tabel saat ini.
                            @if(request()->anyFilled(['search', 'kategori', 'tahun', 'status']))
                                <span class="font-semibold text-blue-600 block sm:inline">(Filter aktif terdeteksi)</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('reports.export', ['format' => 'excel'] + request()->query()) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            Excel Filter
                        </a>
                        <a href="{{ route('reports.export', ['format' => 'pdf'] + request()->query()) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                            PDF Filter
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-4">
            <button type="button" onclick="closeModal('modal-export')" class="rounded-xl bg-slate-100 px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">Tutup</button>
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

    function updateCategoryExportLinks(kategori) {
        const select = document.getElementById('export-year-' + kategori);
        if (!select) return;
        const year = select.value;
        const btnExcel = document.getElementById('btn-export-' + kategori + '-excel');
        const btnPdf = document.getElementById('btn-export-' + kategori + '-pdf');

        const baseUrlExcel = "{{ url('/laporan/export/excel') }}";
        const baseUrlPdf = "{{ url('/laporan/export/pdf') }}";

        let params = '?kategori=' + encodeURIComponent(kategori);
        if (year) {
            params += '&tahun=' + encodeURIComponent(year);
        }

        if (btnExcel) btnExcel.href = baseUrlExcel + params;
        if (btnPdf) btnPdf.href = baseUrlPdf + params;
    }

    function triggerModalCustomExport(format) {
        const kategori = document.getElementById('modal-custom-kategori').value;
        const tahun = document.getElementById('modal-custom-tahun').value;
        const status = document.getElementById('modal-custom-status').value;

        const baseUrl = format === 'excel' ? "{{ url('/laporan/export/excel') }}" : "{{ url('/laporan/export/pdf') }}";
        const params = new URLSearchParams();

        if (kategori) params.append('kategori', kategori);
        if (tahun) params.append('tahun', tahun);
        if (status) params.append('status', status);

        const queryString = params.toString();
        const finalUrl = baseUrl + (queryString ? '?' + queryString : '');
        window.location.href = finalUrl;
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

        const fileContainer = document.getElementById('detail-file-container');
        const fileLink = document.getElementById('detail-file-link');
        if (fileContainer && fileLink) {
            if (doc.file_dokumen) {
                fileContainer.classList.remove('hidden');
                fileLink.href = `/storage/${doc.file_dokumen}`;
            } else {
                fileContainer.classList.add('hidden');
            }
        }

        openModal('modal-detail');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('modal-create');
            closeModal('modal-edit');
            closeModal('modal-detail');
            closeModal('modal-export');
        }
    });
</script>
@endsection

