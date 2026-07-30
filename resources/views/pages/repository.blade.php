@extends('layouts.app')

@section('title', 'Pencarian Repository Dokumen')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/60 via-white to-white dark:from-slate-950 dark:via-slate-950 dark:to-black">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Hero & Search Header --}}
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-900 px-6 py-10 sm:px-10 sm:py-12 shadow-xl shadow-blue-900/20">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-10 h-64 w-64 rounded-full bg-indigo-300/20 blur-3xl"></div>

            <div class="relative max-w-3xl">
                <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-blue-100 backdrop-blur-sm border border-white/20">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    Pencarian Repository Akademik
                </p>
                <h1 class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-snug">
                    {{ $kategori ? 'Repository '.strtoupper($kategori) : 'Koleksi Repository Kampus' }}
                </h1>
                <p class="mt-2 text-sm sm:text-base text-blue-100/90 leading-relaxed">
                    Cari dan baca laporan magang, skripsi, PKM, serta karya penelitian dosen yang telah terverifikasi.
                </p>

                {{-- Form Pencarian --}}
                <form class="mt-6 flex flex-col sm:flex-row gap-3" method="GET" action="{{ route('repository.index', $kategori) }}">
                    <div class="relative flex-1">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>
                        </svg>
                        <input type="search" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul, nama penulis/dosen, NIM, NIDN, tempat magang, abstrak..."
                               class="w-full rounded-2xl border-0 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-800 shadow-lg placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:scale-[1.02] hover:shadow-xl transition-all active:scale-[0.98]">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                        Cari Dokumen
                    </button>
                </form>

                {{-- Quick Kategori Filter Badges --}}
                <div class="mt-5 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-blue-200 font-medium">Filter Kategori:</span>
                    <a href="{{ route('repository.index') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ !$kategori ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Semua Dokumen
                    </a>
                    <a href="{{ route('repository.index', 'skripsi') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ $kategori === 'skripsi' ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Skripsi / TA
                    </a>
                    <a href="{{ route('repository.index', 'magang') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ $kategori === 'magang' ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Magang
                    </a>
                    <a href="{{ route('repository.index', 'pkm') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ $kategori === 'pkm' ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        PKM
                    </a>
                    <a href="{{ route('repository.index', 'penelitian') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ $kategori === 'penelitian' ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Penelitian Dosen
                    </a>
                </div>
            </div>
        </section>

        {{-- Info Pencarian --}}
        @if(request('search'))
            <div class="flex items-center justify-between rounded-xl border border-blue-100 bg-blue-50/70 px-4 py-3 text-sm text-blue-800">
                <span>Hasil pencarian untuk kata kunci: <strong>"{{ request('search') }}"</strong> ({{ $documents->total() }} dokumen ditemukan)</span>
                <a href="{{ route('repository.index', $kategori) }}" class="text-xs font-bold text-blue-600 hover:underline">Reset Pencarian</a>
            </div>
        @endif

        {{-- List Dokumen Hasil Pencarian --}}
        <section class="space-y-4">
            @forelse ($documents as $document)
                @php
                    $katBadges = [
                        'skripsi'    => 'bg-blue-100 text-blue-700 border-blue-200',
                        'magang'     => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                        'pkm'        => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'penelitian' => 'bg-purple-100 text-purple-700 border-purple-200',
                    ];
                    $badgeStyle = $katBadges[$document->kategori] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                @endphp
                <article class="group rounded-2xl border border-blue-100/80 bg-white p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-300 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="space-y-2.5 flex-1">
                            {{-- Badges & Status --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="rounded-full border px-2.5 py-0.5 font-bold uppercase tracking-wider {{ $badgeStyle }}">
                                    {{ strtoupper($document->kategori) }}
                                </span>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 font-semibold text-emerald-700 border border-emerald-100">
                                    Terverifikasi
                                </span>
                                @if($document->jenis_input === 'admin_import')
                                    <span class="rounded-full bg-amber-50 px-2.5 py-0.5 font-medium text-amber-700 border border-amber-100">
                                        Data Import Admin
                                    </span>
                                @endif
                                <span class="text-slate-400 ml-auto sm:ml-0 font-medium">Tahun {{ $document->tahun }}</span>
                            </div>

                            {{-- Judul --}}
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 group-hover:text-blue-600 transition-colors leading-snug">
                                {{ $document->judul }}
                            </h2>

                            {{-- Metadata Penulis / Dosen / Prodi --}}
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                                <span class="inline-flex items-center gap-1.5 font-medium text-slate-700 dark:text-slate-300">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ $document->nama }}
                                    @if($document->nim)<span class="text-slate-400">(NIM: {{ $document->nim }})</span>@endif
                                    @if($document->nidn)<span class="text-slate-400">(NIDN: {{ $document->nidn }})</span>@endif
                                </span>

                                @if($document->programStudi)
                                    <span class="inline-flex items-center gap-1">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500"><path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                        {{ $document->programStudi->nama }}
                                    </span>
                                @endif

                                @if($document->tempat_magang)
                                    <span class="inline-flex items-center gap-1 text-indigo-600 font-medium">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                                        {{ $document->tempat_magang }}
                                    </span>
                                @endif
                            </div>

                            {{-- Abstrak / Ringkasan --}}
                            @if($document->abstrak || $document->detail)
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/80">
                                    {{ $document->abstrak ?: $document->detail }}
                                </p>
                            @endif
                        </div>

                        {{-- Action Button --}}
                        <div class="sm:shrink-0 pt-2 sm:pt-0">
                            @if ($document->file_dokumen)
                                <a href="{{ route('repository.show', $document) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:shadow-md transition-all">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat Dokumen
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-400">
                                    Format Meta
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-blue-200 bg-blue-50/30 p-12 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-500 mb-3">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    </div>
                    <p class="text-base font-bold text-slate-700 dark:text-slate-300">Tidak ada dokumen ditemukan</p>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian atau pilih kategori dokumen lain.</p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $documents->links() }}
            </div>
        </section>
    </div>
</div>
@endsection
