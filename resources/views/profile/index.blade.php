@extends('layouts.app')

@section('title', 'Profil Akun Saya')

@section('content')
<section class="page-hero compact">
    <div class="page-hero-content">
        <p class="eyebrow">Pengaturan Akun</p>
        <h1>Profil Saya</h1>
        <p class="hero-lead">Kelola informasi pribadi, ubah password, dan pantau status dokumen repository Anda.</p>
    </div>
</section>

<section class="section">
    {{-- Header Banner Card Akun --}}
    <div class="profile-header-card">
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar-lg">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="profile-main-info">
                <h2>{{ $user->name }}</h2>
                <div class="profile-badges">
                    <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    @if ($user->nim)
                        <span class="id-badge">NIM: {{ $user->nim }}</span>
                    @endif
                    @if ($user->nidn)
                        <span class="id-badge">NIDN: {{ $user->nidn }}</span>
                    @endif
                    <span class="status-badge status-{{ $user->status_akun }}">
                        <i class="dot"></i> {{ ucfirst($user->status_akun) }}
                    </span>
                </div>
                <p class="profile-subtext">
                    {{ $user->programStudi->nama ?? 'Program Studi Tidak Set' }} • {{ $user->email }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid profile-stats-grid">
        <div class="stat-card">
            <span>Total Upload</span>
            <strong>{{ $stats['total_uploads'] }}</strong>
            <small>Dokumen diupload</small>
        </div>
        <div class="stat-card">
            <span>Menunggu Verifikasi</span>
            <strong>{{ $stats['pending_uploads'] }}</strong>
            <small>Dalam antrean</small>
        </div>
        <div class="stat-card">
            <span>Terverifikasi</span>
            <strong>{{ $stats['approved_uploads'] }}</strong>
            <small>Tampil di repository</small>
        </div>
        @if (isset($stats['pending_bimbingan']))
            <div class="stat-card">
                <span>ACC Bimbingan</span>
                <strong>{{ $stats['pending_bimbingan'] }}</strong>
                <small>Menunggu ACC Anda</small>
            </div>
        @endif
    </div>

    {{-- Dual Form Section --}}
    <div class="profile-forms-grid">
        {{-- Form Edit Profil --}}
        <div class="auth-card profile-card">
            <h3><svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Informasi Profil</h3>
            <p class="card-desc">Perbarui nama lengkap, email, dan kontak WhatsApp Anda.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <label>Nama Lengkap
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </label>

                <label>Email
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </label>

                <label>Nomor WhatsApp
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="Contoh: 08123456789">
                </label>

                <label>Program Studi
                    <select name="program_studi_id">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id', $user->program_studi_id) == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <div class="form-readonly-row">
                    <label>Peran (Role)
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled class="input-disabled">
                    </label>
                    <label>Identitas (NIM / NIDN)
                        <input type="text" value="{{ $user->nim ?: ($user->nidn ?: '-') }}" disabled class="input-disabled">
                    </label>
                </div>

                <button type="submit" class="btn primary full margin-top">Simpan Perubahan</button>
            </form>
        </div>

        {{-- Form Ubah Password --}}
        <div class="auth-card profile-card">
            <h3><svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Ubah Password</h3>
            <p class="card-desc">Pastikan password Anda kuat untuk menjaga keamanan akun.</p>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PATCH')

                <label>Password Saat Ini
                    <input type="password" name="current_password" required placeholder="Masukkan password lama">
                </label>

                <label>Password Baru
                    <input type="password" name="password" required placeholder="Minimal 8 karakter">
                </label>

                <label>Konfirmasi Password Baru
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password baru">
                </label>

                <button type="submit" class="btn secondary full margin-top">Perbarui Password</button>
            </form>
        </div>
    </div>

    {{-- Dokumen Terbaru Saya --}}
    @if ($userDocuments->count() > 0)
        <div class="profile-recent-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Aktivitas Terbaru</p>
                    <h2>Dokumen Saya</h2>
                </div>
                @if ($user->role === 'mahasiswa')
                    <a href="{{ route('mahasiswa.dashboard') }}" class="text-link">Lihat semua di dashboard &rarr;</a>
                @elseif ($user->role === 'dosen')
                    <a href="{{ route('dosen.dashboard') }}" class="text-link">Lihat semua di dashboard &rarr;</a>
                @endif
            </div>

            <div class="document-history">
                @foreach ($userDocuments as $doc)
                    <div class="document-row">
                        <div>
                            <h2>{{ $doc->judul }}</h2>
                            <small>{{ strtoupper($doc->kategori) }} • {{ $doc->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="doc-status-col">
                            <span class="badge status-badge-{{ $doc->status }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                            <a href="{{ route('repository.show', $doc->id) }}" class="btn secondary small">Lihat</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
