@extends('layouts.app')

@section('title', 'Panduan & Template')

@section('content')

<style>
    /* ── Hero ───────────────────────────────────────────────── */
    .guide-hero {
        background: linear-gradient(135deg, rgba(14,114,164,.55), rgba(5,37,62,.85));
        border-bottom: 1px solid rgba(114,217,255,.15);
        padding: clamp(3rem,8vw,5rem) clamp(1rem,5vw,5rem);
        text-align: center;
    }
    .guide-hero .eyebrow-tag {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .35rem 1rem;
        border-radius: 999px;
        background: rgba(114,217,255,.14);
        border: 1px solid rgba(114,217,255,.28);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .1em;
        color: #72d9ff;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .guide-hero h1 {
        font-size: clamp(2rem,5vw,3.5rem);
        font-weight: 900;
        color: #ffffff;
        margin: 0 0 .8rem;
        line-height: 1.1;
    }
    .guide-hero p {
        font-size: clamp(.9rem,2vw,1.05rem);
        color: rgba(255,255,255,.65);
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* ── Steps Section ──────────────────────────────────────── */
    .guide-steps-section {
        width: min(1100px, calc(100% - 2rem));
        margin: clamp(2.5rem,6vw,4rem) auto;
    }
    .guide-section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .72rem;
        font-weight: 800;
        color: #0b8fe8;
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-bottom: .6rem;
    }
    [data-theme="dark"] .guide-section-eyebrow { color: #72d9ff; }
    .guide-section-eyebrow span {
        display: inline-block;
        width: 18px; height: 2px;
        border-radius: 2px;
        background: linear-gradient(90deg,#0b8fe8,transparent);
    }
    [data-theme="dark"] .guide-section-eyebrow span {
        background: linear-gradient(90deg,#72d9ff,transparent);
    }
    .guide-section-title {
        font-size: clamp(1.5rem,3.5vw,2.4rem);
        font-weight: 900;
        color: var(--ink);
        margin: 0 0 1.8rem;
        line-height: 1.1;
    }

    /* Step cards grid */
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.2rem;
    }
    .step-card {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        padding: 1.6rem 1.4rem;
        border: 1px solid rgba(114,217,255,.18);
        background: linear-gradient(145deg, rgba(22,111,153,.62), rgba(12,58,90,.72));
        box-shadow: 0 20px 56px rgba(5,73,112,.2), inset 0 1px 0 rgba(255,255,255,.13);
        transition: transform .25s cubic-bezier(.2,.9,.2,1), box-shadow .25s ease, border-color .25s;
        display: flex;
        flex-direction: column;
        gap: .8rem;
    }
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 36px 80px rgba(5,73,112,.32);
        border-color: rgba(114,217,255,.38);
    }
    .step-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle at 80% 10%, rgba(114,217,255,.1), transparent 60%);
        pointer-events: none;
    }
    .step-num {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: rgba(114,217,255,.15);
        border: 1px solid rgba(114,217,255,.28);
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        font-weight: 900;
        color: #72d9ff;
        flex-shrink: 0;
    }
    .step-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(114,217,255,.12);
        border: 1px solid rgba(114,217,255,.2);
        display: flex; align-items: center; justify-content: center;
        color: #72d9ff;
        flex-shrink: 0;
    }
    .step-header {
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .step-card h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #e8f6ff;
    }
    .step-card p {
        margin: 0;
        font-size: .84rem;
        color: rgba(255,255,255,.62);
        line-height: 1.65;
        flex: 1;
    }

    /* ── Ketentuan Section ───────────────────────────────────── */
    .guide-rules-section {
        width: min(1100px, calc(100% - 2rem));
        margin: 0 auto clamp(2rem,5vw,4rem);
    }
    .rules-card {
        border-radius: 20px;
        border: 1px solid rgba(114,217,255,.18);
        background: linear-gradient(145deg, rgba(22,111,153,.55), rgba(12,58,90,.68));
        box-shadow: 0 24px 64px rgba(5,73,112,.22), inset 0 1px 0 rgba(255,255,255,.12);
        padding: clamp(1.5rem,4vw,2.5rem);
    }
    .rules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
        gap: .8rem;
        margin-top: 1.4rem;
    }
    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: .8rem;
        padding: .9rem 1rem;
        border-radius: 12px;
        border: 1px solid rgba(114,217,255,.12);
        background: rgba(255,255,255,.04);
        transition: border-color .2s, background .2s;
    }
    .rule-item:hover {
        border-color: rgba(114,217,255,.28);
        background: rgba(255,255,255,.07);
    }
    .rule-dot {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: rgba(114,217,255,.14);
        border: 1px solid rgba(114,217,255,.25);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        color: #72d9ff;
    }
    .rule-item span {
        font-size: .84rem;
        font-weight: 600;
        color: rgba(255,255,255,.72);
        line-height: 1.55;
        padding-top: .2rem;
    }

    /* ── Template Download Section ───────────────────────────── */
    .guide-templates-section {
        width: min(1100px, calc(100% - 2rem));
        margin: 0 auto clamp(3rem,7vw,5rem);
    }
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
        gap: 1.2rem;
    }
    .template-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        border: 1px solid rgba(114,217,255,.18);
        background: linear-gradient(145deg, rgba(22,111,153,.6), rgba(12,58,90,.7));
        box-shadow: 0 16px 48px rgba(5,73,112,.18), inset 0 1px 0 rgba(255,255,255,.12);
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        gap: .75rem;
        transition: transform .25s cubic-bezier(.2,.9,.2,1), box-shadow .25s ease, border-color .25s;
    }
    .template-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 32px 72px rgba(5,73,112,.3);
        border-color: rgba(114,217,255,.35);
    }
    .template-badge {
        display: inline-flex;
        padding: .2rem .65rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .05em;
        background: rgba(114,217,255,.15);
        border: 1px solid rgba(114,217,255,.28);
        color: #72d9ff;
        align-self: flex-start;
    }
    .template-card h3 {
        margin: 0;
        font-size: .98rem;
        font-weight: 800;
        color: #e8f6ff;
        line-height: 1.4;
    }
    .template-card p {
        margin: 0;
        font-size: .82rem;
        color: rgba(255,255,255,.58);
        line-height: 1.6;
        flex: 1;
    }
    .template-link {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        font-size: .8rem;
        font-weight: 700;
        color: #72d9ff;
        transition: gap .2s ease, opacity .2s;
        align-self: flex-start;
        margin-top: auto;
    }
    .template-link:hover { gap: .7rem; opacity: .8; }
    .guide-empty {
        grid-column: 1 / -1;
        padding: 3rem;
        text-align: center;
        color: rgba(255,255,255,.45);
        border: 1px dashed rgba(114,217,255,.22);
        border-radius: 16px;
        font-weight: 600;
        font-size: .88rem;
    }
