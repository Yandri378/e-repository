@extends('layouts.app')

@section('title', 'Detail Submission')

@section('content')
<section class="page-hero compact reveal">
    <p class="eyebrow">Bukti Submission</p>
    <h1>{{ $document->judul }}</h1>
    <p>Status saat ini: {{ strtoupper($document->status) }}</p>
</section>

<section class="section detail-grid">
    <article class="module-card reveal">
        <h3>Data Pengirim</h3>
        <p>Nama: <strong>{{ $document->nama }}</strong></p>
        <p>Email: <strong>{{ $document->email ?: '-' }}</strong></p>
        <p>Identitas: <strong>{{ $document->nim ?: $document->nidn ?: '-' }}</strong></p>
        <p>Prodi: <strong>{{ $document->programStudi?->nama ?: '-' }}</strong></p>
    </article>
    <article class="module-card reveal">
        <h3>Data Dokumen</h3>
        <p>Kategori: <strong>{{ strtoupper($document->kategori) }}</strong></p>
        <p>Tahun: <strong>{{ $document->tahun }}</strong></p>
        <p>File: <strong>{{ $document->file_dokumen ? 'PDF sudah terupload' : '-' }}</strong></p>
        <p>Project: <strong>{{ $document->file_project ? 'ZIP/RAR sudah terupload' : '-' }}</strong></p>
        <p>Detail: <strong>{{ $document->detail ?: $document->abstrak ?: '-' }}</strong></p>
    </article>
    <div class="section-actions reveal">
        @if($document->file_dokumen)
            <div style="margin-bottom: 1.5rem; text-align: center;">
                <a href="{{ route('repository.bebas-pustaka', [$document, 'token' => $document->submission_token]) }}" class="btn primary" style="display: inline-block; width: 100%; text-align: center;">Download Kartu Bebas Pustaka</a>
            </div>

            @php
                $hasApproved = \App\Models\RepositoryDownloadRequest::where('repository_document_id', $document->id)
                    ->where('submission_token', $document->submission_token)
                    ->where('status', 'approved')
                    ->exists();
            @endphp

            @if($hasApproved)
                <a href="{{ route('repository.download.approved', [$document, 'submission_token' => $document->submission_token]) }}" class="btn primary">Download PDF (disetujui)</a>
            @else
                <form method="POST" action="{{ route('repository.request.download', $document) }}">
                    @csrf
                    <input type="hidden" name="submission_token" value="{{ $document->submission_token }}" />
                    <input type="hidden" name="requester_email" value="{{ $document->email }}" />
                    <label for="requester_phone">Nomor WhatsApp (contoh: 62812xxxx)</label>
                    <input id="requester_phone" name="requester_phone" type="text" value="" required />
                    <label for="message">Pesan (opsional)</label>
                    <textarea id="message" name="message" rows="3" maxlength="500"></textarea>
                    <button type="submit" class="btn">Minta Izin Unduh ke Pustaka</button>
                </form>
            @endif
        @endif
    </div>
</section>
@endsection
