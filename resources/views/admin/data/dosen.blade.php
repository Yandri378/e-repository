@extends('layouts.admin')

@section('title', 'Data Dosen')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Data Dosen</p>
    <h1>{{ $kategori ? 'Data '.strtoupper($kategori) : 'Data PKM dan Penelitian' }}</h1>
    <div class="hero-actions">
        <a class="btn secondary" href="{{ route('admin.data.dosen') }}">Semua</a>
        <a class="btn secondary" href="{{ route('admin.data.dosen', 'pkm') }}">PKM</a>
        <a class="btn secondary" href="{{ route('admin.data.dosen', 'penelitian') }}">Penelitian</a>
    </div>
    <form class="search-bar" method="GET">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIDN, judul, tahun...">
        <button type="submit">Cari</button>
        <div style="margin-left:0.8rem">
            @php
                $k = request('kategori') ?? $kategori ?? '';
                $base = '/admin/data-dosen/'.($k ? $k.'/' : '').'export/';
            @endphp
            <a class="btn secondary" href="{{ $base }}excel?search={{ urlencode(request('search') ?? '') }}">Export Excel</a>
            <a class="btn secondary" href="{{ $base }}pdf?search={{ urlencode(request('search') ?? '') }}">Export PDF</a>
        </div>
    </form>
</section>

<section class="section table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>NIDN</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>Tahun</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $document)
                <tr>
                    <td>{{ $document->nama }}</td>
                    <td>{{ strtoupper($document->kategori) }}</td>
                    <td>{{ $document->judul }}</td>
                    <td>{{ $document->nidn ?: '-' }}</td>
                    <td>{{ $document->email ?: '-' }}</td>
                    <td>{{ $document->programStudi?->nama ?: '-' }}</td>
                    <td>{{ $document->tahun }}</td>
                    <td>{{ $document->detail ?: $document->abstrak ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada data dosen.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $documents->links() }}
</section>
@endsection
