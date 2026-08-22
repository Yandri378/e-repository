@extends('layouts.app')

@section('title', $document->judul)

@section('content')

<style>
    /* ── Breadcrumb ─────────────────────────────────────────── */
    .dv-breadcrumb {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: 1.2rem 0 0;
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--ink-soft);
        flex-wrap: wrap;
    }
    .dv-breadcrumb a {
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        transition: opacity .2s;
    }
    .dv-breadcrumb a:hover { opacity: .75; }
    .dv-breadcrumb-sep { color: var(--ink-soft); opacity: .45; }
    .dv-breadcrumb-current {
        color: var(--ink);
        font-weight: 700;
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Hero compact ──────────────────────────────────────── */
    .dv-hero {
        width: min(1200px, calc(100% - 2rem));
        margin: 1rem auto 0;
        padding: 1.4rem 1.8rem;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(14,114,164,.55), rgba(5,37,62,.8));
        border: 1px solid rgba(114,217,255,.22);
        box-shadow: 0 16px 48px rgba(5,73,112,.25), inset 0 1px 0 rgba(255,255,255,.14);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.2rem;
        flex-wrap: wrap;
    }
    .dv-hero-info { flex: 1; min-width: 0; }
    .dv-hero-eyebrow {
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #72d9ff;
        margin: 0 0 .4rem;
    }
    .dv-hero-title {
        margin: 0;
        font-size: clamp(1rem, 2.5vw, 1.5rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dv-hero-meta {
        margin: .5rem 0 0;
        font-size: .8rem;
        color: rgba(255,255,255,.65);
        display: flex;
        flex-wrap: wrap;
        gap: .5rem 1rem;
        align-items: center;
    }
    .dv-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }
    .dv-hero-actions {
        display: flex;
        gap: .6rem;
        flex-shrink: 0;
        flex-wrap: wrap;
    }
    .dv-btn-back {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.2rem;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 700;
        color: #ffffff;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.22);
        cursor: pointer;
        text-decoration: none;
        transition: background .2s, transform .2s;
    }
    .dv-btn-back:hover { background: rgba(255,255,255,.22); transform: translateY(-2px); }
    .dv-btn-fullscreen {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.2rem;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 700;
        color: #00111d;
        background: linear-gradient(135deg, #dff8ff, #72d9ff 50%, #0b8fe8);
        border: 1px solid rgba(255,255,255,.35);
        cursor: pointer;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
    }
    .dv-btn-fullscreen:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(5,150,216,.4); }

    /* ── Layout Split ────────────────────────────────────────── */
    .dv-layout {
        width: min(1200px, calc(100% - 2rem));
        margin: 1.2rem auto clamp(3rem,6vw,5rem);
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.4rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .dv-layout { grid-template-columns: 1fr; }
        .dv-sidebar { order: 2; }
        .dv-viewer-col { order: 1; }
    }

    /* ── Viewer Column ───────────────────────────────────────── */
    .dv-viewer-col { position: relative; }
    .dv-iframe-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(37,99,235,.18);
        border: 1px solid rgba(114,217,255,.18);
    }
    .dv-iframe-wrap iframe {
        display: block;
        width: 100%;
        height: 82vh;
        min-height: 520px;
        border: 0;
        border-radius: 16px;
        background: #fff;
    }

    /* ── Skeleton Loading ────────────────────────────────────── */
    .dv-skeleton {
        position: absolute;
        inset: 0;
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(22,111,153,.55), rgba(12,58,90,.65));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        z-index: 10;
        transition: opacity .4s ease;
    }
    .dv-skeleton.hidden { opacity: 0; pointer-events: none; }
    .dv-skeleton-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: rgba(114,217,255,.15);
        border: 1px solid rgba(114,217,255,.3);
        display: flex; align-items: center; justify-content: center;
    }
    .dv-skeleton-icon svg { color: #72d9ff; }
    .dv-skeleton-text { text-align: center; }
    .dv-skeleton-text p {
        margin: 0;
        font-size: .85rem;
        color: rgba(255,255,255,.6);
        font-weight: 600;
    }
    .dv-skeleton-bar {
        width: 160px;
        height: 4px;
        border-radius: 4px;
        background: rgba(114,217,255,.15);
        overflow: hidden;
        margin: .6rem auto 0;
    }
    .dv-skeleton-bar::after {
        content: '';
        display: block;
        height: 100%;
        width: 40%;
        background: linear-gradient(90deg, transparent, #72d9ff, transparent);
        border-radius: 4px;
        animation: skeletonShimmer 1.4s ease-in-out infinite;
    }
    @keyframes skeletonShimmer {
        0% { transform: translateX(-200%); }
        100% { transform: translateX(400%); }
    }

    /* Overlay watermark */
    .dv-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        user-select: none;
        -webkit-user-select: none;
        z-index: 2;
    }

    /* Viewer note */
    .dv-note {
        text-align: center;
        margin-top: .8rem;
        color: var(--ink-soft);
        font-size: .78rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
    }

    /* ── Sidebar ─────────────────────────────────────────────── */
    .dv-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .dv-card {
        border-radius: 16px;
        border: 1px solid rgba(114,217,255,.18);
        background: linear-gradient(145deg, rgba(22,111,153,.6), rgba(12,58,90,.7));
        box-shadow: 0 16px 48px rgba(5,73,112,.2), inset 0 1px 0 rgba(255,255,255,.12);
        padding: 1.4rem;
    }
    .dv-card-title {
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #72d9ff;
        margin: 0 0 .9rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .dv-card-title svg { flex-shrink: 0; }

    /* Metadata rows */
    .dv-meta-row {
        display: flex;
        flex-direction: column;
        gap: .25rem;
        padding: .7rem 0;
        border-bottom: 1px solid rgba(114,217,255,.1);
    }
    .dv-meta-row:last-child { border-bottom: none; padding-bottom: 0; }
    .dv-meta-row:first-child { padding-top: 0; }
    .dv-meta-label {
        font-size: .7rem;
        font-weight: 700;
        color: #9dcfe8;
        text-transform: uppercase;
        letter-spacing: .07em;
    }
    .dv-meta-value {
        font-size: .88rem;
        font-weight: 600;
        color: #e8f6ff;
        line-height: 1.4;
    }

    /* Category badge */
    .dv-cat-badge {
        display: inline-flex;
        padding: .2rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .05em;
        background: rgba(114,217,255,.18);
        border: 1px solid rgba(114,217,255,.3);
        color: #72d9ff;
    }

    /* Abstrak */
    .dv-abstrak {
        font-size: .82rem;
        color: rgba(255,255,255,.65);
        line-height: 1.65;
        margin: 0;
    }
    .dv-abstrak-toggle {
        display: inline-flex;
        margin-top: .5rem;
        font-size: .75rem;
        font-weight: 700;
        color: #72d9ff;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        gap: .3rem;
        align-items: center;
    }
    .dv-abstrak-toggle:hover { opacity: .8; }

    /* Watermark badge */
    .dv-watermark-info {
        padding: .7rem .9rem;
        border-radius: 10px;
        background: rgba(114,217,255,.08);
        border: 1px solid rgba(114,217,255,.18);
        font-size: .74rem;
        color: rgba(255,255,255,.55);
        line-height: 1.5;
        display: flex;
        gap: .5rem;
    }
    .dv-watermark-info svg { flex-shrink: 0; margin-top: 1px; color: #72d9ff; }
</style>

{{-- ── Breadcrumb ──────────────────────────────────────────────── --}}
<nav class="dv-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">
        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Beranda
    </a>
    <span class="dv-breadcrumb-sep">›</span>
    <a href="{{ route('repository.index', $document->kategori) }}">Repository {{ strtoupper($document->kategori) }}</a>
    <span class="dv-breadcrumb-sep">›</span>
    <span class="dv-breadcrumb-current" title="{{ $document->judul }}">{{ $document->judul }}</span>
</nav>

{{-- ── Hero Bar ─────────────────────────────────────────────────── --}}
<div class="dv-hero reveal">
    <div class="dv-hero-info">
        <p class="dv-hero-eyebrow">
            <span class="dv-cat-badge">{{ strtoupper($document->kategori) }}</span>
        </p>
        <h1 class="dv-hero-title">{{ $document->judul }}</h1>
        <div class="dv-hero-meta">
            @if($document->nama)
            <span>
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $document->nama }}
            </span>
            @endif
            @if($document->tahun)
            <span>
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $document->tahun }}
            </span>
            @endif
            @if($document->programStudi)
            <span>
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                {{ $document->programStudi->nama }}
            </span>
            @endif
        </div>
    </div>
    <div class="dv-hero-actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('repository.index', $document->kategori) }}" class="dv-btn-back">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
        <button id="dv-fullscreen-btn" class="dv-btn-fullscreen" type="button">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
            Fullscreen
        </button>
    </div>
