@extends('layouts.app')

@section('title', 'Pencarian Repository Dokumen')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/60 via-white to-white dark:from-slate-950 dark:via-slate-950 dark:to-black">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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
                <form class="mt-6 space-y-3" method="GET" action="{{ route('repository.index', $kategori) }}" id="repo-filter-form">
                    {{-- Baris 1: Search input + tombol cari --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                            <input type="search" name="search" id="repo-search" value="{{ $search }}"
                                   placeholder="Cari judul, nama, NIM, NIDN, tempat magang, abstrak..."
                                   class="w-full rounded-2xl border-0 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-800 shadow-lg placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:scale-[1.02] hover:shadow-xl transition-all active:scale-[0.98]">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                            Cari
                        </button>
                    </div>

                    {{-- Baris 2: Filter Tahun + Sort --}}
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Filter Tahun --}}
                        <div class="relative">
                            <select name="tahun" id="repo-tahun"
                                    class="appearance-none rounded-xl bg-white/15 backdrop-blur-sm border border-white/25 text-white text-xs font-semibold pl-3 pr-8 py-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-white/40 transition-all hover:bg-white/22"
                                    onchange="this.form.submit()">
                                <option value="" class="text-slate-800 bg-white" {{ $tahun === '' ? 'selected' : '' }}>Semua Tahun</option>
                                @foreach($availableTahun as $t)
                                    <option value="{{ $t }}" class="text-slate-800 bg-white" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-white pointer-events-none"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>

                        {{-- Sort --}}
                        <div class="relative">
                            <select name="sort" id="repo-sort"
                                    class="appearance-none rounded-xl bg-white/15 backdrop-blur-sm border border-white/25 text-white text-xs font-semibold pl-3 pr-8 py-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-white/40 transition-all hover:bg-white/22"
                                    onchange="this.form.submit()">
                                <option value="terbaru" class="text-slate-800 bg-white" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" class="text-slate-800 bg-white" {{ $sort === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="az" class="text-slate-800 bg-white" {{ $sort === 'az' ? 'selected' : '' }}>A → Z</option>
                                <option value="za" class="text-slate-800 bg-white" {{ $sort === 'za' ? 'selected' : '' }}>Z → A</option>
                            </select>
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-white pointer-events-none"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>

                        {{-- Reset Filter --}}
                        @if($search !== '' || $tahun !== '' || $sort !== 'terbaru')
                        <a href="{{ route('repository.index', $kategori) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold px-3 py-2 hover:bg-white/20 transition-all">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                {{-- Quick Kategori Filter Badges --}}
                <div class="mt-5 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-blue-200 font-medium">Kategori:</span>
                    <a href="{{ route('repository.index') }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ !$kategori ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Semua
                    </a>
                    @foreach(['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian Dosen'] as $k => $l)
                    <a href="{{ route('repository.index', $k) }}"
                       class="rounded-full px-3 py-1 font-semibold transition-all {{ $kategori === $k ? 'bg-white text-blue-700 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $l }}
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Info Bar: Hasil Pencarian / Total Dokumen --}}
        <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-blue-100 bg-blue-50/70 px-4 py-3 text-sm text-blue-800 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
            <span>
                @if($search !== '' || $tahun !== '' || $sort !== 'terbaru')
                    Menampilkan <strong>{{ $documents->total() }}</strong> dokumen
                    @if($search !== '') untuk "<strong>{{ $search }}</strong>"@endif
                    @if($tahun !== '') tahun <strong>{{ $tahun }}</strong>@endif
                @else
                    Total <strong>{{ $documents->total() }}</strong> dokumen
                    {{ $kategori ? 'kategori '.strtoupper($kategori) : 'di seluruh repository' }}
                @endif
            </span>
            @if($search !== '' || $tahun !== '')
                <a href="{{ route('repository.index', $kategori) }}" class="text-xs font-bold text-blue-600 hover:underline dark:text-blue-400">Reset Pencarian</a>
            @endif
        </div>

        {{-- List Dokumen --}}
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

                    // Citation APA style
                    $citation = trim(($document->nama ?: 'Anonim'))
                        . ($document->tahun ? ' ('.$document->tahun.').' : '.')
                        . ' ' . $document->judul . '.'
                        . ($document->programStudi ? ' ' . $document->programStudi->nama . ',' : '')
                        . ' Universitas Metamedia.';
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

                            {{-- Citation row --}}
                            <div class="flex items-center gap-2 pt-0.5">
                                <span class="text-xs text-slate-400 font-medium italic truncate max-w-sm">{{ $citation }}</span>
                                <button type="button"
                                        class="copy-citation-btn flex-shrink-0 inline-flex items-center gap-1 rounded-lg bg-slate-100 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 px-2 py-1 text-xs font-semibold text-slate-600 hover:text-blue-600 transition-all"
                                        data-citation="{{ $citation }}"
                                        title="Salin sitasi APA">
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    <span class="btn-cite-label">Sitasi</span>
                                </button>
                            </div>
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
                    <p class="text-xs text-slate-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian, pilih tahun lain, atau pilih kategori dokumen lain.</p>
                    <a href="{{ route('repository.index', $kategori) }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold px-4 py-2.5 hover:bg-blue-700 transition-all">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $documents->links() }}
            </div>
        </section>
    </div>
</div>

{{-- Toast copy citation --}}
<div id="copy-toast"
     style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);z-index:9999;
            display:flex;align-items:center;gap:.5rem;padding:.65rem 1.2rem;
            background:rgba(15,23,42,.9);color:#fff;border-radius:10px;font-size:.8rem;font-weight:700;
            backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.1);
            opacity:0;transition:opacity .3s ease, transform .3s ease;pointer-events:none;">
    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    Sitasi berhasil disalin!
</div>

<script>
(function () {
    /* ── Copy Citation ───────────────────────────────────────── */
    const toast = document.getElementById('copy-toast');
    let toastTimer;

    document.querySelectorAll('.copy-citation-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const text = btn.dataset.citation;
            if (!text) return;

            navigator.clipboard.writeText(text).then(function () {
                // visual feedback on button
                const label = btn.querySelector('.btn-cite-label');
                if (label) { label.textContent = 'Tersalin!'; }
                btn.style.background = '#eff6ff';
                btn.style.borderColor = '#bfdbfe';
                btn.style.color = '#1d4ed8';
                setTimeout(function () {
                    if (label) label.textContent = 'Sitasi';
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.style.color = '';
                }, 2000);

                // show toast
                if (toast) {
                    clearTimeout(toastTimer);
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateX(-50%) translateY(0)';
                    toastTimer = setTimeout(function () {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(-50%) translateY(20px)';
                    }, 2500);
                }
            }).catch(function () {
                // Fallback for browsers without clipboard API
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            });
        });
    });
})();
</script>
@endsection
