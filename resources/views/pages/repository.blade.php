@extends('layouts.app')

@section('title', 'Repository')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Repository</p>
    <h1>{{ $kategori ? ucfirst($kategori) : 'Semua Dokumen' }}</h1>
    <form class="search-bar" method="GET">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul, nama, NIM, NIDN...">
        <button type="submit">Cari</button>
    </form>
</section>

<section class="section list-stack">
    @forelse ($documents as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ strtoupper($document->status) }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ $document->nama }} | {{ $document->tahun }} | {{ strtoupper($document->kategori) }}</p>
            </div>
            <div class="inline-actions">
                @if ($document->file_dokumen)
                    <a class="btn secondary" href="{{ route('repository.show', $document) }}">Lihat Dokumen</a>
                @endif
                <span class="badge muted">{{ $document->jenis_input }}</span>
            </div>
        </article>
    @empty
        <div class="empty-state">Belum ada data repository untuk filter ini.</div>
    @endforelse
    {{ $documents->links() }}
</section>
@endsection
