@extends('layouts.app')

@section('title', 'Detail Submission')

@section('content')
@if (session('status') || session('success'))
    <div style="max-width: 960px; margin: 1.5rem auto 0; padding: 1rem 1.25rem; border-radius: 1rem; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <div>
            <strong style="display: block; font-size: 0.95rem; color: #064e3b;">Data Berhasil Disimpan!</strong>
            <span style="font-size: 0.85rem; color: #047857;">{{ session('success') ?? session('status') }}</span>
        </div>
    </div>
@endif
<section class="page-hero compact reveal">
    <p class="eyebrow">{{ auth()->user()?->role === 'admin' ? 'Preview Data' : 'Bukti Submission' }}</p>
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
        @if($document->dosenPembimbing)
            <p>Dosen Pembimbing: <strong>{{ $document->dosenPembimbing->name }}</strong></p>
        @endif
    </article>
    <article class="module-card reveal">
        <h3>Data Dokumen</h3>
        <p>Kategori: <strong>{{ strtoupper($document->kategori) }}</strong></p>
        <p>Jenis Dokumen: <strong>{{ $document->jenisDokumen?->nama ?: '-' }}</strong></p>
        <p>Tahun: <strong>{{ $document->tahun }}</strong></p>
        <p>Jumlah Halaman: <strong>{{ $document->jumlah_halaman ?: '-' }}</strong></p>
        <p>File: <strong>{{ $document->file_dokumen ? 'PDF sudah terupload' : '-' }}</strong></p>
        <p>Project: <strong>{{ $document->file_project ? 'ZIP/RAR sudah terupload' : '-' }}</strong></p>
        <p>Detail: <strong>{{ $document->detail ?: $document->abstrak ?: '-' }}</strong></p>
    </article>
    <div class="section-actions reveal">
        @if($document->file_dokumen)
            @if(auth()->user()?->role === 'admin')
                <a href="{{ route('repository.download', $document) }}" class="btn primary">Download PDF</a>
                @if($document->file_project)
                    <a href="{{ route('repository.project.download', $document) }}" class="btn">Download Project ZIP/RAR</a>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="btn">Kembali ke Dashboard</a>
            @else
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
        @endif
    </div>
</section>
@endsection
