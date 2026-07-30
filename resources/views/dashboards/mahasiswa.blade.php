@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/60 via-white to-white dark:from-slate-950 dark:via-slate-950 dark:to-black">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Hero Section --}}
        <section class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 via-blue-700 to-blue-900 dark:from-slate-900 dark:via-slate-950 dark:to-black px-6 py-10 sm:px-10 sm:py-14 shadow-2xl shadow-blue-900/20">
            {{-- dekorasi animasi --}}
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-white/10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute -bottom-24 -left-16 w-80 h-80 bg-blue-300/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.08)_1px,transparent_0)] bg-[length:24px_24px]"></div>
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-blue-950/30 to-transparent"></div>

            <div class="relative flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-blue-100 bg-white/10 px-3 py-1.5 rounded-full mb-4 backdrop-blur-sm border border-white/20 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-200"></span>
                        </span>
                        Dashboard Mahasiswa
                    </p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-snug max-w-2xl drop-shadow-sm">
                        Upload PDF dan file project <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">ZIP/RAR</span> untuk ACC dosen.
                    </h1>
                    <p class="mt-3 text-blue-100/90 text-sm sm:text-base max-w-xl">
                        Kelola dokumen skripsi dan laporan magangmu, lalu pantau status persetujuannya di satu tempat.
                    </p>
                </div>

                <div class="flex gap-3 shrink-0">
                    <div class="bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[84px] border border-white/10 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <p class="text-xl font-bold text-white">{{ $totalDokumen ?? 0 }}</p>
                        <p class="text-[11px] text-blue-100 mt-0.5">Dokumen</p>
                    </div>
                    <div class="bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[84px] border border-white/10 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <p class="text-xl font-bold text-white">{{ $menungguAcc ?? 0 }}</p>
                        <p class="text-[11px] text-blue-100 mt-0.5">Menunggu</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Cari Repository --}}
        <section class="rounded-2xl border border-blue-100 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 backdrop-blur-sm">
            <form action="{{ route('repository.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="relative flex-1 w-full">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 pointer-events-none">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>
                    </svg>
                    <input type="search" name="search" placeholder="Cari skripsi, magang, PKM, penelitian, nama dosen/mahasiswa..."
                           class="w-full rounded-xl border border-blue-100 bg-blue-50/50 py-3 pl-12 pr-4 text-sm text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500">
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:scale-[1.02] hover:shadow-lg transition-all active:scale-[0.98]">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    Cari Repository
                </button>
            </form>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-semibold text-slate-600 dark:text-slate-300">Kategori Populer:</span>
                <a href="{{ route('repository.index', 'skripsi') }}" class="rounded-full bg-blue-50 px-2.5 py-1 text-blue-700 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition-colors">Skripsi / TA</a>
                <a href="{{ route('repository.index', 'magang') }}" class="rounded-full bg-indigo-50 px-2.5 py-1 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:text-indigo-300 transition-colors">Laporan Magang</a>
                <a href="{{ route('repository.index', 'pkm') }}" class="rounded-full bg-emerald-50 px-2.5 py-1 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition-colors">PKM</a>
                <a href="{{ route('repository.index', 'penelitian') }}" class="rounded-full bg-purple-50 px-2.5 py-1 text-purple-700 hover:bg-purple-100 dark:bg-purple-950/60 dark:text-purple-300 transition-colors">Penelitian Dosen</a>
            </div>
        </section>

        {{-- Role Workspace --}}
        <section class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/30 transition-transform duration-300 hover:scale-105 hover:rotate-3">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Area Kerja</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pilih kategori dokumen yang ingin diunggah</p>
                </div>
            </div>
            @include('dashboards.partials.role-workspace', [
                'categories' => ['skripsi' => 'Skripsi/TA', 'magang' => 'Laporan Magang'],
            ])
        </section>

        {{-- Cards --}}
        <section>
            <div class="flex items-center gap-3 mb-4 px-1">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/30 transition-transform duration-300 hover:scale-105 hover:rotate-3">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Ringkasan</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Status per kategori</p>
                </div>
            </div>
            @include('dashboards.partials.cards', [
                'cardKeys' => ['skripsi' => 'Skripsi/TA', 'magang' => 'Magang'],
            ])
        </section>

        {{-- Documents --}}
        <section class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/30 transition-transform duration-300 hover:scale-105 hover:rotate-3">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Dokumen Saya</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Riwayat file yang sudah diunggah</p>
                    </div>
                </div>
            </div>
            @include('dashboards.partials.documents')
        </section>

    </div>
</div>
@endsection