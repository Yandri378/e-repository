@extends('layouts.admin')

@section('title', 'Verifikasi Upload')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Admin</p>
    <h1>Verifikasi Upload Dokumen</h1>
</section>

<section class="section list-stack">
    @if ($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif
    @forelse ($documents as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ strtoupper($document->kategori) }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ $document->nama }} | {{ $document->nim ?: $document->nidn }} | Tahun {{ $document->tahun }}</p>
                <p>{{ $document->programStudi?->nama ?: 'Program studi belum tersedia' }} | {{ $document->jenisDokumen?->nama ?: 'Jenis dokumen belum dipilih' }}</p>
                @if (in_array($document->kategori, ['skripsi', 'magang'], true) && $document->dosen_pembimbing_id)
                    <p>Dosen pembimbing: {{ $document->dosenPembimbing?->name ?: '-' }} | {{ $document->dosen_approved_at ? 'Sudah ACC dosen' : 'Menunggu ACC dosen' }}</p>
                @endif
                @if ($document->abstrak)
                    <p>{{ str($document->abstrak)->limit(180) }}</p>
                @endif
            </div>
            <div class="inline-actions">
                @if ($document->file_dokumen)
                    @php
                        $docExt = strtolower(pathinfo($document->file_dokumen, PATHINFO_EXTENSION));
                        $isPdf = $docExt === 'pdf';
                        $docLabel = $isPdf ? 'Lihat PDF' : 'Unduh Berkas (.' . ($docExt ?: 'file') . ')';
                    @endphp
                    <a class="btn secondary" href="{{ route('admin.documents.download', $document) }}" @if($isPdf) target="_blank" @endif>{{ $docLabel }}</a>
                @else
                    <span class="badge muted">File tidak ada</span>
                @endif
                @if ($document->file_project)
                    <a class="btn secondary" href="{{ route('repository.project.download', $document) }}">Download Project</a>
                @endif
                <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="terverifikasi">
                    <button class="btn primary" type="submit">Konfirmasi</button>
                </form>
                <form method="POST" action="{{ route('admin.documents.status', $document) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ditolak">
                    <input type="hidden" name="catatan_verifikasi" value="Dokumen belum sesuai.">
                    <button class="btn secondary" type="submit">Tolak</button>
                </form>
                <form method="POST" action="{{ route('admin.documents.destroy', $document) }}"
                    onsubmit="return confirm(@js('Hapus data dokumen '.$document->judul.' secara permanen? File PDF dan project terkait juga akan dihapus.'))">
                    @csrf
                    @method('DELETE')
                    <button class="btn secondary" type="submit">Hapus</button>
                </form>
            </div>
        </article>
    @empty
        <div class="empty-state">Tidak ada upload dokumen yang menunggu verifikasi.</div>
    @endforelse

    {{ $documents->links() }}
</section>
@endsection
