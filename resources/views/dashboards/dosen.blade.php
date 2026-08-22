@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/60 via-white to-white dark:from-slate-950 dark:via-slate-950 dark:to-black">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Hero Section --}}
        <section class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-slate-900 px-6 py-10 sm:px-10 sm:py-12 shadow-2xl shadow-blue-900/20">
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-white/10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute -bottom-24 -left-16 w-80 h-80 bg-indigo-300/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.08)_1px,transparent_0)] bg-[length:24px_24px]"></div>

            <div class="relative flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-blue-100 bg-white/10 px-3 py-1.5 rounded-full mb-4 backdrop-blur-sm border border-white/20 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-200"></span>
                        </span>
                        Dashboard Dosen
                    </p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-snug max-w-2xl drop-shadow-sm">
                        Kelola PKM, Penelitian, dan Bimbingan Skripsi/Magang.
                    </h1>
                    <p class="mt-3 text-blue-100/90 text-sm sm:text-base max-w-xl">
                        Unggah karya ilmiah & penelitian Anda, serta berikan persetujuan (ACC) dokumen bimbingan mahasiswa.
                    </p>
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
                    <input type="search" name="search" placeholder="Cari karya penelitian, PKM, skripsi, magang, NIDN, NIM..."
                           class="w-full rounded-xl border border-blue-100 bg-blue-50/50 py-3 pl-12 pr-4 text-sm text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500">
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:scale-[1.02] hover:shadow-lg transition-all active:scale-[0.98]">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    Cari Repository
                </button>
            </form>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-semibold text-slate-600 dark:text-slate-300">Pencarian Cepat:</span>
                <a href="{{ route('repository.index', 'penelitian') }}" class="rounded-full bg-blue-50 px-2.5 py-1 text-blue-700 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition-colors">Penelitian Dosen</a>
                <a href="{{ route('repository.index', 'pkm') }}" class="rounded-full bg-sky-50 px-2.5 py-1 text-sky-700 hover:bg-sky-100 dark:bg-sky-950/60 dark:text-sky-300 transition-colors">PKM</a>
                <a href="{{ route('repository.index', 'skripsi') }}" class="rounded-full bg-blue-50 px-2.5 py-1 text-blue-700 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition-colors">Skripsi / TA</a>
                <a href="{{ route('repository.index', 'magang') }}" class="rounded-full bg-sky-50 px-2.5 py-1 text-sky-700 hover:bg-sky-100 dark:bg-sky-950/60 dark:text-sky-300 transition-colors">Laporan Magang</a>
            </div>
        </section>

        {{-- Role Workspace --}}
        <section class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-700 flex items-center justify-center shadow-md shadow-indigo-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Area Kerja Dosen</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pilih kategori karya ilmiah yang ingin diunggah</p>
                </div>
            </div>
            @include('dashboards.partials.role-workspace', [
                'categories' => ['pkm' => 'PKM', 'penelitian' => 'Penelitian'],
            ])
        </section>

        {{-- Cards --}}
        <section>
            @include('dashboards.partials.cards', [
                'cardKeys' => ['pkm' => 'PKM', 'penelitian' => 'Penelitian'],
            ])
        </section>

        {{-- Approval Documents --}}
        @include('dashboards.partials.approval-documents')

        {{-- My Documents --}}
        <section class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
            @include('dashboards.partials.documents')
        </section>

    </div>
</div>
@endsection
