@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Dashboard Mahasiswa</p>
    <h1>Upload PDF dan file project ZIP/RAR untuk ACC dosen.</h1>
</section>

@include('dashboards.partials.role-workspace', [
    'categories' => ['skripsi' => 'Skripsi/TA', 'magang' => 'Laporan Magang'],
])

@include('dashboards.partials.cards', [
    'cardKeys' => ['skripsi' => 'Skripsi/TA', 'magang' => 'Magang'],
])
@include('dashboards.partials.documents')
@endsection
