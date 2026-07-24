@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    /* Styling ikon SVG untuk dashboard admin */
    .icon { width: 1em; height: 1em; vertical-align: -0.15em; flex-shrink: 0; margin-right: 0.35rem; }
    .icon-sm { width: 0.9em; height: 0.9em; }
    .icon-lg { width: 2.25rem; height: 2.25rem; margin: 0 auto 0.75rem; display: block; opacity: 0.5; }
    .icon-dot { width: 0.5em; height: 0.5em; margin-right: 0.4rem; }
    .eyebrow { display: inline-flex; align-items: center; gap: 0.35rem; }
    .eyebrow .icon { margin-right: 0; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; }
    .btn .icon { margin-right: 0; }
    .status-list b, h3 { display: inline-flex; align-items: center; gap: 0.4rem; }
    .status-list b .icon, h3 .icon { margin-right: 0; }
    .setting-list span b { display: inline-flex; align-items: center; }
    .empty-state { text-align: center; padding: 2rem 1rem; color: var(--muted, #8b93a7); }
    .upload-toggle-form.is-open .icon-dot { color: var(--success, #22c55e); }
    .upload-toggle-form.is-closed .icon-dot { color: var(--danger, #ef4444); }
</style>

<section class="page-hero compact admin-dashboard-hero reveal">
    <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg> Dashboard Admin</p>
    <h1>Pusat kontrol repository, verifikasi, akun, dan laporan kampus.</h1>
    <div class="hero-actions">
        <a class="btn primary" href="#setting-upload">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            Atur Sesi Upload
        </a>
        <a class="btn secondary" href="{{ route('reports.index') }}">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 13h6M9 17h6M9 9h1"/></svg>
            Buka Laporan
        </a>
    </div>
</section>

<section class="section admin-overview reveal">
    <div class="overview-main">
        <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg> Perlu Ditindak</p>
        <h2>{{ $pendingUsers + $pendingDocuments }} antrean menunggu keputusan</h2>
        <p>{{ $pendingUsers }} akun baru dan {{ $pendingDocuments }} dokumen upload perlu diverifikasi admin.</p>
        <div class="hero-actions">
            <a class="btn primary" href="{{ route('admin.documents.pending') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="m9 15 2 2 4-4"/></svg>
                Cek Upload
            </a>
            <a class="btn secondary" href="{{ route('admin.users.pending') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Cek Akun
            </a>
        </div>
    </div>
    <div class="overview-metrics">
        <div>
            <span><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> Total Dokumen</span>
            <strong>{{ $totalDocuments }}</strong>
        </div>
        <div>
            <span><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> Upload Bulan Ini</span>
            <strong>{{ $documentsThisMonth }}</strong>
        </div>
        <div>
            <span><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg> Terverifikasi Bulan Ini</span>
            <strong>{{ $verifiedThisMonth }}</strong>
        </div>
    </div>
</section>

<div class="admin-card-strip reveal">
    @include('dashboards.partials.cards')
</div>

<section class="section module-card admin-panel reveal">
    <div class="section-heading">
        <div>
            <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg> Diagram Batang</p>
            <h2>Ringkasan data utama</h2>
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
    <div class="bar-chart">
        @foreach ($chartRows as $label => $value)
            <div class="bar-row">
                <span>{{ $label }}</span>
                <div class="bar-track"><strong style="width: {{ max(6, round(($value / $chartMax) * 100)) }}%">{{ $value }}</strong></div>
            </div>
        @endforeach
    </div>
</section>

<section class="section module-grid admin-module-grid">
    <article class="module-card admin-panel reveal">
        <h3>
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            Status Dokumen
        </h3>
        <div class="status-list">
            @php
                $statusIcons = [
                    'pending' => '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>',
                    'terverifikasi' => '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg>',
                    'arsip' => '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="5" rx="1"/><path d="M5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9M10 13h4"/></svg>',
                    'ditolak' => '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>',
                ];
            @endphp
            @foreach (['pending' => 'Pending', 'terverifikasi' => 'Terverifikasi', 'arsip' => 'Arsip', 'ditolak' => 'Ditolak'] as $status => $label)
                <span><b>{!! $statusIcons[$status] !!} {{ $label }}</b><strong>{{ $statusCounts[$status] ?? 0 }}</strong></span>
            @endforeach
        </div>
    </article>
    <article class="module-card admin-panel reveal">
        <h3>
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Komposisi Akun
        </h3>
        <div class="status-list">
            <span><b><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.66 2.69 3 6 3s6-1.34 6-3v-5"/></svg> Mahasiswa</b><strong>{{ $roleCounts['mahasiswa'] ?? 0 }}</strong></span>
            <span><b><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg> Dosen</b><strong>{{ $roleCounts['dosen'] ?? 0 }}</strong></span>
        </div>
        <p class="admin-helper-text"><svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg> Data ini hanya tampil di panel admin.</p>
    </article>
    <article class="module-card admin-panel reveal">
        <h3>
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
            Aksi Cepat
        </h3>
        <div class="quick-links">
            <a class="btn secondary" href="{{ route('repository.create', 'skripsi') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M12 11v6M9 14h6"/></svg>
                Tambah Arsip Skripsi
            </a>
            <a class="btn secondary" href="{{ route('repository.create', 'magang') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Tambah Arsip Magang
            </a>
            <a class="btn secondary" href="{{ route('repository.create', 'penelitian') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                Tambah Penelitian
            </a>
            <a class="btn primary" href="{{ route('reports.index') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                Buka Laporan
            </a>
        </div>
    </article>
    <article class="module-card admin-panel admin-session-panel reveal" id="setting-upload">
        <div class="admin-panel-title">
            <div>
                <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg> Kontrol Admin</p>
                <h3>Sesi Upload</h3>
            </div>
            <a class="btn secondary" href="{{ route('home') }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Cek Beranda
            </a>
        </div>
        <p class="admin-helper-text">Aktifkan kategori yang boleh diunggah mahasiswa atau dosen. Perubahan tombol langsung memengaruhi running text dan form publik.</p>
        <div class="hero-actions">
            <form method="POST" action="{{ route('admin.settings.upload-session') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="kategori" value="all">
                <input type="hidden" name="status" value="open">
                <button class="btn primary" type="submit">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                    Buka Semua Sesi
                </button>
            </form>
            <form method="POST" action="{{ route('admin.settings.upload-session') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="kategori" value="all">
                <input type="hidden" name="status" value="closed">
                <button class="btn secondary" type="submit">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Tutup Semua Sesi
                </button>
            </form>
        </div>
        <div class="setting-list">
            @foreach ($uploadStatuses as $kategori => $isOpen)
                <form method="POST" action="{{ route('admin.settings.upload-session') }}" class="upload-toggle-form {{ $isOpen ? 'is-open' : 'is-closed' }}" data-kategori="{{ $kategori }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                    <input type="hidden" name="status" value="{{ $isOpen ? 'closed' : 'open' }}">
                    <span>
                        <b>
                            <svg class="icon icon-dot" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>
                            {{ strtoupper($kategori) }}
                        </b>
                        <small class="upload-status">{{ $isOpen ? 'Dibuka untuk upload' : 'Ditutup untuk upload' }}</small>
                    </span>
                    <button class="btn {{ $isOpen ? 'secondary' : 'primary' }} toggle-btn" type="submit">{{ $isOpen ? 'Tutup' : 'Buka' }}</button>
                </form>
            @endforeach
        </div>
    </article>
</section>

<section class="section admin-columns reveal">
    <div class="list-stack">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Verifikasi Upload</p>
                <h2>Dokumen terbaru</h2>
            </div>
            <a class="text-link" href="{{ route('admin.documents.pending') }}">Lihat semua &rarr;</a>
        </div>

        @forelse ($latestPendingDocuments as $document)
            <article class="document-row">
                <div>
                    <span class="badge">{{ strtoupper($document->kategori) }}</span>
                    <h2>{{ $document->judul }}</h2>
                    <p>{{ $document->nama }} | {{ $document->nim ?: $document->nidn }} | {{ $document->programStudi?->nama ?: 'Prodi belum dipilih' }}</p>
                </div>
                <div class="inline-actions">
                    @if ($document->file_dokumen)
                        <a class="btn secondary" href="{{ route('admin.documents.download', $document) }}">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            Lihat PDF
                        </a>
                    @endif
                    @if ($document->file_project)
                        <a class="btn secondary" href="{{ route('repository.project.download', $document) }}">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
                            Download Project
                        </a>
                    @endif
                    <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="terverifikasi">
                        <button class="btn primary" type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Konfirmasi
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <input type="hidden" name="catatan_verifikasi" value="Dokumen belum sesuai.">
                        <button class="btn secondary" type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                Belum ada dokumen yang menunggu verifikasi.
            </div>
        @endforelse
    </div>

    <div class="list-stack">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 8v6M19 11h6"/></svg> Verifikasi Akun Baru</p>
                <h2>Pendaftar terbaru</h2>
            </div>
            <a class="text-link" href="{{ route('admin.users.pending') }}">Lihat semua &rarr;</a>
        </div>

        @forelse ($latestPendingUsers as $user)
            <article class="document-row">
                <div>
                    <span class="badge">{{ strtoupper($user->role) }}</span>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }} | {{ $user->nim ?: $user->nidn }} | {{ $user->programStudi?->nama ?: 'Prodi belum dipilih' }}</p>
                </div>
                <div class="inline-actions">
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="aktif">
                        <button class="btn primary" type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Terima
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="ditolak">
                        <button class="btn secondary" type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Belum ada akun mahasiswa/dosen yang menunggu verifikasi.
            </div>
        @endforelse
    </div>
</section>

<section class="section admin-columns reveal">
    <div class="list-stack">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg> Aktivitas Repository</p>
                <h2>Update terakhir</h2>
            </div>
            <a class="text-link" href="{{ route('admin.documents.index') }}">Kelola dokumen &rarr;</a>
        </div>
        @forelse ($recentDocuments as $document)
            <article class="document-row compact-row">
                <div>
                    <span class="badge muted">{{ strtoupper($document->status) }}</span>
                    <h2>{{ $document->judul }}</h2>
                    <p>{{ $document->nama }} | {{ $document->kategori }} | {{ $document->updated_at->diffForHumans() }}</p>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                Belum ada aktivitas repository.
            </div>
        @endforelse
    </div>

    <div class="list-stack">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg> Sebaran Prodi</p>
                <h2>Akun per program studi</h2>
            </div>
            <a class="text-link" href="{{ route('admin.users.index') }}">Kelola akun &rarr;</a>
        </div>
        @forelse ($programDistribution as $program)
            @php
                $percent = $totalDocuments > 0 ? min(100, round(($program->documents_count / $totalDocuments) * 100)) : 0;
            @endphp
            <article class="program-row">
                <div>
                    <strong>{{ $program->nama }}</strong>
                    <span>{{ $program->documents_count }} dokumen</span>
                </div>
                <div class="progress-track"><span style="width: {{ $percent }}%"></span></div>
            </article>
        @empty
            <div class="empty-state">
                <svg class="icon icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/></svg>
                Belum ada data program studi.
            </div>
        @endforelse
    </div>
</section>

<section class="section admin-only-note reveal">
    <p class="eyebrow"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg> Area Admin</p>
    <h2>Informasi antrean, kontak pendaftar, dokumen pending, dokumen ditolak, dan laporan internal hanya tersedia untuk admin.</h2>
    <p>Mahasiswa dan dosen tetap hanya melihat dashboard, upload, dan riwayat dokumen milik akun mereka sendiri.</p>
</section>
@endsection