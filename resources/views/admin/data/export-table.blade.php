<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Identitas</th>
            <th>Prodi</th>
            <th>Judul</th>
            <th>Tahun</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($documents as $d)
        <tr>
            <td>{{ $d->nama }}</td>
            <td>{{ $d->nim ?: $d->nidn ?: '-' }}</td>
            <td>{{ $d->programStudi?->nama ?: '-' }}</td>
            <td>{{ $d->judul }}</td>
            <td>{{ $d->tahun }}</td>
            <td>{{ strtoupper($d->kategori) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
