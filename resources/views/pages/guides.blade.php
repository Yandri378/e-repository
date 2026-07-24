@extends('layouts.app')

@section('title', 'Panduan & Template')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Panduan & Template</p>
    <h1>Panduan penggunaan repository kampus.</h1>
</section>

<section class="section guide-steps">
    <article>
        <span>01</span>
        <h2>Daftar Akun</h2>
        <p>Mahasiswa dan dosen mendaftar sesuai role, memilih program studi, mengisi NIM atau NIDN, lalu menunggu akun diverifikasi admin.</p>
    </article>
    <article>
        <span>02</span>
        <h2>Upload Dokumen</h2>
        <p>Masuk ke dashboard, pilih kategori yang sesuai, lengkapi metadata dokumen, lalu unggah file PDF maksimal 10 MB.</p>
    </article>
    <article>
        <span>03</span>
        <h2>Kirim Konfirmasi</h2>
        <p>Setelah upload berhasil, tekan tombol WhatsApp Admin. Pesan otomatis akan berisi data dokumen yang baru diunggah.</p>
    </article>
    <article>
        <span>04</span>
        <h2>Tunggu Verifikasi</h2>
        <p>Admin memeriksa dokumen. Dokumen yang disetujui akan tampil di repository publik, sedangkan dokumen yang ditolak perlu diperbaiki.</p>
    </article>
</section>

<section class="section guide-rules">
    <div>
        <p class="eyebrow">Ketentuan Upload</p>
        <h2>Pastikan dokumen siap sebelum dikirim.</h2>
    </div>
    <ul>
        <li>Format file wajib PDF.</li>
        <li>Ukuran maksimal file 10 MB.</li>
        <li>Judul, nama, NIM/NIDN, tahun, dan program studi harus benar.</li>
        <li>Mahasiswa hanya mengupload skripsi/TA dan laporan magang.</li>
        <li>Dosen hanya mengupload PKM dan penelitian.</li>
        <li>Status awal upload adalah pending sampai admin melakukan verifikasi.</li>
    </ul>
</section>

<section class="section module-grid">
    @forelse ($guides as $guide)
        <article class="module-card">
            <span class="badge">{{ strtoupper($guide->kategori) }}</span>
            <h3>{{ $guide->judul }}</h3>
            <p>{{ $guide->deskripsi }}</p>
            @if ($guide->file_path)
                <a class="text-link" href="{{ asset('storage/'.$guide->file_path) }}" target="_blank" rel="noopener">Buka template</a>
            @endif
        </article>
    @empty
        <div class="empty-state">Panduan belum tersedia.</div>
    @endforelse
</section>
@endsection