</style>

{{-- ── Hero ──────────────────────────────────────────────────────── --}}
<div class="guide-hero">
    <p class="eyebrow-tag">
        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Panduan & Template
    </p>
    <h1>Panduan Penggunaan<br>E-Repository</h1>
    <p>Ikuti langkah-langkah berikut untuk upload dokumen, melacak status verifikasi, dan mengakses template resmi kampus.</p>
</div>

{{-- ── Langkah-Langkah ─────────────────────────────────────────── --}}
<section class="guide-steps-section">
    <p class="guide-section-eyebrow"><span></span>Alur Penggunaan</p>
    <h2 class="guide-section-title">Langkah-Langkah Upload Dokumen</h2>

    <div class="steps-grid">
        @foreach ([
            ['01', 'Daftar & Aktivasi Akun', 'Mahasiswa dan dosen mendaftar sesuai role, memilih program studi, mengisi NIM atau NIDN. Tunggu akun diaktifkan oleh admin sebelum bisa login.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'],
            ['02', 'Tunggu Sesi Upload Dibuka', 'Admin membuka sesi upload sesuai jadwal. Pantau status sesi di halaman beranda untuk mengetahui kapan bisa mengunggah dokumen.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'],
            ['03', 'Upload Dokumen PDF', 'Masuk ke dashboard, pilih kategori yang sesuai, lengkapi metadata dokumen (judul, tahun, abstrak), lalu unggah file PDF maksimal 10 MB.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>'],
            ['04', 'ACC Dosen Pembimbing', 'Untuk skripsi/TA dan laporan magang, dosen pembimbing perlu menyetujui dokumen. Hubungi dosen pembimbing untuk mempercepat proses ini.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'],
            ['05', 'Konfirmasi via WhatsApp', 'Setelah upload berhasil, tekan tombol WhatsApp Admin. Pesan otomatis berisi data dokumen Anda akan langsung terkirim ke admin.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>'],
            ['06', 'Verifikasi Admin & Publish', 'Admin memeriksa dokumen Anda. Jika disetujui, dokumen akan tampil di repository publik. Jika ditolak, perbaiki sesuai catatan admin dan upload ulang.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'],
        ] as [$num, $title, $desc, $icon])
        <article class="step-card reveal">
            <div class="step-header">
                <div class="step-num">{{ $num }}</div>
                <div class="step-icon">{!! $icon !!}</div>
            </div>
            <h3>{{ $title }}</h3>
            <p>{{ $desc }}</p>
        </article>
        @endforeach
    </div>
</section>

{{-- ── Ketentuan Upload ─────────────────────────────────────────── --}}
<section class="guide-rules-section">
    <div class="rules-card reveal">
        <p class="guide-section-eyebrow"><span></span>Ketentuan Upload</p>
        <h2 class="guide-section-title" style="margin-bottom:0">Pastikan dokumen siap sebelum dikirim.</h2>

        <div class="rules-grid">
            @foreach ([
                ['Format file wajib PDF.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'],
                ['Ukuran maksimal file 10 MB.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>'],
                ['Judul, nama, NIM/NIDN, tahun, dan program studi harus diisi dengan benar.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>'],
                ['Mahasiswa hanya mengupload skripsi/TA dan laporan magang.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>'],
                ['Dosen hanya mengupload PKM dan penelitian.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'],
                ['Status awal upload adalah pending sampai admin melakukan verifikasi.', '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'],
            ] as [$rule, $icon])
            <div class="rule-item">
                <div class="rule-dot">{!! $icon !!}</div>
                <span>{{ $rule }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Template Dokumen ─────────────────────────────────────────── --}}
<section class="guide-templates-section">
    <p class="guide-section-eyebrow"><span></span>Template Resmi</p>
    <h2 class="guide-section-title">Unduh Template Dokumen</h2>

    <div class="template-grid">
        @forelse ($guides as $guide)
            <article class="template-card reveal">
                <span class="template-badge">{{ strtoupper($guide->kategori) }}</span>
                <h3>{{ $guide->judul }}</h3>
                @if($guide->deskripsi)
                    <p>{{ $guide->deskripsi }}</p>
                @endif
                @if ($guide->file_path)
                    <a class="template-link" href="{{ asset('storage/'.$guide->file_path) }}" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh Template
                    </a>
                @endif
            </article>
        @empty
            <div class="guide-empty">
                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto .8rem;display:block;opacity:.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Template belum tersedia. Hubungi admin untuk informasi lebih lanjut.
            </div>
        @endforelse
    </div>
</section>

@endsection
