@extends('layouts.app')

@section('title', 'Sesi Belum Dibuka')

@section('content')
<section class="auth-shell">
    <div class="auth-card">
        <p class="eyebrow">Sesi Kompre</p>
        <h1>Sesi belum dibuka</h1>
        <p class="auth-intro">Form upload {{ strtoupper($kategori) }} untuk {{ ucfirst($actor) }} belum diaktifkan admin.</p>
        <a class="btn primary full" href="{{ route('home') }}">Kembali ke Beranda</a>
    </div>
</section>
@endsection
