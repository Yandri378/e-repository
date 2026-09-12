<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }
        .header-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 4px;
        }
        .header-meta {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 12px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        th {
            background-color: #1e40af;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #93c5fd;
            text-align: center;
            vertical-align: middle;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge-terverifikasi {
            color: #047857;
            font-weight: bold;
        }
        .badge-pending {
            color: #b45309;
            font-weight: bold;
        }
        .badge-ditolak {
            color: #b91c1c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header-title">UNIVERSITAS METAMEDIA</div>
    <div class="header-subtitle">LAPORAN DATA DOKUMEN REPOSITORY</div>
    <div class="header-meta">
        Tanggal Ekspor: {{ date('d F Y, H:i') }} WIB
        @if(!empty($filterText)) | Filter: {{ $filterText }} @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 320px;">Judul</th>
                <th style="width: 180px;">Penulis</th>
                <th style="width: 120px;">NIM / NIDN</th>
                <th style="width: 120px;">Kategori</th>
                <th style="width: 70px;">Tahun</th>
                <th style="width: 110px;">Status</th>
                <th style="width: 200px;">Program Studi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $index => $doc)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $doc->judul }}</td>
                    <td>{{ $doc->nama }}</td>
                    <td class="text-center" style="mso-number-format:'\@';">{{ $doc->nim ?: $doc->nidn ?: '-' }}</td>
                    <td class="text-center font-bold">{{ strtoupper($doc->kategori) }}</td>
                    <td class="text-center">{{ $doc->tahun }}</td>
                    <td class="text-center">
                        <span class="badge-{{ $doc->status }}">
                            {{ strtoupper($doc->status) }}
                        </span>
                    </td>
                    <td>{{ $doc->programStudi?->nama ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 16px; color: #94a3b8;">
                        Tidak ada data dokumen repository yang sesuai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

