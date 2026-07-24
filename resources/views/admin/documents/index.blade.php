@extends('layouts.admin')

@section('title', 'Kelola Upload')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Admin</p>
    <h1>Kelola Semua Upload Repository</h1>
</section>

<section class="section list-stack">
    @if ($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif
    @forelse ($documents as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ strtoupper($document->status) }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ strtoupper($document->kategori) }} | {{ $document->nama }} | {{ $document->tahun }}</p>
                @if (in_array($document->kategori, ['skripsi', 'magang'], true) && $document->dosen_pembimbing_id)
                    <p>Dosen pembimbing: {{ $document->dosenPembimbing?->name ?: '-' }} | {{ $document->dosen_approved_at ? 'Sudah ACC dosen' : 'Menunggu ACC dosen' }}</p>
                @endif
            </div>
            <div class="inline-actions">
                @if ($document->file_dokumen)
                    <a class="btn secondary" href="{{ route('admin.documents.download', $document) }}">Lihat PDF</a>
                @endif
                @if ($document->file_project)
                    <a class="btn secondary" href="{{ route('repository.project.download', $document) }}">Download Project</a>
                @endif
                <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="terverifikasi">
                    <button class="btn primary" type="submit">Terverifikasi</button>
                </form>
                <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ditolak">
                    <button class="btn secondary" type="submit">Tolak</button>
                </form>
            </div>
        </article>
    @empty
        <div class="empty-state">Belum ada dokumen repository.</div>
    @endforelse

    {{ $documents->links() }}
</section>
@endsection
