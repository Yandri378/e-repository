<?php

namespace App\Http\Controllers;

use App\Models\RepositoryDocument;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, ?string $kategori = null)
    {
        $reports = $this->reportRows($request, $kategori);

        return view('reports.index', compact('reports', 'kategori'));
    }

    public function export(Request $request, string $format)
    {
        abort_if(! in_array($format, ['excel', 'pdf'], true), 404);

        $reports = $this->reportRows($request, $request->kategori);

        if ($format === 'excel') {
            $html = view('reports.export-table', compact('reports'))->render();

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-repository.xls"',
            ]);
        }

        return response($this->simplePdf($reports), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-repository.pdf"',
        ]);
    }

    private function reportRows(Request $request, ?string $kategori = null)
    {
        return RepositoryDocument::query()
            ->selectRaw('kategori, tahun, bulan, status, jenis_input, COUNT(*) as total')
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->when($request->tahun, fn ($query, $tahun) => $query->where('tahun', $tahun))
            ->groupBy('kategori', 'tahun', 'bulan', 'status', 'jenis_input')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();
    }

    private function simplePdf($reports): string
    {
        $lines = ['Laporan Repository Kampus', ''];
        $lines[] = 'Kategori | Tahun | Bulan | Status | Mode | Total';
        foreach ($reports as $row) {
            $lines[] = implode(' | ', [
                strtoupper($row->kategori),
                $row->tahun,
                $row->bulan,
                $row->status,
                $row->jenis_input,
                $row->total,
            ]);
        }

        $content = collect($lines)
            ->map(fn ($line) => '('.str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $line).') Tj T*')
            ->implode("\n");
        $stream = "BT /F1 10 Tf 40 800 Td 14 TL\n$content\nET";
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj\n",
            "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n",
            "5 0 obj << /Length ".strlen($stream)." >> stream\n$stream\nendstream endobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf."trailer << /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
    }
}
