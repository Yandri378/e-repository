@extends('layouts.app')

@section('title', $title)

@section('content')
@php
    $openCategories = collect($categories)->filter(fn ($label, $key) => $uploadStatuses[$key] ?? false);
@endphp

@if ($openCategories->isNotEmpty())
    <section class="session-ticker actor-ticker" aria-label="Informasi sesi kompre">
        <div class="ticker-track">
            <span>Running text: sesi {{ $openCategories->values()->join(', ') }} sedang dibuka oleh admin. Silakan upload dokumen sebelum sesi ditutup.</span>
            <span aria-hidden="true">Running text: sesi {{ $openCategories->values()->join(', ') }} sedang dibuka oleh admin. Silakan upload dokumen sebelum sesi ditutup.</span>
        </div>
    </section>
@else
    <section class="session-ticker actor-ticker muted" aria-label="Informasi sesi kompre">
        <div class="ticker-track">
            <span>Running text: semua sesi upload belum dibuka. Form akan aktif setelah admin membuka sesi dari dashboard admin.</span>
            <span aria-hidden="true">Running text: semua sesi upload belum dibuka. Form akan aktif setelah admin membuka sesi dari dashboard admin.</span>
        </div>
    </section>
@endif

<section class="hero actor-hero reveal">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="eyebrow">{{ $heroLabel }}</p>
        <h1>{{ $heroHeading }}</h1>
        <p>{{ $heroDescription }}</p>
        <div class="hero-actions">
            @foreach ($categories as $key => $label)
                @if ($uploadStatuses[$key] ?? false)
                    <a href="{{ route('public.upload.create', [$actor, $key]) }}" class="btn primary">{{ $label }} - {{ $ctaLabel }}</a>
                @else
                    <span class="btn disabled">{{ $label }} - Ditutup</span>
                @endif
            @endforeach
            <a href="#status-sesi" class="btn secondary">Cek status sesi</a>
            <a href="{{ route('repository.index') }}" class="btn secondary">Lihat repository</a>
        </div>
    </div>
</section>

<section id="status-sesi" class="section actor-workspace reveal">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Sesi Upload</p>
            <h2>Pilih dokumen yang ingin diunggah</h2>
        </div>
        <a class="btn secondary" href="{{ route('guides.index') }}">Buka panduan</a>
    </div>

    <div class="actor-upload-grid">
        @foreach ($categories as $key => $label)
            @php $isOpen = $uploadStatuses[$key] ?? false; @endphp
            <article class="actor-upload-card {{ $isOpen ? 'is-open' : 'is-closed' }}">
                <div>
                    <span class="status-pill {{ $isOpen ? 'open' : 'closed' }}">{{ $isOpen ? 'Dibuka admin' : 'Belum dibuka' }}</span>
                    <h3>{{ $label }}</h3>
                    <p>{{ $isOpen ? 'Form sudah aktif dan siap menerima unggahan PDF.' : 'Tombol upload akan aktif setelah admin membuka sesi.' }}</p>
                </div>
                @if ($isOpen)
                    <a href="{{ route('public.upload.create', [$actor, $key]) }}" class="btn primary full">Upload {{ $label }}</a>
                @else
                    <span class="btn disabled full">Menunggu sesi</span>
                @endif
            </article>
        @endforeach
    </div>

    <div class="actor-helper-grid">
        <article class="actor-card">
            <span class="step-number">01</span>
            <h3>Pilih jenis dokumen</h3>
            <p>Gunakan tombol upload sesuai kategori yang dibuka oleh admin.</p>
        </article>
        <article class="actor-card">
            <span class="step-number">02</span>
            <h3>Isi data dan unggah PDF</h3>
            <p>Lengkapi metadata dengan rapi agar proses verifikasi lebih mudah.</p>
        </article>
        <article class="actor-card">
            <span class="step-number">03</span>
            <h3>Simpan detail submission</h3>
            <p>Setelah upload, halaman detail submission akan tampil sebagai bukti.</p>
        </article>
    </div>
</section>

<section class="section reveal">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Fitur</p>
            <h2>Form upload tanpa akun</h2>
        </div>
    </div>
    <div class="feature-grid">
        <article class="feature-card">
            <h3>Upload Cepat</h3>
            <p>Anda cukup pilih dokumen, unggah PDF, dan isi detail tanpa registrasi.</p>
            <a class="text-link" href="#status-sesi">Pilih sesi</a>
        </article>
        <article class="feature-card">
            <h3>Notifikasi Setelah Submit</h3>
            <p>Setelah berhasil upload, halaman detail submission langsung ditampilkan.</p>
            <a class="text-link" href="{{ route('guides.index') }}">Lihat alur</a>
        </article>
        <article class="feature-card">
            <h3>Aman dan Terstruktur</h3>
            <p>Upload Anda masuk ke antrean verifikasi sebelum tampil di repository umum.</p>
            <a class="text-link" href="{{ route('repository.index') }}">Buka repository</a>
        </article>
    </div>
</section>
@endsection
