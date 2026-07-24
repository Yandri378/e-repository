@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<section class="page-hero compact">
    <p class="eyebrow">Data Mahasiswa</p>
    <h1>List skripsi dan magang mahasiswa.</h1>
    <form class="search-bar" method="GET">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, judul, tahun...">
        <select name="kategori">
            <option value="">Semua</option>
            <option value="skripsi" {{ request('kategori') === 'skripsi' ? 'selected' : '' }}>Skripsi</option>
            <option value="magang" {{ request('kategori') === 'magang' ? 'selected' : '' }}>Magang</option>
        </select>
        <button type="submit">Cari</button>
        <div style="margin-left:0.8rem">
            <a class="btn secondary" href="{{ route('admin.data.mahasiswa.export', 'excel') }}?search={{ urlencode(request('search') ?? '') }}&kategori={{ urlencode(request('kategori') ?? '') }}">Export Excel</a>
            <a class="btn secondary" href="{{ route('admin.data.mahasiswa.export', 'pdf') }}?search={{ urlencode(request('search') ?? '') }}&kategori={{ urlencode(request('kategori') ?? '') }}">Export PDF</a>
        </div>
    </form>
</section>

<section class="section table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Judul</th>
                <th>NIM</th>
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
                    <td>{{ $document->judul }}</td>
                    <td>{{ $document->nim ?: '-' }}</td>
                    <td>{{ $document->email ?: '-' }}</td>
                    <td>{{ $document->programStudi?->nama ?: '-' }}</td>
                    <td>{{ $document->tahun }}</td>
                    <td>{{ $document->detail ?: $document->abstrak ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada data mahasiswa.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $documents->links() }}
</section>
@endsection
