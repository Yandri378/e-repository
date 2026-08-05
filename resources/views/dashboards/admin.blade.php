@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 px-4 py-6 sm:px-6 lg:px-8">

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-slate-900 p-8 shadow-xl shadow-blue-900/20 sm:p-10">
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-blue-400/20 blur-3xl"></div>

        <div class="relative">
            <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-100 ring-1 ring-inset ring-white/20">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-300 to-blue-500 text-white shadow-inner">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                </span>
                Dashboard Admin
            </p>
            <h1 class="mt-4 max-w-2xl text-2xl font-bold leading-snug text-white sm:text-3xl">
                Pusat kontrol repository, verifikasi, akun, dan laporan kampus.
            </h1>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-900/30 transition hover:from-blue-600 hover:to-indigo-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Manual
                </a>
                <a href="{{ route('admin.documents.import') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/30 transition hover:from-emerald-600 hover:to-teal-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><path d="M9 13l2 2 4-4"/></svg>
                    Import Excel
                </a>
                <a href="#setting-upload" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-lg shadow-black/10 transition hover:bg-blue-50">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                    Atur Sesi Upload
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-inset ring-white/25 transition hover:bg-white/20">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 13h6M9 17h6M9 9h1"/></svg>
                    Buka Laporan
                </a>
            </div>
        </div>
    </section>

    {{-- ============ OVERVIEW ============ --}}
    <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
            <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-lg shadow-orange-500/30">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                </span>
                Perlu Ditindak
            </p>
            <h2 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">
                {{ $pendingUsers + $pendingDocuments }} antrean menunggu keputusan
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ $pendingUsers }} akun baru dan {{ $pendingDocuments }} dokumen upload perlu diverifikasi admin.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('admin.documents.pending') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="m9 15 2 2 4-4"/></svg>
                    Cek Upload
                </a>
                <a href="{{ route('admin.users.pending') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-blue-400 dark:hover:bg-slate-700 dark:hover:text-blue-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Cek Akun
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-1">
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg shadow-blue-500/30 ring-1 ring-white/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                </span>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Dokumen</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalDocuments }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-700 text-white shadow-lg shadow-indigo-500/30 ring-1 ring-white/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Upload Bulan Ini</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $documentsThisMonth }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-500/30 ring-1 ring-white/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg>
                </span>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Terverifikasi Bulan Ini</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $verifiedThisMonth }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CARD STRIP PARTIAL ============ --}}
    <div>
        @include('dashboards.partials.cards')
    </div>

    {{-- ============ BAR CHART ============ --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start justify-between">
            <div>
                <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/30">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg>
                    </span>
                    Diagram Batang
                </p>
                <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Ringkasan data utama</h2>
            </div>
        </div>

        @php
            $chartRows = [
                'Mahasiswa' => $totalMahasiswa,
                'Dosen' => $totalDosen,
                'Dokumen TA' => $totalTa,
                'Magang' => $totalMagang,
            ];
            $chartMax = max(1, ...array_values($chartRows));
        @endphp
        <div class="mt-6 space-y-4">
            @foreach ($chartRows as $label => $value)
                <div class="flex items-center gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-600 dark:text-slate-300">{{ $label }}</span>
                    <div class="h-3 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="flex h-full items-center justify-end rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-2 text-[11px] font-semibold text-white" style="width: {{ max(6, round(($value / $chartMax) * 100)) }}%">
                            {{ $value }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ MODULE GRID ============ --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- Status Dokumen --}}
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/30">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                </span>
                Status Dokumen
            </h3>
            <div class="mt-4 space-y-1">
                @php
                    $statusIcons = [
                        'pending' => ['from-amber-400 to-orange-500', '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>'],
                        'terverifikasi' => ['from-emerald-400 to-emerald-600', '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg>'],
                        'arsip' => ['from-slate-400 to-slate-600', '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="5" rx="1"/><path d="M5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9M10 13h4"/></svg>'],
                        'ditolak' => ['from-rose-400 to-rose-600', '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>'],
                    ];
                @endphp
                @foreach (['pending' => 'Pending', 'terverifikasi' => 'Terverifikasi', 'arsip' => 'Arsip', 'ditolak' => 'Ditolak'] as $status => $label)
                    <div class="flex items-center justify-between rounded-xl px-2 py-2.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">
                        <span class="flex items-center gap-2.5 text-sm font-medium text-slate-600 dark:text-slate-300">
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-gradient-to-br {{ $statusIcons[$status][0] }} text-white shadow-sm">
                                {!! $statusIcons[$status][1] !!}
                            </span>
                            {{ $label }}
                        </span>
                        <strong class="text-sm font-bold text-slate-900 dark:text-white">{{ $statusCounts[$status] ?? 0 }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- Komposisi Akun --}}
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-blue-700 text-white shadow-md shadow-indigo-500/30">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                Komposisi Akun
            </h3>
            <div class="mt-4 space-y-1">
                <div class="flex items-center justify-between rounded-xl px-2 py-2.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    <span class="flex items-center gap-2.5 text-sm font-medium text-slate-600 dark:text-slate-300">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-sm">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.66 2.69 3 6 3s6-1.34 6-3v-5"/></svg>
                        </span>
                        Mahasiswa
                    </span>
                    <strong class="text-sm font-bold text-slate-900 dark:text-white">{{ $roleCounts['mahasiswa'] ?? 0 }}</strong>
                </div>
                <div class="flex items-center justify-between rounded-xl px-2 py-2.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    <span class="flex items-center gap-2.5 text-sm font-medium text-slate-600 dark:text-slate-300">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-sm">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        </span>
                        Dosen
                    </span>
                    <strong class="text-sm font-bold text-slate-900 dark:text-white">{{ $roleCounts['dosen'] ?? 0 }}</strong>
                </div>
            </div>
            <p class="mt-4 flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>
                Data ini hanya tampil di panel admin.
            </p>
        </article>

        {{-- Aksi Cepat --}}
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-md shadow-orange-500/30">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                </span>
                Aksi Cepat
            </h3>
            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                <a href="{{ route('repository.create', 'skripsi') }}" class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-blue-400 dark:hover:bg-slate-700 dark:hover:text-blue-200">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M12 11v6M9 14h6"/></svg>
                    Arsip Skripsi
                </a>
                <a href="{{ route('repository.create', 'magang') }}" class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-blue-400 dark:hover:bg-slate-700 dark:hover:text-blue-200">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    Arsip Magang
                </a>
                <a href="{{ route('repository.create', 'penelitian') }}" class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-blue-400 dark:hover:bg-slate-700 dark:hover:text-blue-200">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                    Penelitian
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-600/30 transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    Buka Laporan
                </a>
            </div>
        </article>

        {{-- Sesi Upload --}}
        <article id="setting-upload" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                        </span>
                        Kontrol Admin
                    </p>
                    <h3 class="mt-2 text-sm font-bold text-slate-900 dark:text-white">Sesi Upload</h3>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-300 hover:text-blue-700 dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:text-blue-300">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                    Beranda
                </a>
            </div>
            <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                Aktifkan kategori yang boleh diunggah mahasiswa atau dosen. Perubahan tombol langsung memengaruhi running text dan form publik.
            </p>

            <div class="mt-4 flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.settings.upload-session') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="kategori" value="all">
                    <input type="hidden" name="status" value="open">
                    <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-md shadow-blue-600/30 transition hover:bg-blue-700 dark:bg-blue-500" type="submit">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                        Buka Semua Sesi
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.settings.upload-session') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="kategori" value="all">
                    <input type="hidden" name="status" value="closed">
                    <button class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-300 hover:text-rose-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-rose-500 dark:hover:text-rose-300" type="submit">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Tutup Semua Sesi
                    </button>
                </form>
            </div>

            <div class="mt-4 space-y-2">
                @foreach ($uploadStatuses as $kategori => $isOpen)
                    <form method="POST" action="{{ route('admin.settings.upload-session') }}" class="flex items-center justify-between rounded-xl border {{ $isOpen ? 'border-emerald-200 bg-emerald-50/60 dark:border-emerald-800 dark:bg-emerald-500/10' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/60' }} px-3 py-2.5" data-kategori="{{ $kategori }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                        <input type="hidden" name="status" value="{{ $isOpen ? 'closed' : 'open' }}">
                        <span>
                            <span class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200">
                                <span class="h-2 w-2 rounded-full {{ $isOpen ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ strtoupper($kategori) }}
                            </span>
                            <small class="mt-0.5 block text-[11px] text-slate-500 dark:text-slate-400">{{ $isOpen ? 'Dibuka untuk upload' : 'Ditutup untuk upload' }}</small>
                        </span>
                        <button class="rounded-lg {{ $isOpen ? 'border border-slate-200 text-slate-600 hover:border-rose-300 hover:text-rose-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-rose-500 dark:hover:text-rose-300' : 'bg-blue-600 text-white shadow-md shadow-blue-600/30 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400' }} px-3 py-1.5 text-xs font-semibold transition" type="submit">{{ $isOpen ? 'Tutup' : 'Buka' }}</button>
                    </form>
                @endforeach
            </div>
        </article>
    </section>

    {{-- ============ VERIFIKASI UPLOAD / AKUN ============ --}}
    <section class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        </span>
                        Verifikasi Upload
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Dokumen terbaru</h2>
                </div>
                <a class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400" href="{{ route('admin.documents.pending') }}">Lihat semua &rarr;</a>
            </div>

            @forelse ($latestPendingDocuments as $document)
                <article class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">{{ strtoupper($document->kategori) }}</span>
                        <h2 class="mt-2 text-sm font-bold text-slate-900 dark:text-white">{{ $document->judul }}</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $document->nama }} &middot; {{ $document->nim ?: $document->nidn }} &middot; {{ $document->programStudi?->nama ?: 'Prodi belum dipilih' }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($document->file_dokumen)
                            <a class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-300 hover:text-blue-700 dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:text-blue-300" href="{{ route('admin.documents.download', $document) }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                Lihat PDF
                            </a>
                        @endif
                        @if ($document->file_project)
                            <a class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-300 hover:text-blue-700 dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:text-blue-300" href="{{ route('repository.project.download', $document) }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
                                Download Project
                            </a>
                        @endif
                        <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="terverifikasi">
                            <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm shadow-emerald-600/30 transition hover:bg-emerald-700" type="submit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Konfirmasi
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <input type="hidden" name="catatan_verifikasi" value="Dokumen belum sesuai.">
                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-500/10" type="submit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-500">
                    <svg class="mx-auto mb-3 h-9 w-9 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                    Belum ada dokumen yang menunggu verifikasi.
                </div>
            @endforelse
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-blue-700 text-white shadow-md shadow-indigo-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 8v6M19 11h6"/></svg>
                        </span>
                        Verifikasi Akun Baru
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Pendaftar terbaru</h2>
                </div>
                <a class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400" href="{{ route('admin.users.pending') }}">Lihat semua &rarr;</a>
            </div>

            @forelse ($latestPendingUsers as $user)
                <article class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">{{ strtoupper($user->role) }}</span>
                        <h2 class="mt-2 text-sm font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $user->email }} &middot; {{ $user->nim ?: $user->nidn }} &middot; {{ $user->programStudi?->nama ?: 'Prodi belum dipilih' }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form method="POST" action="{{ route('admin.users.status', $user) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_akun" value="aktif">
                            <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm shadow-emerald-600/30 transition hover:bg-emerald-700" type="submit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Terima
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_akun" value="ditolak">
                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-500/10" type="submit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-500">
                    <svg class="mx-auto mb-3 h-9 w-9 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    Belum ada akun mahasiswa/dosen yang menunggu verifikasi.
                </div>
            @endforelse
        </div>
    </section>

    {{-- ============ AKTIVITAS / SEBARAN PRODI ============ --}}
    <section class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-md shadow-orange-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                        </span>
                        Aktivitas Repository
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Update terakhir</h2>
                </div>
                <a class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400" href="{{ route('admin.documents.index') }}">Kelola dokumen &rarr;</a>
            </div>

            @forelse ($recentDocuments as $document)
                <article class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ strtoupper($document->status) }}</span>
                        <h2 class="mt-1.5 text-sm font-semibold text-slate-900 dark:text-white">{{ $document->judul }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $document->nama }} &middot; {{ $document->kategori }} &middot; {{ $document->updated_at->diffForHumans() }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-500">
                    <svg class="mx-auto mb-3 h-9 w-9 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                    Belum ada aktivitas repository.
                </div>
            @endforelse
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/30">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg>
                        </span>
                        Sebaran Prodi
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Akun per program studi</h2>
                </div>
                <a class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400" href="{{ route('admin.users.index') }}">Kelola akun &rarr;</a>
            </div>

            @forelse ($programDistribution as $program)
                @php
                    $percent = $totalDocuments > 0 ? min(100, round(($program->documents_count / $totalDocuments) * 100)) : 0;
                @endphp
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between text-sm">
                        <strong class="font-semibold text-slate-900 dark:text-white">{{ $program->nama }}</strong>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $program->documents_count }} dokumen</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <span class="block h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $percent }}%"></span>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-500">
                    <svg class="mx-auto mb-3 h-9 w-9 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg>
                    Belum ada data program studi.
                </div>
            @endforelse
        </div>
    </section>

    {{-- ============ ADMIN ONLY NOTE ============ --}}
    <section class="rounded-3xl border border-blue-100 bg-blue-50/60 p-6 dark:border-blue-900/40 dark:bg-blue-500/5">
        <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/30">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
            </span>
            Area Admin
        </p>
        <h2 class="mt-3 text-sm font-semibold text-slate-800 dark:text-slate-100">
            Informasi antrean, kontak pendaftar, dokumen pending, dokumen ditolak, dan laporan internal hanya tersedia untuk admin.
        </h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Mahasiswa dan dosen tetap hanya melihat dashboard, upload, dan riwayat dokumen milik akun mereka sendiri.
        </p>
    </section>

</div>
@endsection
