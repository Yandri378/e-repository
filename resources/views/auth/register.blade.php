@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <section class="auth-shell">
        <form method="POST" action="{{ route('register.store') }}" class="auth-card wide">
            @csrf
            <p class="eyebrow">Register Mahasiswa & Dosen</p>
            <h1>Akun Dibuat oleh Admin</h1>
            <p class="auth-note">
                Mahasiswa dan dosen wajib login memakai username dan password awal dari Admin.
                Silakan hubungi Admin untuk dibuatkan akun, lalu masuk menggunakan Email/NIM/NIDN dan password tersebut.
            </p>
            @if (!$role)
                <div class="role-picker">
                    <a href="{{ route('login') }}" class="btn primary full">Masuk ke Login Admin</a>
                </div>
            @else
                @if ($errors->any())
                    <div class="error-box">{{ $errors->first() }}</div>
                @endif
                <input type="hidden" name="role" value="{{ $role }}">
                <button type="submit" class="btn secondary full">Saya Mengerti</button>
                <a href="{{ route('login') }}" class="btn primary full">Masuk ke Login Admin</a>
            @endif
        </form>
    </section>
@endsection