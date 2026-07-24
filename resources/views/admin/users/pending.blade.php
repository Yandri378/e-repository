@extends('layouts.admin')

@section('title', 'Verifikasi Akun')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Admin</p>
    <h1>Verifikasi Akun Mahasiswa & Dosen</h1>
</section>

<section class="section list-stack">
    @forelse ($users as $user)
        <article class="document-row">
            <div>
                <span class="badge">{{ strtoupper($user->role) }}</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }} | {{ $user->nim ?: $user->nidn }} | WhatsApp: {{ $user->whatsapp ?: '-' }}</p>
                <p>{{ $user->programStudi?->nama ?: 'Program studi belum tersedia' }}</p>
            </div>
            <div class="inline-actions">
                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="aktif">
                    <button class="btn primary" type="submit">Terima</button>
                </form>
                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="ditolak">
                    <input type="hidden" name="alasan_penolakan" value="Data registrasi belum sesuai.">
                    <button class="btn secondary" type="submit">Tolak</button>
                </form>
            </div>
        </article>
    @empty
        <div class="empty-state">Tidak ada akun yang menunggu verifikasi.</div>
    @endforelse

    {{ $users->links() }}
</section>
@endsection
