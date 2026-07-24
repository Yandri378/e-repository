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
        @foreach ($reports as $row)
            <tr>
                <td>{{ ucfirst($row->kategori) }}</td>
                <td>{{ $row->tahun }}</td>
                <td>{{ $row->bulan }}</td>
                <td>{{ $row->status }}</td>
                <td>{{ $row->jenis_input }}</td>
                <td>{{ $row->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
