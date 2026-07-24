@extends('layouts.app')

@section('title', 'ACC Dokumen Mahasiswa')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Dosen Pembimbing</p>
    <h1>ACC Laporan Magang dan Skripsi Mahasiswa</h1>
</section>

<section class="section list-stack">
    @forelse ($documents as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ $document->dosen_approved_at ? 'SUDAH ACC' : strtoupper($document->status) }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ $document->nama }} | {{ $document->nim ?: '-' }} | {{ strtoupper($document->kategori) }} | Tahun {{ $document->tahun }}</p>
                <p>{{ $document->programStudi?->nama ?: 'Program studi belum tersedia' }} | {{ $document->jenisDokumen?->nama ?: 'Jenis dokumen belum dipilih' }}</p>
                @if ($document->catatan_dosen)
                    <p>Catatan dosen: {{ $document->catatan_dosen }}</p>
                @endif
            </div>
            <div class="inline-actions">
                @if ($document->file_dokumen)
                    <a class="btn secondary" href="{{ route('repository.file.download', $document) }}">Download PDF</a>
                @endif
                @if ($document->file_project)
                    <a class="btn secondary" href="{{ route('repository.project.download', $document) }}">Download Project</a>
                @endif
                @if (! $document->dosen_approved_at && $document->status === 'pending')
                    <form method="POST" action="{{ route('dosen.approvals.approve', $document) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn primary" type="submit">ACC</button>
                    </form>
                    <form method="POST" action="{{ route('dosen.approvals.reject', $document) }}">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="catatan_dosen" placeholder="Catatan penolakan">
                        <button class="btn secondary" type="submit">Tolak</button>
                    </form>
                @endif
            </div>
        </article>
    @empty
        <div class="empty-state">Belum ada dokumen mahasiswa bimbingan.</div>
    @endforelse

    {{ $documents->links() }}
</section>
@endsection
