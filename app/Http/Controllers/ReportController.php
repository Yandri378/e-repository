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

        $dbYears = RepositoryDocument::select('tahun')
            ->distinct()
            ->whereNotNull('tahun')
            ->pluck('tahun')
            ->toArray();

        $defaultYears = range((int) date('Y'), (int) date('Y') - 5);
        $tahunList = collect(array_values(array_unique(array_merge($dbYears, $defaultYears))))
            ->map(fn ($y) => (int) $y)
            ->sortDesc()
            ->values();

        $kategoriTahunCounts = RepositoryDocument::selectRaw('kategori, tahun, COUNT(*) as total')
            ->whereNotNull('tahun')
            ->groupBy('kategori', 'tahun')
            ->orderByDesc('tahun')
            ->get()
            ->groupBy('kategori')
            ->map(fn ($items) => $items->pluck('total', 'tahun'));

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
            'tahunList',
            'kategoriTahunCounts'
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

        $scope          = $request->query('scope', 'filtered');
        $kategoriFilter = $request->kategori ?: $request->query('kategori');
        $tahunFilter    = $request->query('tahun');
        $statusFilter   = $request->query('status');
        $searchQuery    = $request->query('search');

        // Jika scope = all, hapus batasan filter
        if ($scope === 'all') {
            $kategoriFilter = null;
            $tahunFilter    = null;
            $statusFilter   = null;
            $searchQuery    = null;
        }

        if ($tahunFilter === 'all' || $tahunFilter === '') {
            $tahunFilter = null;
        }

        if ($kategoriFilter === 'all' || $kategoriFilter === '') {
            $kategoriFilter = null;
        }

        if ($statusFilter === 'all' || $statusFilter === '') {
            $statusFilter = null;
        }

        $documents = RepositoryDocument::with(['programStudi', 'owner', 'dosenPembimbing'])
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
            })
            ->latest()
            ->get();

        $kategoriDisplayNames = [
            'skripsi'    => 'Skripsi',
            'magang'     => 'Magang',
            'pkm'        => 'PKM',
            'penelitian' => 'Penelitian Dosen',
        ];

        $filterLabels = [];
        if ($scope === 'all') {
            $filterLabels[] = 'Semua Data Dokumen (Seluruh Tahun)';
        } else {
            if ($kategoriFilter) {
                $filterLabels[] = 'Kategori: ' . ($kategoriDisplayNames[$kategoriFilter] ?? ucfirst($kategoriFilter));
            }
            if ($tahunFilter) {
                $filterLabels[] = 'Tahun: ' . $tahunFilter;
            } else {
                $filterLabels[] = 'Semua Tahun';
            }
            if ($statusFilter) {
                $filterLabels[] = 'Status: ' . ucfirst($statusFilter);
            }
            if ($searchQuery) {
                $filterLabels[] = 'Pencarian: "' . $searchQuery . '"';
            }
            if (empty($filterLabels)) {
                $filterLabels[] = 'Semua Data Dokumen';
            }
        }
        $filterText = implode(' | ', $filterLabels);

        $nameParts = ['laporan'];
        if ($kategoriFilter) {
            $nameParts[] = $kategoriFilter === 'penelitian' ? 'penelitian-dosen' : $kategoriFilter;
        } else {
            $nameParts[] = 'repository';
        }

        if ($tahunFilter) {
            $nameParts[] = 'tahun-' . $tahunFilter;
        } elseif ($scope === 'all') {
            $nameParts[] = 'semua-tahun';
        }

        $filenamePrefix = implode('-', $nameParts);
        $timestamp = date('Ymd_His');

        $kategoriLabel = $kategoriFilter ? ($kategoriDisplayNames[$kategoriFilter] ?? strtoupper($kategoriFilter)) : null;

        if ($format === 'excel') {
            $html = view('reports.export-table', compact('documents', 'filterText', 'kategoriLabel'))->render();

            return response($html, 200, [
                'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filenamePrefix . '-' . $timestamp . '.xls"',
            ]);
        }

        return response($this->generatePdf($documents, $filterText, $kategoriLabel), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filenamePrefix . '-' . $timestamp . '.pdf"',
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

    private function generatePdf($documents, string $filterText = '', ?string $kategoriLabel = null): string
    {
        $pdf = new class($filterText, $kategoriLabel) extends \FPDF {
            private string $filterText;
            private ?string $kategoriLabel;

            public function __construct(string $filterText, ?string $kategoriLabel = null)
            {
                parent::__construct('L', 'mm', 'A4');
                $this->filterText = $filterText;
                $this->kategoriLabel = $kategoriLabel;
                $this->AliasNbPages();
                $this->SetMargins(14, 14, 14);
                $this->SetAutoPageBreak(true, 18);
            }

            public function Header(): void
            {
                $logoPath = public_path('assets/metamedia.png');
                if (file_exists($logoPath)) {
                    $this->Image($logoPath, 14, 10, 16);
                    $this->SetXY(33, 10);
                } else {
                    $this->SetXY(14, 10);
                }

                $this->SetFont('Arial', 'B', 13);
                $this->SetTextColor(30, 58, 138);
                $this->Cell(0, 5, 'UNIVERSITAS METAMEDIA', 0, 1, 'L');

                if (file_exists($logoPath)) {
                    $this->SetX(33);
                } else {
                    $this->SetX(14);
                }
                $this->SetFont('Arial', 'B', 10);
                $this->SetTextColor(51, 65, 85);
                $titleText = 'LAPORAN DATA DOKUMEN ' . ($this->kategoriLabel ? strtoupper($this->kategoriLabel) : 'REPOSITORY');
                $this->Cell(0, 4.5, $this->encode($titleText), 0, 1, 'L');

                if (file_exists($logoPath)) {
                    $this->SetX(33);
                } else {
                    $this->SetX(14);
                }
                $this->SetFont('Arial', '', 7.5);
                $this->SetTextColor(100, 116, 139);
                $subText = 'Dicetak: ' . date('d/m/Y H:i') . ' WIB';
                if ($this->filterText !== '') {
                    $subText .= ' | ' . $this->filterText;
                }
                $this->Cell(0, 4, $this->encode($subText), 0, 1, 'L');

                $this->SetLineWidth(0.3);
                $this->SetDrawColor(203, 213, 225);
                $this->Line(14, 27, 283, 27);
                $this->Ln(6);

                // Table Headers
                $this->SetFont('Arial', 'B', 8);
                $this->SetFillColor(238, 242, 255);
                $this->SetTextColor(30, 58, 138);
                $this->SetDrawColor(199, 210, 254);

                $this->Cell(10, 7, 'No', 1, 0, 'C', true);
                $this->Cell(75, 7, 'Judul Dokumen', 1, 0, 'L', true);
                $this->Cell(42, 7, 'Penulis', 1, 0, 'L', true);
                $this->Cell(26, 7, 'NIM / NIDN', 1, 0, 'C', true);
                $this->Cell(45, 7, 'Program Studi', 1, 0, 'L', true);
                $this->Cell(26, 7, 'Kategori', 1, 0, 'C', true);
                $this->Cell(17, 7, 'Tahun', 1, 0, 'C', true);
                $this->Cell(28, 7, 'Status', 1, 1, 'C', true);

                $this->SetTextColor(30, 41, 59);
                $this->SetFont('Arial', '', 7.5);
                $this->SetDrawColor(226, 232, 240);
            }

            public function Footer(): void
            {
                $this->SetY(-14);
                $this->SetFont('Arial', 'I', 7.5);
                $this->SetTextColor(148, 163, 184);
                $this->Cell(0, 8, $this->encode('Halaman ' . $this->PageNo() . ' dari {nb} | E-Repository Universitas Metamedia'), 0, 0, 'C');
            }

            public function encode(string $text): string
            {
                return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
            }

            public function fitText(string $text, float $maxWidth): string
            {
                $text = trim($text);
                $encoded = $this->encode($text);
                if ($this->GetStringWidth($encoded) <= $maxWidth) {
                    return $encoded;
                }

                while (mb_strlen($text) > 3 && $this->GetStringWidth($this->encode($text . '...')) > $maxWidth) {
                    $text = mb_substr($text, 0, -1);
                }

                return $this->encode($text . '...');
            }
        };

        $pdf->AddPage();

        $fill = false;
        $no = 1;

        foreach ($documents as $doc) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);

            $judul  = $pdf->fitText($doc->judul ?? '-', 73);
            $penulis = $pdf->fitText($doc->nama ?? '-', 40);
            $nim     = $pdf->fitText($doc->nim ?: ($doc->nidn ?: '-'), 24);
            $prodi   = $pdf->fitText($doc->programStudi?->nama ?? '-', 43);
            $kategori = $pdf->fitText(strtoupper($doc->kategori ?? '-'), 24);
            $tahun   = (string) ($doc->tahun ?? '-');
            $status  = strtoupper($doc->status ?? '-');

            $pdf->Cell(10, 6.5, (string) $no++, 1, 0, 'C', true);
            $pdf->Cell(75, 6.5, $judul, 1, 0, 'L', true);
            $pdf->Cell(42, 6.5, $penulis, 1, 0, 'L', true);
            $pdf->Cell(26, 6.5, $nim, 1, 0, 'C', true);
            $pdf->Cell(45, 6.5, $prodi, 1, 0, 'L', true);
            $pdf->Cell(26, 6.5, $kategori, 1, 0, 'C', true);
            $pdf->Cell(17, 6.5, $tahun, 1, 0, 'C', true);

            // Status color highlight
            if ($doc->status === 'terverifikasi') {
                $pdf->SetTextColor(5, 150, 105);
            } elseif ($doc->status === 'ditolak') {
                $pdf->SetTextColor(225, 29, 72);
            } else {
                $pdf->SetTextColor(217, 119, 6);
            }
            $pdf->Cell(28, 6.5, $pdf->encode($status), 1, 1, 'C', true);
            $pdf->SetTextColor(30, 41, 59);

            $fill = ! $fill;
        }

        if ($documents->isEmpty()) {
            $pdf->Cell(269, 12, $pdf->encode('Tidak ada data dokumen repository ditemukan.'), 1, 1, 'C', true);
        }

        return $pdf->Output('S');
    }
}
