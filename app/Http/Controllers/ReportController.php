<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request, ?string $kategori = null)
    {
        $kategoriFilter = $kategori ?: $request->query('kategori');
        $tahunFilter = $request->query('tahun');
        $statusFilter = $request->query('status');
        $searchQuery = $request->query('search');

        // Aggregate reports summary rows
        $reports = $this->reportRows($request, $kategoriFilter);

        // Detailed documents query for CRUD table & detailed inspection
        $query = RepositoryDocument::with(['programStudi', 'owner', 'dosenPembimbing'])
            ->when($kategoriFilter, fn ($q) => $q->where('kategori', $kategoriFilter))
            ->when($tahunFilter, fn ($q) => $q->where('tahun', $tahunFilter))
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->when($searchQuery, function ($q, $s) {
                $q->where(function ($sub) use ($s) {
                    $sub->where('judul', 'like', "%{$s}%")
                        ->orWhere('nama', 'like', "%{$s}%")
                        ->orWhere('nim', 'like', "%{$s}%")
                        ->orWhere('nidn', 'like', "%{$s}%");
                });
            });

        $documents = $query->latest()->paginate(12)->withQueryString();

        // Key statistical metrics
        $totalDokumen       = RepositoryDocument::count();
        $totalTerverifikasi = RepositoryDocument::where('status', 'terverifikasi')->count();
        $totalPending       = RepositoryDocument::where('status', 'pending')->count();
        $totalDitolak       = RepositoryDocument::where('status', 'ditolak')->count();

        // Kategori metrics
        $countSkripsi    = RepositoryDocument::where('kategori', 'skripsi')->count();
        $countMagang     = RepositoryDocument::where('kategori', 'magang')->count();
        $countPkm        = RepositoryDocument::where('kategori', 'pkm')->count();
        $countPenelitian = RepositoryDocument::where('kategori', 'penelitian')->count();

        $prodiList = ProgramStudi::where('aktif', true)->get();
        $tahunList = RepositoryDocument::select('tahun')
            ->distinct()
            ->whereNotNull('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('reports.index', compact(
            'reports',
            'documents',
            'kategori',
            'kategoriFilter',
            'tahunFilter',
            'statusFilter',
            'searchQuery',
            'totalDokumen',
            'totalTerverifikasi',
            'totalPending',
            'totalDitolak',
            'countSkripsi',
            'countMagang',
            'countPkm',
            'countPenelitian',
            'prodiList',
            'tahunList'
        ));
    }

    /**
     * CRUD: Store new document directly from report management page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'            => ['required', 'string', 'max:255'],
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['nullable', 'string', 'max:50'],
            'nidn'             => ['nullable', 'string', 'max:50'],
            'kategori'         => ['required', Rule::in(['skripsi', 'magang', 'pkm', 'penelitian'])],
            'tahun'            => ['required', 'integer', 'min:2000', 'max:2099'],
            'bulan'            => ['nullable', 'string', 'max:20'],
            'status'           => ['required', Rule::in(['pending', 'terverifikasi', 'ditolak'])],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'abstrak'          => ['nullable', 'string'],
        ]);

        $validated['jenis_input']    = 'manual';
        $validated['tanggal_upload'] = now();
        $validated['input_by']        = Auth::id();

        if ($validated['status'] === 'terverifikasi') {
            $validated['verified_by'] = Auth::id();
            $validated['verified_at'] = now();
        }

        RepositoryDocument::create($validated);

        return back()->with('status', 'Dokumen laporan baru "'.$validated['judul'].'" berhasil ditambahkan.');
    }

    /**
     * CRUD: Update document data from report management page.
     */
    public function update(Request $request, RepositoryDocument $document)
    {
        $validated = $request->validate([
            'judul'            => ['required', 'string', 'max:255'],
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['nullable', 'string', 'max:50'],
            'nidn'             => ['nullable', 'string', 'max:50'],
            'kategori'         => ['required', Rule::in(['skripsi', 'magang', 'pkm', 'penelitian'])],
            'tahun'            => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'           => ['required', Rule::in(['pending', 'terverifikasi', 'ditolak'])],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'abstrak'          => ['nullable', 'string'],
        ]);

        if ($validated['status'] === 'terverifikasi' && $document->status !== 'terverifikasi') {
            $validated['verified_by'] = Auth::id();
            $validated['verified_at'] = now();
        }

        $document->update($validated);

        return back()->with('status', 'Data dokumen "'.$document->judul.'" berhasil diperbarui.');
    }

    /**
     * CRUD: Delete document from report management page.
     */
    public function destroy(RepositoryDocument $document)
    {
        $title = $document->judul;

        foreach (['file_dokumen', 'file_project'] as $field) {
            $path = $document->{$field};
            if (! $path) {
                continue;
            }

            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $document->delete();

        return back()->with('status', 'Data dokumen "'.$title.'" berhasil dihapus dari laporan.');
    }

    public function export(Request $request, string $format)
    {
        abort_if(! in_array($format, ['excel', 'pdf'], true), 404);

        $reports = $this->reportRows($request, $request->kategori);

        if ($format === 'excel') {
            $html = view('reports.export-table', compact('reports'))->render();

            return response($html, 200, [
                'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-repository.xls"',
            ]);
        }

        return response($this->simplePdf($reports), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-repository.pdf"',
        ]);
    }

    private function reportRows(Request $request, ?string $kategori = null)
    {
        return RepositoryDocument::query()
            ->selectRaw('kategori, tahun, bulan, status, jenis_input, COUNT(*) as total')
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->when($request->tahun, fn ($query, $tahun) => $query->where('tahun', $tahun))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
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
                $row->bulan ?: '-',
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