</div>

{{-- ── Main Layout ──────────────────────────────────────────────── --}}
<div class="dv-layout">

    {{-- ── Viewer ──────────────────────────────────────────────── --}}
    <div class="dv-viewer-col reveal" oncontextmenu="return false">
        <div class="dv-iframe-wrap">
            {{-- Skeleton loader --}}
            <div class="dv-skeleton" id="dv-skeleton">
                <div class="dv-skeleton-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="dv-skeleton-text">
                    <p>Memuat dokumen PDF…</p>
                    <div class="dv-skeleton-bar"></div>
                </div>
            </div>
            {{-- Iframe --}}
            <iframe
                id="dv-iframe"
                src="{{ $fileUrl }}"
                title="Viewer: {{ $document->judul }}"
                allowfullscreen
            ></iframe>
            {{-- Overlay watermark --}}
            <div class="dv-overlay" id="dv-overlay" aria-hidden="true"></div>
        </div>

        @if(! empty($watermark))
            <div class="viewer-watermark" style="pointer-events:none;">{{ $watermark }}</div>
        @endif

        <p class="dv-note">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Dokumen ini dilindungi — hanya dapat dilihat &amp; di-scroll pada halaman ini.
        </p>
    </div>

    {{-- ── Sidebar Metadata ─────────────────────────────────────── --}}
    <aside class="dv-sidebar reveal">

        {{-- Info Dokumen --}}
        <div class="dv-card">
            <p class="dv-card-title">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Info Dokumen
            </p>

            <div class="dv-meta-row">
                <span class="dv-meta-label">Kategori</span>
                <span class="dv-meta-value"><span class="dv-cat-badge">{{ strtoupper($document->kategori) }}</span></span>
            </div>

            @if($document->judul)
            <div class="dv-meta-row">
                <span class="dv-meta-label">Judul</span>
                <span class="dv-meta-value">{{ $document->judul }}</span>
            </div>
            @endif

            @if($document->nama)
            <div class="dv-meta-row">
                <span class="dv-meta-label">{{ in_array($document->kategori, ['pkm','penelitian']) ? 'Dosen' : 'Mahasiswa' }}</span>
                <span class="dv-meta-value">{{ $document->nama }}</span>
            </div>
            @endif

            @if($document->nim)
            <div class="dv-meta-row">
                <span class="dv-meta-label">NIM</span>
                <span class="dv-meta-value">{{ $document->nim }}</span>
            </div>
            @endif

            @if($document->nidn)
            <div class="dv-meta-row">
                <span class="dv-meta-label">NIDN</span>
                <span class="dv-meta-value">{{ $document->nidn }}</span>
            </div>
            @endif

            @if($document->tahun)
            <div class="dv-meta-row">
                <span class="dv-meta-label">Tahun</span>
                <span class="dv-meta-value">{{ $document->tahun }}</span>
            </div>
            @endif

            @if($document->programStudi)
            <div class="dv-meta-row">
                <span class="dv-meta-label">Program Studi</span>
                <span class="dv-meta-value">{{ $document->programStudi->nama }}</span>
            </div>
            @endif

            @if($document->tempat_magang)
            <div class="dv-meta-row">
                <span class="dv-meta-label">Tempat Magang</span>
                <span class="dv-meta-value">{{ $document->tempat_magang }}</span>
            </div>
            @endif
        </div>

        {{-- Abstrak --}}
        @if($document->abstrak || $document->detail)
        <div class="dv-card">
            <p class="dv-card-title">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Abstrak / Ringkasan
            </p>
            <p class="dv-abstrak" id="dv-abstrak-text" style="display:-webkit-box;-webkit-line-clamp:5;-webkit-box-orient:vertical;overflow:hidden;">{{ $document->abstrak ?: $document->detail }}</p>
            <button class="dv-abstrak-toggle" id="dv-abstrak-toggle" type="button">
                Lihat selengkapnya
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" id="dv-toggle-icon"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
        </div>
        @endif

        {{-- Info Perlindungan --}}
        <div class="dv-card">
            <p class="dv-card-title">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Perlindungan Dokumen
            </p>
            <div class="dv-watermark-info">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Dokumen ini dilindungi sistem e-repository. Dilarang mengunduh, menyalin, atau mendistribusikan tanpa izin.</span>
            </div>
        </div>

        {{-- Navigasi --}}
        <div class="dv-card" style="padding:1rem;">
            <a href="{{ route('repository.index', $document->kategori) }}" style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;font-weight:700;color:#72d9ff;transition:opacity .2s;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke Repository {{ strtoupper($document->kategori) }}
            </a>
        </div>

    </aside>
