@extends('layouts.admin')

@section('title', 'Kelola Upload')

@section('content')

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .doc-card {
        animation: fadeInUp 0.45s ease both;
    }
    .doc-card:nth-child(1) { animation-delay: 0.03s; }
    .doc-card:nth-child(2) { animation-delay: 0.08s; }
    .doc-card:nth-child(3) { animation-delay: 0.13s; }
    .doc-card:nth-child(4) { animation-delay: 0.18s; }
    .doc-card:nth-child(5) { animation-delay: 0.23s; }
</style>

<div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-5xl px-3 py-6 sm:px-6 sm:py-10 lg:px-8">

        {{-- ============ HERO ============ --}}
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 px-5 py-7 shadow-lg shadow-blue-900/20 sm:rounded-3xl sm:px-10 sm:py-10">
            {{-- decorative blobs --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-10 h-48 w-48 rounded-full bg-sky-300/20 blur-2xl"></div>

            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/30 backdrop-blur-sm sm:h-14 sm:w-14 sm:rounded-2xl">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="sm:h-7 sm:w-7">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-100 sm:text-xs">Admin</p>
                        <h1 class="mt-1 truncate text-lg font-bold text-white sm:text-2xl md:text-3xl">Kelola Semua Upload Repository</h1>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.documents.verify-all') }}"
                    onsubmit="return confirm(@js('Verifikasi semua upload pending menjadi terverifikasi?'))">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition sm:w-auto {{ $bulkPendingCount > 0 ? 'bg-white text-blue-700 shadow-lg shadow-blue-900/20 hover:scale-[1.02] hover:bg-blue-50' : 'cursor-not-allowed bg-white/20 text-white/60 ring-1 ring-white/25' }}"
                        {{ $bulkPendingCount > 0 ? '' : 'disabled' }}
                        title="{{ $bulkPendingCount > 0 ? 'Verifikasi semua upload pending' : 'Tidak ada upload pending yang bisa diverifikasi' }}">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                        Terverifikasi Semua
                        @if ($bulkPendingCount > 0)
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs text-blue-700">{{ $bulkPendingCount }}</span>
                        @endif
                    </button>
                </form>
            </div>
        </section>

        {{-- ============ ERROR ============ --}}
        @if ($errors->any())
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-red-700 shadow-sm sm:mt-6 sm:rounded-2xl sm:px-5 sm:py-4">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 shrink-0">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p class="text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- ============ FILTER TABS ============ --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-blue-100 bg-white p-2.5 shadow-sm shadow-blue-900/5 sm:mt-8 sm:p-3">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                {{-- Button 1: Belum Terverifikasi (Pending) --}}
                <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition-all duration-200 sm:text-sm {{ $status === 'pending' ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-600/25 ring-2 ring-blue-600' : 'bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-700 ring-1 ring-slate-200/80' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Belum Terverifikasi</span>
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $status === 'pending' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">
                        {{ $pendingCount }}
                    </span>
                </a>

                {{-- Button 2: Data Terverifikasi --}}
                <a href="{{ route('admin.documents.index', ['status' => 'terverifikasi']) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition-all duration-200 sm:text-sm {{ $status === 'terverifikasi' ? 'bg-gradient-to-r from-emerald-600 to-emerald-700 text-white shadow-md shadow-emerald-600/25 ring-2 ring-emerald-600' : 'bg-slate-50 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 ring-1 ring-slate-200/80' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Data Terverifikasi</span>
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $status === 'terverifikasi' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $verifiedCount }}
                    </span>
                </a>

                {{-- Button 3: Ditolak --}}
                <a href="{{ route('admin.documents.index', ['status' => 'ditolak']) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition-all duration-200 sm:text-sm {{ $status === 'ditolak' ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-md shadow-red-600/25 ring-2 ring-red-600' : 'bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-700 ring-1 ring-slate-200/80' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>Ditolak</span>
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $status === 'ditolak' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-800' }}">
                        {{ $rejectedCount }}
                    </span>
                </a>

                {{-- Button 4: Semua Data --}}
                <a href="{{ route('admin.documents.index', ['status' => 'all']) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition-all duration-200 sm:text-sm {{ $status === 'all' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-md shadow-slate-800/25 ring-2 ring-slate-700' : 'bg-slate-50 text-slate-600 hover:bg-slate-200 ring-1 ring-slate-200/80' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                    <span>Semua Data</span>
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-800' }}">
                        {{ $allCount }}
                    </span>
                </a>
            </div>
        </div>

        {{-- ============ LIST ============ --}}
        <section class="mt-4 space-y-5 sm:mt-6 sm:space-y-6">
            @forelse ($documents as $document)
                @php
                    $statusStyle = [
                        'terverifikasi' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                        'ditolak'       => 'bg-red-100 text-red-700 ring-red-200',
                    ][$document->status] ?? 'bg-amber-100 text-amber-700 ring-amber-200';
                @endphp

                <article class="doc-card overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-md shadow-blue-900/5 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-900/10">

                    {{-- Card header --}}
                    <div class="border-b border-blue-50 bg-gradient-to-r from-blue-50/60 to-white px-4 py-4 sm:px-6 sm:py-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $statusStyle }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ strtoupper($document->status) }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold text-blue-700 ring-1 ring-blue-100">
                                {{ strtoupper($document->kategori) }}
                            </span>
                        </div>

                        <h2 class="mt-3 break-words text-base font-bold text-slate-800 sm:text-lg md:text-xl">{{ $document->judul }}</h2>

                        <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 sm:text-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ $document->nama }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ $document->tahun }}
                            </span>
                        </div>

                        @if (in_array($document->kategori, ['skripsi', 'magang'], true) && $document->dosen_pembimbing_id)
                            <div class="mt-2 flex flex-wrap items-center gap-1.5 text-xs text-slate-500 sm:text-sm">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                <span>Dosen pembimbing: <strong class="text-slate-700">{{ $document->dosenPembimbing?->name ?: '-' }}</strong></span>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $document->dosen_approved_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $document->dosen_approved_at ? 'Sudah ACC dosen' : 'Menunggu ACC dosen' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Bebas Pustaka Prerequisite Panel (only for skripsi/magang) --}}
                    @if (in_array($document->kategori, ['skripsi', 'magang'], true))
                        <div class="border-b border-blue-50 bg-gradient-to-br from-blue-50/70 via-white to-white px-4 py-4 sm:px-6 sm:py-5">
                            <p class="flex items-center gap-2 text-sm font-bold text-blue-800">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                Konfirmasi Bebas Pustaka
                            </p>

                            <div class="mt-4 space-y-2.5">

                                {{-- 1. Hard Copy --}}
                                <div class="flex flex-wrap items-center gap-3 rounded-xl border {{ $document->hard_copy_submitted ? 'border-emerald-100 bg-emerald-50/60' : 'border-slate-100 bg-slate-50' }} px-3.5 py-3 transition-colors duration-300 sm:px-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $document->hard_copy_submitted ? 'bg-emerald-500 text-white' : 'bg-slate-300 text-white' }}">
                                        {{ $document->hard_copy_submitted ? '✓' : '✗' }}
                                    </span>
                                    <span class="min-w-[10rem] flex-1 text-sm text-slate-700">Hard copy diserahkan ke perpustakaan</span>
                                    <form method="POST" action="{{ route('admin.documents.bebas-pustaka', $document) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="field" value="hard_copy_submitted">
                                        <input type="hidden" name="value" value="{{ $document->hard_copy_submitted ? '0' : '1' }}">
                                        <button type="submit" class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all duration-200 hover:scale-105 {{ $document->hard_copy_submitted ? 'bg-slate-200 text-slate-600 hover:bg-slate-300' : 'bg-blue-600 text-white shadow-sm shadow-blue-600/30 hover:bg-blue-700' }}">
                                            {{ $document->hard_copy_submitted ? 'Batalkan' : 'Konfirmasi' }}
                                        </button>
                                    </form>
                                </div>

                                {{-- 2. PDF Kelengkapan --}}
                                <div class="flex flex-wrap items-center gap-3 rounded-xl border {{ $document->pdf_kelengkapan_confirmed ? 'border-emerald-100 bg-emerald-50/60' : 'border-slate-100 bg-slate-50' }} px-3.5 py-3 transition-colors duration-300 sm:px-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $document->pdf_kelengkapan_confirmed ? 'bg-emerald-500 text-white' : 'bg-slate-300 text-white' }}">
                                        {{ $document->pdf_kelengkapan_confirmed ? '✓' : '✗' }}
                                    </span>
                                    <span class="min-w-[10rem] flex-1 text-sm text-slate-700">Soft copy PDF lengkap (pengesahan + persetujuan + orisinalitas)</span>
                                    <form method="POST" action="{{ route('admin.documents.bebas-pustaka', $document) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="field" value="pdf_kelengkapan_confirmed">
                                        <input type="hidden" name="value" value="{{ $document->pdf_kelengkapan_confirmed ? '0' : '1' }}">
                                        <button type="submit" class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all duration-200 hover:scale-105 {{ $document->pdf_kelengkapan_confirmed ? 'bg-slate-200 text-slate-600 hover:bg-slate-300' : 'bg-blue-600 text-white shadow-sm shadow-blue-600/30 hover:bg-blue-700' }}">
                                            {{ $document->pdf_kelengkapan_confirmed ? 'Batalkan' : 'Konfirmasi' }}
                                        </button>
                                    </form>
                                </div>

                                {{-- 3. Pinjaman Buku --}}
                                <div class="flex flex-wrap items-center gap-3 rounded-xl border {{ !$document->has_active_loans ? 'border-emerald-100 bg-emerald-50/60' : 'border-amber-200 bg-amber-50' }} px-3.5 py-3 transition-colors duration-300 sm:px-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ !$document->has_active_loans ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-white' }}">
                                        {{ !$document->has_active_loans ? '✓' : '!' }}
                                    </span>
                                    <span class="min-w-[10rem] flex-1 text-sm text-slate-700">
                                        {{ $document->has_active_loans ? 'Masih ada pinjaman buku aktif' : 'Tidak ada pinjaman buku aktif' }}
                                    </span>
                                    <form method="POST" action="{{ route('admin.documents.bebas-pustaka', $document) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="field" value="has_active_loans">
                                        <input type="hidden" name="value" value="{{ $document->has_active_loans ? '0' : '1' }}">
                                        <button type="submit" class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all duration-200 hover:scale-105 {{ $document->has_active_loans ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/30 hover:bg-amber-600' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                            {{ $document->has_active_loans ? 'Tandai Lunas' : 'Tandai Masih Pinjam' }}
                                        </button>
                                    </form>
                                </div>

                                {{-- ACC Dosen status (read-only) --}}
                                <div class="flex flex-wrap items-center gap-3 rounded-xl border {{ $document->dosen_approved_at ? 'border-emerald-100 bg-emerald-50/60' : 'border-slate-100 bg-slate-50' }} px-3.5 py-3 sm:px-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $document->dosen_approved_at ? 'bg-emerald-500 text-white' : 'bg-slate-300 text-white' }}">
                                        {{ $document->dosen_approved_at ? '✓' : '✗' }}
                                    </span>
                                    <span class="min-w-[10rem] flex-1 text-sm text-slate-700">
                                        ACC dosen pembimbing
                                        @if($document->dosen_approved_at)
                                            <span class="text-xs text-slate-400">({{ $document->dosen_approved_at->format('d/m/Y') }})</span>
                                        @endif
                                    </span>
                                    <span class="text-xs font-semibold text-slate-400">{{ $document->dosen_approved_at ? 'Sudah ACC' : 'Menunggu dosen' }}</span>
                                </div>

                                {{-- 5. PDF Declaration by Student (read-only) --}}
                                <div class="flex flex-wrap items-center gap-3 rounded-xl border {{ $document->pdf_kelengkapan_deklarasi ? 'border-emerald-100 bg-emerald-50/60' : 'border-slate-100 bg-slate-50' }} px-3.5 py-3 sm:px-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $document->pdf_kelengkapan_deklarasi ? 'bg-emerald-500 text-white' : 'bg-slate-300 text-white' }}">
                                        {{ $document->pdf_kelengkapan_deklarasi ? '✓' : '✗' }}
                                    </span>
                                    <span class="min-w-[10rem] flex-1 text-sm text-slate-700">
                                        Mahasiswa telah mendeklarasikan kelengkapan PDF
                                        @if($document->pdf_page_count)
                                            <span class="text-xs text-slate-400">({{ $document->pdf_page_count }} halaman terdeteksi)</span>
                                        @endif
                                    </span>
                                    <span class="text-xs font-semibold text-slate-400">{{ $document->pdf_kelengkapan_deklarasi ? 'Sudah dideklarasikan' : 'Belum dideklarasikan' }}</span>
                                </div>
                            </div>

                            @php $allMet = $document->canDownloadBebasPustaka(); @endphp
                            <div class="mt-4 flex items-center justify-center gap-2 rounded-full px-4 py-2.5 text-center text-xs font-semibold sm:text-sm {{ $allMet ? 'animate-pulse bg-gradient-to-r from-emerald-500 to-emerald-400 text-white shadow-md shadow-emerald-500/30' : 'bg-slate-100 text-slate-500 ring-1 ring-slate-200' }}">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0">
                                    @if($allMet)
                                        <polyline points="20 6 9 17 4 12"/>
                                    @else
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    @endif
                                </svg>
                                {{ $allMet ? 'Semua syarat terpenuhi — mahasiswa dapat download kartu bebas pustaka' : 'Belum semua syarat terpenuhi' }}
                            </div>

                            {{-- FINAL APPROVAL: Admin explicitly grants download permission --}}
                            @php
                                $prereqsMet = ! $document->has_active_loans
                                    && $document->dosen_approved_at
                                    && $document->pdf_kelengkapan_deklarasi
                                    && $document->pdf_kelengkapan_confirmed
                                    && $document->hard_copy_submitted;
                            @endphp
                            <div class="mt-3">
                                @if ($document->bebas_pustaka_diizinkan)
                                    <div class="flex flex-wrap items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 text-emerald-600"><polyline points="20 6 9 17 4 12"/></svg>
                                        <div class="min-w-[10rem] flex-1 text-sm text-emerald-800">
                                            <strong>Download diizinkan</strong>
                                            <span class="text-emerald-600"> {{ $document->bebas_pustaka_diizinkan_at ? '— '.$document->bebas_pustaka_diizinkan_at->format('d/m/Y H:i') : '' }}</span>
                                        </div>
                                        <form method="POST" action="{{ route('admin.documents.bebas-pustaka.revoke', $document) }}">
                                            @csrf
                                            <button type="submit" class="rounded-full border border-red-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-red-600 transition-all duration-200 hover:scale-105 hover:bg-red-50" onclick="return confirm('Cabut izin download kartu bebas pustaka?')">
                                                Cabut Izin
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.documents.bebas-pustaka.approve', $document) }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-center text-sm font-bold transition-all duration-200 {{ $prereqsMet ? 'bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow-md shadow-blue-600/30 hover:scale-[1.01] hover:shadow-lg' : 'cursor-not-allowed bg-slate-100 text-slate-400' }}"
                                            {{ $prereqsMet ? '' : 'disabled' }}
                                            title="{{ $prereqsMet ? 'Klik untuk mengizinkan download kartu bebas pustaka' : 'Selesaikan semua syarat terlebih dahulu sebelum memberikan izin' }}">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                                            Izinkan Download Kartu Bebas Pustaka
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3 bg-white px-4 py-4 sm:flex-row sm:flex-wrap sm:items-center sm:gap-2.5 sm:px-6 sm:py-5">
                        <div class="flex flex-wrap gap-2.5">
                            @if ($document->file_dokumen)
                                <a href="{{ route('admin.documents.download', $document) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition-all duration-200 hover:scale-105 hover:bg-blue-50">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat PDF
                                </a>
                            @endif
                            @if ($document->file_project)
                                <a href="{{ route('repository.project.download', $document) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition-all duration-200 hover:scale-105 hover:bg-blue-50">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download Project
                                </a>
                            @endif
                        </div>

                        <div class="flex gap-2.5 sm:ml-auto">
                            <form method="POST" action="{{ route('admin.documents.status', $document) }}" class="flex-1 sm:flex-none">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="terverifikasi">
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-blue-600/30 transition-all duration-200 hover:scale-105 hover:shadow-md sm:w-auto">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                                    Terverifikasi
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.documents.status', $document) }}" class="flex-1 sm:flex-none">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition-all duration-200 hover:scale-105 hover:bg-red-50 hover:text-red-600 hover:border-red-200 sm:w-auto">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Tolak
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" class="flex-1 sm:flex-none" onsubmit="return confirm(@js('Hapus data dokumen '.$document->judul.' secara permanen? File PDF dan project terkait juga akan dihapus.'))">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition-all duration-200 hover:scale-105 hover:bg-red-50 sm:w-auto">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" class="shrink-0"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-blue-200 bg-blue-50/40 px-6 py-16 text-center">
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 text-blue-300"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <p class="text-sm font-medium text-slate-500">
                        @if ($status === 'pending')
                            Tidak ada upload dokumen yang menunggu verifikasi.
                        @elseif ($status === 'terverifikasi')
                            Belum ada dokumen yang terverifikasi.
                        @elseif ($status === 'ditolak')
                            Belum ada dokumen yang ditolak.
                        @else
                            Belum ada dokumen repository.
                        @endif
                    </p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $documents->links() }}
            </div>
        </section>
    </div>
</div>
@endsection
