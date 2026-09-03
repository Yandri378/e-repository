@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="admin-page-header">
    <div class="header-left">
        <div class="header-badge">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <span>Repository Mahasiswa</span>
        </div>
        <h1>Data Skripsi & Magang</h1>
        <p>Kelola, cari, dan unduh data arsip skripsi serta laporan magang mahasiswa Universitas Metamedia.</p>
    </div>
    <div class="header-stats">
        <div class="stat-mini-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <span class="stat-value">{{ number_format($documents->total()) }}</span>
                <span class="stat-label">Total Dokumen</span>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-icon emerald">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </div>
            <div>
                <span class="stat-value" style="text-transform: capitalize;">{{ request('kategori') ?: 'Semua' }}</span>
                <span class="stat-label">Filter Kategori</span>
            </div>
        </div>
    </div>
</div>

<div class="admin-filter-bar">
    <form method="GET" action="{{ route('admin.data.mahasiswa') }}" class="filter-form">
        <div class="search-input-wrap">
            <svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa, NIM, judul, atau tahun..." class="custom-input">
        </div>

        <div class="select-input-wrap">
            <svg class="select-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <select name="kategori" class="custom-select">
                <option value="">Semua Kategori</option>
                <option value="skripsi" {{ request('kategori') === 'skripsi' ? 'selected' : '' }}>Skripsi</option>
                <option value="magang" {{ request('kategori') === 'magang' ? 'selected' : '' }}>Magang</option>
            </select>
        </div>

        <button type="submit" class="btn-filter-submit">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>Cari Data</span>
        </button>

        @if(request('search') || request('kategori'))
            <a href="{{ route('admin.data.mahasiswa') }}" class="btn-filter-reset" title="Reset Filter">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                <span>Reset</span>
            </a>
        @endif
    </form>

    <div class="export-buttons-group">
        <a class="btn-export btn-export-excel" href="{{ route('admin.documents.import', ['kategori' => request('kategori') ?: 'magang']) }}">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <span>Import Excel</span>
        </a>
        <a class="btn-export btn-export-excel" href="{{ route('admin.data.mahasiswa.export', 'excel') }}?search={{ urlencode(request('search') ?? '') }}&kategori={{ urlencode(request('kategori') ?? '') }}">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
            <span>Export Excel</span>
        </a>
        <a class="btn-export btn-export-pdf" href="{{ route('admin.data.mahasiswa.export', 'pdf') }}?search={{ urlencode(request('search') ?? '') }}&kategori={{ urlencode(request('kategori') ?? '') }}">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
            <span>Export PDF</span>
        </a>
    </div>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="modern-data-table">
            <thead>
                <tr>
                    <th style="width: 110px;">Kategori</th>
                    <th style="width: 220px;">Mahasiswa</th>
                    <th style="width: 130px;">NIM</th>
                    <th>Judul Dokumen</th>
                    <th style="width: 160px;">Program Studi</th>
                    <th style="width: 90px; text-align: center;">Tahun</th>
                    <th style="width: 240px;">Abstrak / Detail</th>
                    <th style="width: 110px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td>
                            @if(strtolower($document->kategori ?? '') === 'skripsi')
                                <span class="tag-badge tag-skripsi">
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                    Skripsi
                                </span>
                            @else
                                <span class="tag-badge tag-magang">
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                                    Magang
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="student-profile-cell">
                                <div class="student-avatar-circle">
                                    {{ strtoupper(substr($document->nama, 0, 1)) }}
                                </div>
                                <div class="student-info">
                                    <span class="student-name">{{ $document->nama }}</span>
                                    @if($document->email)
                                        <a href="mailto:{{ $document->email }}" class="student-email" title="{{ $document->email }}">
                                            {{ $document->email }}
                                        </a>
                                    @else
                                        <span class="text-muted-xs">-</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="nim-code-badge">{{ $document->nim ?: '-' }}</span>
                        </td>
                        <td>
                            <div class="document-title-text" title="{{ $document->judul }}">
                                {{ $document->judul }}
                            </div>
                        </td>
                        <td>
                            <span class="prodi-text">{{ $document->programStudi?->nama ?: '-' }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="year-pill">{{ $document->tahun }}</span>
                        </td>
                        <td>
                            <div class="detail-snippet" title="{{ $document->detail ?: $document->abstrak ?: '-' }}">
                                {{ \Illuminate\Support\Str::limit($document->detail ?: $document->abstrak ?: '-', 90) }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <div class="inline-flex flex-wrap justify-center gap-2">
                                @if ($document->file_dokumen)
                                    <a href="{{ route('admin.documents.download', $document) }}" target="_blank"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Lihat PDF
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.documents.destroy', $document) }}"
                                    onsubmit="return confirm(@js('Hapus data dokumen '.$document->judul.' secara permanen? File PDF dan project terkait juga akan dihapus.'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state-card">
                                <div class="empty-icon-wrap">
                                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                </div>
                                <h3>Data Tidak Ditemukan</h3>
                                <p>Tidak ada data mahasiswa yang sesuai dengan pencarian atau filter Anda.</p>
                                @if(request('search') || request('kategori'))
                                    <a href="{{ route('admin.data.mahasiswa') }}" class="btn-filter-reset-empty">Tampilkan Semua Data</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($documents->hasPages())
        <div class="table-pagination-footer">
            <div class="pagination-info">
                Menampilkan <strong>{{ $documents->firstItem() ?: 0 }}</strong> - <strong>{{ $documents->lastItem() ?: 0 }}</strong> dari <strong>{{ $documents->total() }}</strong> mahasiswa
            </div>
            <div class="pagination-links">
                {{ $documents->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
