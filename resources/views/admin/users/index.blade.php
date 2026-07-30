@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Admin</p>
        <h1>Kelola User Mahasiswa & Dosen</h1>
    </section>

    <section class="section">
        <form method="POST" action="{{ route('admin.users.store') }}" class="auth-card wide">
            @csrf
            <p class="eyebrow">Buat Akun</p>
            <h2>Username dan Password dari Admin</h2>
            @if ($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif
            <div class="form-grid">
                <label>Role
                    <select name="role" required>
                        <option value="mahasiswa" class="text-gray-500" @selected(old('role') === 'mahasiswa')>Mahasiswa
                        </option>
                        <option value="dosen" class="text-gray-500" @selected(old('role') === 'dosen')>Dosen</option>
                    </select>
                </label>
                <label>Nama Lengkap
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>NIM Mahasiswa
                    <input type="text" class="nim-input" name="nim" value="{{ old('nim') }}"
                        placeholder="Isi jika role mahasiswa">
                </label>
                <label>NIDN Dosen
                    <input type="text" name="nidn" value="{{ old('nidn') }}" placeholder="Isi jika role dosen">
                </label>
                <label>Program Studi
                    <select name="program_studi_id" required>
                        <option value="">Pilih prodi</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" class="text-gray-500"
                                @selected(old('program_studi_id') == $prodi->id)>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>No. WhatsApp
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}">
                </label>
                <label>Password Awal
                    <input type="password" name="password" required>
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required>
                </label>
            </div>
            <button class="btn primary full" type="submit">Buat Akun Aktif</button>
        </form>
    </section>

    <section class="section list-stack">
        @forelse ($users as $user)
            <article class="document-row">
                <div>
                    <span class="badge">{{ strtoupper($user->status_akun) }}</span>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ ucfirst($user->role) }} | {{ $user->email }} | {{ $user->nim ?: $user->nidn }}</p>
                </div>
                <div class="inline-actions">
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="aktif">
                        <button class="btn primary" type="submit">Aktifkan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="nonaktif">
                        <button class="btn secondary" type="submit">Nonaktifkan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" type="submit"
                            style="background:#dc2626; color:#ffffff; border:none;">Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">Belum ada user mahasiswa/dosen.</div>
        @endforelse

        {{ $users->links() }}
    </section>
@endsection