</div>

<script>
(function () {
    const iframe  = document.getElementById('dv-iframe');
    const shell   = document.querySelector('.dv-viewer-col');
    const skeleton = document.getElementById('dv-skeleton');
    const fsBtn   = document.getElementById('dv-fullscreen-btn');

    // ─── Cegah klik kanan & drag ─────────────────────────────
    if (shell) {
        shell.addEventListener('contextmenu', e => e.preventDefault());
        shell.addEventListener('dragstart', e => e.preventDefault());
    }

    // ─── Cegah teks terseleksi ───────────────────────────────
    document.addEventListener('selectstart', function (e) {
        if (!['INPUT','TEXTAREA'].includes(e.target.tagName)) e.preventDefault();
    });

    // ─── Blokir Ctrl+S, Ctrl+P, Ctrl+U ──────────────────────
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && ['s','S','p','P','u','U'].includes(e.key)) {
            e.preventDefault(); e.stopPropagation(); return false;
        }
    });

    // ─── Skeleton: hide when iframe loads ────────────────────
    if (iframe && skeleton) {
        iframe.addEventListener('load', function () {
            skeleton.classList.add('hidden');
            setTimeout(() => { skeleton.style.display = 'none'; }, 450);
        });
        // Fallback: hide after 12s regardless
        setTimeout(() => { skeleton.classList.add('hidden'); }, 12000);
    }

    // ─── Fullscreen ──────────────────────────────────────────
    if (fsBtn && iframe) {
        fsBtn.addEventListener('click', function () {
            if (iframe.requestFullscreen)              iframe.requestFullscreen();
            else if (iframe.webkitRequestFullscreen)  iframe.webkitRequestFullscreen();
            else if (iframe.mozRequestFullScreen)     iframe.mozRequestFullScreen();
        });
    }

    // ─── Abstrak toggle ──────────────────────────────────────
    const abstrakText  = document.getElementById('dv-abstrak-text');
    const abstrakBtn   = document.getElementById('dv-abstrak-toggle');
    const abstrakIcon  = document.getElementById('dv-toggle-icon');
    let expanded = false;
    if (abstrakBtn && abstrakText) {
        abstrakBtn.addEventListener('click', function () {
            expanded = !expanded;
            abstrakText.style.webkitLineClamp = expanded ? 'unset' : '5';
            abstrakText.style.overflow = expanded ? 'visible' : 'hidden';
            abstrakBtn.childNodes[0].textContent = expanded ? 'Sembunyikan ' : 'Lihat selengkapnya ';
            abstrakIcon.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }
})();
</script>

@endsection
