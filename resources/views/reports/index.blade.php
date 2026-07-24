@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Laporan & Statistik</p>
    <h1>Rekap per kategori, bulan, status, dan mode input.</h1>
    <div class="hero-actions">
        <a class="btn secondary" href="{{ route('reports.export', ['format' => 'excel'] + request()->query()) }}">Export Excel</a>
        <a class="btn secondary" href="{{ route('reports.export', ['format' => 'pdf'] + request()->query()) }}">Export PDF</a>
    </div>
</section>
<section class="section table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Status</th>
                <th>Mode</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $row)
                <tr>
                    <td>{{ ucfirst($row->kategori) }}</td>
                    <td>{{ $row->tahun }}</td>
                    <td>{{ $row->bulan }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->jenis_input }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data laporan.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
