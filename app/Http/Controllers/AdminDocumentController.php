<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\RepositoryDocumentImport;
use App\Models\JenisDokumen;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminDocumentController extends Controller
{
    public function pending()
    {
        $documents = $this->verifiablePendingDocumentsQuery()
            ->with(['owner', 'programStudi', 'jenisDokumen', 'dosenPembimbing'])
            ->latest()
            ->paginate(10);

        return view('admin.documents.pending', compact('documents'));
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen', 'dosenPembimbing']);

        if ($status === 'terverifikasi') {
            $query->where('status', 'terverifikasi');
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } elseif ($status === 'all') {
            // Tampilkan semua data tanpa filter status
        } else {
            // Default: hanya tampilkan dokumen yang belum diverifikasi (pending)
            $status = 'pending';
            $query->where('status', 'pending');
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        $bulkPendingCount = RepositoryDocument::where('status', 'pending')->count();
        $pendingCount = $bulkPendingCount;
        $verifiedCount = RepositoryDocument::where('status', 'terverifikasi')->count();
        $rejectedCount = RepositoryDocument::where('status', 'ditolak')->count();
        $allCount = RepositoryDocument::count();

        return view('admin.documents.index', compact(
            'documents',
            'bulkPendingCount',
            'status',
            'pendingCount',
            'verifiedCount',
            'rejectedCount',
            'allCount'
        ));
    }

    public function create(Request $request)
    {
        $kategori = $request->query('kategori', 'skripsi');
        if (!in_array($kategori, ['skripsi', 'penelitian', 'pkm'], true)) {
            $kategori = 'skripsi';
        }

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();
        $jenisDokumen = JenisDokumen::where('aktif', true)->orderBy('nama')->get();

        return view('admin.documents.create', compact('kategori', 'programStudi', 'jenisDokumen'));
    }

    public function store(Request $request)
    {
        $kategori = $request->input('kategori', 'skripsi');
        if (!in_array($kategori, ['skripsi', 'penelitian', 'pkm'], true)) {
            $kategori = 'skripsi';
        }

        $rules = [
            'kategori' => ['required', Rule::in(['skripsi', 'penelitian', 'pkm'])],
            'program_studi_id' => ['required', 'exists:program_studi,id'],
            'jenis_dokumen_id' => ['nullable', 'exists:jenis_dokumen,id'],
            'nama' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'digits:4'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'judul' => ['required', 'string', 'max:255'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'abstrak' => ['nullable', 'string'],
            'detail' => ['nullable', 'string'],
            'status_penelitian' => ['nullable', 'in:berjalan,selesai'],
            'file_dokumen' => ['required', 'file', 'mimetypes:application/pdf,application/x-pdf,text/pdf,application/octet-stream', 'extensions:pdf', 'max:10240'],
            'file_project' => ['nullable', 'file', 'mimes:zip,rar', 'extensions:zip,rar', 'max:819200'],
        ];

        if ($kategori === 'skripsi') {
            $rules['nim'] = ['required', 'string', 'max:30'];
        } else {
            $rules['nidn'] = ['required', 'string', 'max:30'];
        }

        $messages = [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM mahasiswa wajib diisi.',
            'nidn.required' => 'NIDN dosen wajib diisi.',
            'program_studi_id.required' => 'Program studi wajib dipilih.',
            'program_studi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'tahun.required' => 'Tahun penerbitan/laporan wajib diisi.',
            'tahun.digits' => 'Tahun harus berupa 4 digit angka (contoh: 2024).',
            'judul.required' => 'Judul dokumen/penelitian wajib diisi.',
            'file_dokumen.required' => 'File dokumen PDF wajib diunggah.',
            'file_dokumen.mimes' => 'File dokumen harus berformat PDF (.pdf).',
            'file_dokumen.max' => 'Ukuran file dokumen PDF melebihi batas maksimum 10 MB.',
            'file_project.mimes' => 'File project harus berformat ZIP (.zip) atau RAR (.rar).',
            'file_project.extensions' => 'File project harus berekstensi .zip atau .rar.',
            'file_project.max' => 'Ukuran file project ZIP/RAR melebihi batas maksimum 800 MB.',
        ];

        $data = $request->validate($rules, $messages);

        try {
            $uploadedPdf = $request->file('file_dokumen');
            $storedPdfPath = $this->storeUploadedFile($uploadedPdf, 'repository-documents');

            $storedProjectPath = null;
            if ($request->hasFile('file_project')) {
                try {
                    $storedProjectPath = $this->storeUploadedFile($request->file('file_project'), 'repository-projects');
                } catch (\Throwable $projectStorageException) {
                    Log::warning('Gagal menyimpan file project upload manual admin, dokumen tetap disimpan tanpa file project.', [
                        'exception' => $projectStorageException,
                    ]);
                }
            }

            $pdfPageCount = null;
            try {
                $tempPath = $uploadedPdf->getPathname();
                if ($tempPath && is_file($tempPath)) {
                    $pdfPageCount = $this->countPdfPages($tempPath);
                }
            } catch (\Throwable $pageCountException) {
                Log::warning('Gagal menghitung halaman PDF saat upload manual admin.', ['exception' => $pageCountException]);
            }

            $document = RepositoryDocument::create([
                'user_id' => Auth::id(),
                'input_by' => Auth::id(),
                'program_studi_id' => $data['program_studi_id'],
                'jenis_dokumen_id' => $data['jenis_dokumen_id'] ?? null,
                'kategori' => $kategori,
                'jenis_input' => 'upload',
                'nim' => $data['nim'] ?? null,
                'nidn' => $data['nidn'] ?? null,
                'nama' => $data['nama'],
                'tahun' => $data['tahun'],
                'bulan' => $data['bulan'] ?? now()->month,
                'judul' => $data['judul'],
                'jumlah_halaman' => $data['jumlah_halaman'] ?? null,
                'abstrak' => $data['abstrak'] ?? null,
                'detail' => $data['detail'] ?? null,
                'status_penelitian' => $data['status_penelitian'] ?? null,
                'file_dokumen' => $storedPdfPath,
                'file_project' => $storedProjectPath,
                'pdf_page_count' => $pdfPageCount,
                'status' => 'terverifikasi',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'tanggal_upload' => now(),
                'submission_token' => Str::random(48),
            ]);

            return redirect()
                ->route('admin.documents.index', ['status' => 'terverifikasi'])
                ->with('status', 'Data ' . strtoupper($kategori) . ' "' . $document->judul . '" berhasil diupload secara manual.');
        } catch (\Throwable $e) {
            Log::error('Upload manual admin gagal: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Upload dokumen gagal: ' . $e->getMessage());
        }
    }

    private function storeUploadedFile(UploadedFile $uploadedFile, string $directory): string
    {
        $lastException = null;

        foreach (['local', 'public'] as $disk) {
            try {
                $path = $uploadedFile->store($directory, $disk);

                if ($path) {
                    return $path;
                }
            } catch (\Throwable $exception) {
                $lastException = $exception;
                Log::warning('Gagal menyimpan file upload ke disk.', [
                    'disk' => $disk,
                    'directory' => $directory,
                    'filename' => $uploadedFile->getClientOriginalName(),
                    'exception' => $exception,
                ]);
            }
        }

        throw new \RuntimeException('Gagal menyimpan berkas ke penyimpanan server. Silakan coba lagi atau hubungi admin.', 0, $lastException);
    }

    private function countPdfPages(string $filePath): ?int
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            return null;
        }

        $chunk = fread($handle, 204800);
        fclose($handle);

        if ($chunk === false) {
            return null;
        }

        preg_match_all('/\/Type\s*\/Page[^s]/', $chunk, $matches);

        return count($matches[0]) ?: null;
    }

    public function mahasiswa(Request $request)
    {
        $kategori = $request->kategori;

        $query = $this->searchDocuments($request);
        if ($kategori && in_array($kategori, ['skripsi', 'magang'], true)) {
            $query->where('kategori', $kategori);
        } else {
            $query->whereIn('kategori', ['skripsi', 'magang']);
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        return view('admin.data.mahasiswa', compact('documents'));
    }

    public function dosen(Request $request, ?string $kategori = null)
    {
        abort_if($kategori && !in_array($kategori, ['pkm', 'penelitian'], true), 404);

        $documents = $this->searchDocuments($request)
            ->whereIn('kategori', $kategori ? [$kategori] : ['pkm', 'penelitian'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.data.dosen', compact('documents', 'kategori'));
    }

    public function exportMahasiswa(Request $request, string $format)
    {
        abort_if(!in_array($format, ['excel', 'pdf'], true), 404);

        $documents = $this->searchDocuments($request)
            ->whereIn('kategori', ['skripsi', 'magang'])
            ->latest()
            ->get();

        $html = view('admin.data.export-table', compact('documents'))->render();

        if ($format === 'excel') {
            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="data-mahasiswa.xls"',
            ]);
        }

        // Simple PDF like ReportController
        $pdf = $this->simplePdfFromDocuments($documents, 'Data Mahasiswa');
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="data-mahasiswa.pdf"',
        ]);
    }

    public function exportDosen(Request $request, ?string $kategori = null, string $format)
    {
        abort_if(!in_array($format, ['excel', 'pdf'], true), 404);

        $documents = $this->searchDocuments($request)
            ->when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->latest()
            ->get();

        $html = view('admin.data.export-table', compact('documents'))->render();

        if ($format === 'excel') {
            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="data-dosen.xls"',
            ]);
        }

        $pdf = $this->simplePdfFromDocuments($documents, 'Data Dosen');
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="data-dosen.pdf"',
        ]);
    }

    private function simplePdfFromDocuments($documents, $title)
    {
        $lines = [$title, ''];
        $lines[] = 'Nama | Identitas | Prodi | Judul | Tahun | Kategori';
        foreach ($documents as $d) {
            $lines[] = implode(' | ', [
                $d->nama,
                $d->nim ?: $d->nidn ?: '-',
                $d->programStudi?->nama ?: '-',
                $d->judul,
                $d->tahun,
                strtoupper($d->kategori),
            ]);
        }

        $content = collect($lines)
            ->map(fn($line) => '(' . str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line) . ') Tj T*')
            ->implode("\n");
        $stream = "BT /F1 10 Tf 40 800 Td 14 TL\n$content\nET";
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj\n",
            "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n",
            "5 0 obj << /Length " . strlen($stream) . " >> stream\n$stream\nendstream endobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf . "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
    }

    public function updateStatus(Request $request, RepositoryDocument $document)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['terverifikasi', 'ditolak'])],
            'catatan_verifikasi' => ['nullable', 'string', 'max:500'],
        ]);

        if (
            $data['status'] === 'terverifikasi'
            && in_array($document->kategori, ['skripsi', 'magang'], true)
            && $document->dosen_pembimbing_id
            && !$document->dosen_approved_at
        ) {
            return back()->withErrors([
                'status' => 'Dokumen mahasiswa harus di-ACC dosen pembimbing terlebih dahulu.',
            ]);
        }

        $document->update([
            'status' => $data['status'],
            'verified_by' => Auth::id(),
            'verified_at' => $data['status'] === 'terverifikasi' ? now() : null,
            'catatan_verifikasi' => $data['catatan_verifikasi'] ?? null,
        ]);

        return back()->with('status', 'Status dokumen "' . $document->judul . '" berhasil diperbarui.');
    }

    public function verifyAll()
    {
        $count = RepositoryDocument::where('status', 'pending')->update([
            'status' => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => null,
        ]);

        return back()->with('status', $count . ' upload berhasil diverifikasi semuanya.');
    }

    private function verifiablePendingDocumentsQuery()
    {
        return RepositoryDocument::where('status', 'pending')
            ->where(function ($query) {
                $query->whereNotIn('kategori', ['skripsi', 'magang'])
                    ->orWhereNull('dosen_pembimbing_id')
                    ->orWhereNotNull('dosen_approved_at');
            });
    }

    public function download(RepositoryDocument $document)
    {
        abort_if(!$document->file_dokumen, 404, 'File dokumen belum tersedia.');
        // Prefer private storage; fallback to public
        if (Storage::disk('local')->exists($document->file_dokumen)) {
            return Storage::disk('local')->download($document->file_dokumen);
        }

        abort_if(!Storage::disk('public')->exists($document->file_dokumen), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('public')->download($document->file_dokumen);
    }

    public function destroy(RepositoryDocument $document)
    {
        $title = $document->judul;

        foreach (['file_dokumen', 'file_project'] as $field) {
            $path = $document->{$field};

            if (!$path) {
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

        return back()->with('status', 'Data dokumen "' . $title . '" berhasil dihapus.');
    }

    public function downloadRequests(Request $request)
    {
        $requests = \App\Models\RepositoryDownloadRequest::with('document')
            ->latest()
            ->paginate(15);

        return view('admin.downloads.index', compact('requests'));
    }

    public function approveDownloadRequest(Request $request, \App\Models\RepositoryDownloadRequest $downloadRequest)
    {
        $downloadRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Prepare WA link to notify requester
        $phone = preg_replace('/[^0-9]/', '', $downloadRequest->requester_phone ?? '');
        $approvalMsg = implode("\n", [
            'Halo, permintaan unduh dokumen Anda telah disetujui oleh pustaka.',
            '',
            'Dokumen: ' . ($downloadRequest->document->judul ?? '-'),
            'Anda dapat mengunduh melalui link ini (masukkan submission token jika diperlukan):',
            route('repository.download', $downloadRequest->document) . '?submission_token=' . $downloadRequest->submission_token,
            '',
            'Terima kasih.'
        ]);

        $waUrl = $phone ? ('https://wa.me/' . $phone . '?text=' . rawurlencode($approvalMsg)) : null;

        return back()->with('status', 'Permintaan unduh telah disetujui.')->with('whatsapp_notification_url', $waUrl);
    }

    private function searchDocuments(Request $request)
    {
        return RepositoryDocument::with(['programStudi', 'jenisDokumen'])
            ->when($request->search, function ($query, string $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('nidn', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tahun', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Admin toggles bebas pustaka prerequisite fields on a specific document.
     * Fields: hard_copy_submitted, pdf_kelengkapan_confirmed, has_active_loans
     */
    public function updateBebasPustakaStatus(Request $request, RepositoryDocument $document)
    {
        $data = $request->validate([
            'field' => ['required', 'in:hard_copy_submitted,pdf_kelengkapan_confirmed,has_active_loans'],
            'value' => ['required', 'boolean'],
        ]);

        $document->update([$data['field'] => $data['value']]);

        $labels = [
            'hard_copy_submitted' => 'Status hard copy',
            'pdf_kelengkapan_confirmed' => 'Konfirmasi kelengkapan PDF',
            'has_active_loans' => 'Status pinjaman buku',
        ];

        $statusText = $data['value'] ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('status', ($labels[$data['field']] ?? $data['field']) . ' berhasil ' . $statusText . ' untuk dokumen "' . $document->judul . '".');
    }

    /**
     * Admin explicitly approves the download of Kartu Bebas Pustaka for a specific document.
     * All other prerequisites must already be met before this can be granted.
     */
    public function approveBebasPustaka(RepositoryDocument $document)
    {
        // Ensure all other checklist items are done (except the approval itself)
        $requiredMet = !$document->has_active_loans
            && $document->dosen_approved_at
            && $document->pdf_kelengkapan_deklarasi
            && $document->pdf_kelengkapan_confirmed
            && $document->hard_copy_submitted;

        if (!$requiredMet) {
            return back()->withErrors([
                'bebas_pustaka' => 'Tidak dapat memberikan izin: masih ada syarat bebas pustaka yang belum terpenuhi. Pastikan: tidak ada pinjaman buku, sudah ACC dosen, PDF sudah dikonfirmasi lengkap, dan hard copy sudah diserahkan.',
            ]);
        }

        $document->update([
            'bebas_pustaka_diizinkan' => true,
            'bebas_pustaka_diizinkan_by' => Auth::id(),
            'bebas_pustaka_diizinkan_at' => now(),
        ]);

        return back()->with('status', 'Izin download Kartu Bebas Pustaka berhasil diberikan untuk "' . $document->judul . '".');
    }

    /**
     * Admin revokes the bebas pustaka download permission.
     */
    public function revokeBebasPustaka(RepositoryDocument $document)
    {
        $document->update([
            'bebas_pustaka_diizinkan' => false,
            'bebas_pustaka_diizinkan_by' => null,
            'bebas_pustaka_diizinkan_at' => null,
        ]);

        return back()->with('status', 'Izin download Kartu Bebas Pustaka berhasil dicabut untuk "' . $document->judul . '".');
    }

    /* ─────────────────────────────────────────
     |  IMPORT EXCEL
     ───────────────────────────────────────── */

    /** Kolom header Excel per kategori */
    private function templateHeaders(string $kategori): array
    {
        $shared = ['Nama', 'Email', 'Judul', 'Tahun', 'Abstrak', 'Program Studi'];

        return match ($kategori) {
            'skripsi' => array_merge(['NIM'], $shared, ['Dosen Pembimbing', 'File Dokumen']),
            'magang' => array_merge(['NIM'], $shared, ['Tempat Magang', 'Dosen Pembimbing', 'File Dokumen']),
            'pkm' => array_merge(['NIDN'], $shared, ['Detail', 'File Dokumen']),
            default => array_merge(['NIDN'], $shared, ['Detail', 'File Dokumen']), // penelitian
        };
    }

    /**
     * Tampilkan halaman form import Excel.
     */
    public function showImport(Request $request)
    {
        $kategori = $request->query('kategori', 'skripsi');
        $validKategori = ['skripsi', 'magang', 'pkm', 'penelitian'];
        if (!in_array($kategori, $validKategori, true)) {
            $kategori = 'skripsi';
        }

        $headers = $this->templateHeaders($kategori);

        return view('admin.documents.import', compact('kategori', 'headers', 'validKategori'));
    }

    /**
     * Proses file Excel/CSV yang diupload — opsional beserta file ZIP berisi lampiran PDF.
     */
    public function import(Request $request)
    {
        $request->validate([
            'kategori' => ['required', 'in:skripsi,magang,pkm,penelitian'],
            'file' => ['required', 'file', 'mimes:xlsx,csv', 'max:10240'],
            'file_zip' => ['nullable', 'file', 'mimes:zip', 'max:819200'], // max 800 MB
        ], [
            'file.required' => 'File Excel/CSV wajib dipilih.',
            'file.mimes' => 'File harus berformat .xlsx atau .csv.',
            'file.max' => 'Ukuran file Excel maksimal 10 MB.',
            'file_zip.mimes' => 'File lampiran harus berformat .zip.',
            'file_zip.max' => 'Ukuran file ZIP maksimal 800 MB.',
        ]);

        $kategori = $request->kategori;
        $uploaded = $request->file('file');
        $extension = strtolower($uploaded->getClientOriginalExtension());
        $filePath = $uploaded->getRealPath();

        $zipExtractDir = null;
        if ($request->hasFile('file_zip')) {
            try {
                $zipFile = $request->file('file_zip');
                $zip = new \ZipArchive();
                if ($zip->open($zipFile->getRealPath()) === true) {
                    $zipExtractDir = storage_path('app/temp_excel_zip_' . Str::random(16));
                    $zip->extractTo($zipExtractDir);
                    $zip->close();
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal mengekstrak ZIP lampiran pada Import Excel: ' . $e->getMessage());
            }
        }

        $importer = new RepositoryDocumentImport($kategori, $uploaded->getClientOriginalName());

        if ($zipExtractDir) {
            $importer->setZipExtractPath($zipExtractDir);
        }

        try {
            $importer->importFromFile($filePath, $extension);
        } catch (\Throwable $e) {
            if ($zipExtractDir && is_dir($zipExtractDir)) {
                $this->deleteDirectory($zipExtractDir);
            }

            return back()
                ->withInput()
                ->with('import_error', 'Gagal memproses file: ' . $e->getMessage());
        }

        if ($zipExtractDir && is_dir($zipExtractDir)) {
            $this->deleteDirectory($zipExtractDir);
        }

        return back()->with([
            'import_success' => $importer->successCount,
            'import_errors' => $importer->errorRows,
            'import_kategori' => $kategori,
        ]);
    }

    /**
     * Download template CSV sesuai kategori (tidak butuh library pihak ketiga).
     * File CSV bisa langsung dibuka di Excel, Google Sheets, LibreOffice, dll.
     */
    public function downloadTemplate(string $kategori)
    {
        $validKategori = ['skripsi', 'magang', 'pkm', 'penelitian'];
        abort_if(!in_array($kategori, $validKategori, true), 404);

        $headers = $this->templateHeaders($kategori);
        $filename = 'template_import_' . $kategori . '.csv';

        $example = [];
        switch ($kategori) {
            case 'skripsi':
                $example = [
                    '12345678',
                    'Budi Santoso',
                    'budi@example.com',
                    'Judul Skripsi Contoh',
                    date('Y'),
                    'Abstrak singkat tentang penelitian ini.',
                    'Teknik Informatika',
                    'Dr. Ahmad Fauzi',
                    'budi_skripsi.pdf',
                ];
                break;
            case 'magang':
                $example = [
                    '12345678',
                    'Siti Rahayu',
                    'siti@example.com',
                    'Laporan Magang di Perusahaan X',
                    date('Y'),
                    'Abstrak singkat laporan magang.',
                    'Sistem Informasi',
                    'PT. Karya Abadi',
                    'Dr. Budi Santoso',
                    'siti_magang.pdf',
                ];
                break;
            case 'pkm':
                $example = [
                    '0123456789',
                    'Dr. Ahmad Fauzi',
                    'ahmad@kampus.ac.id',
                    'Judul PKM Contoh',
                    date('Y'),
                    'Abstrak PKM singkat.',
                    'Teknik Informatika',
                    'Detail tambahan tentang PKM ini.',
                    'pkm_ahmad.pdf',
                ];
                break;
            default: // penelitian
                $example = [
                    '0123456789',
                    'Prof. Dr. Sari Dewi',
                    'sari@kampus.ac.id',
                    'Judul Penelitian Contoh',
                    date('Y'),
                    'Abstrak penelitian dosen.',
                    'Matematika',
                    'Detail tambahan penelitian.',
                    'penelitian_sari.pdf',
                ];
                break;
        }

        $example = array_slice(array_pad($example, count($headers), ''), 0, count($headers));

        return response()->streamDownload(function () use ($headers, $example) {
            // BOM UTF-8 agar Excel membaca karakter Indonesia dengan benar
            echo "\xEF\xBB\xBF";
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers, ',', '"');
            fputcsv($handle, $example, ',', '"');
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Ekstrak file arsip (.zip, .rar, .7z, dll) ke direktori tujuan.
     */
    private function extractArchiveFile(string $archivePath, string $destinationDir): bool
    {
        if (!file_exists($archivePath)) {
            return false;
        }

        if (!is_dir($destinationDir)) {
            @mkdir($destinationDir, 0777, true);
        }

        $ext = strtolower(pathinfo($archivePath, PATHINFO_EXTENSION));

        if ($ext === 'zip') {
            try {
                $zip = new \ZipArchive();
                if ($zip->open($archivePath) === true) {
                    $zip->extractTo($destinationDir);
                    $zip->close();
                    return true;
                }
            } catch (\Throwable $e) {
                // fallback to tar.exe
            }
        }

        // Use bsdtar (tar.exe) on Windows/Linux to extract .rar, .zip, .7z, etc.
        $cmd = 'tar.exe -xf ' . escapeshellarg($archivePath) . ' -C ' . escapeshellarg($destinationDir) . ' 2>&1';
        @exec($cmd, $output, $resultCode);

        return is_dir($destinationDir) && count(scandir($destinationDir)) > 2;
    }

    /**
     * Proses upload file ZIP / RAR berisi file-file PDF / DOC dokumen.
     * Setiap file dokumen di dalam ZIP/RAR menjadi 1 record RepositoryDocument (status pending).
     */
    public function importZip(Request $request)
    {
        $request->validate([
            'kategori' => ['required', 'in:skripsi,magang,pkm,penelitian'],
            'file_zip' => ['required', 'file', 'mimes:zip,rar', 'extensions:zip,rar,7z', 'max:819200'], // max 800 MB
        ], [
            'file_zip.required' => 'File ZIP/RAR wajib dipilih.',
            'file_zip.mimes'    => 'File harus berformat .zip atau .rar.',
            'file_zip.max'      => 'Ukuran file ZIP/RAR maksimal 800 MB.',
        ]);

        $kategori = $request->kategori;
        $uploaded = $request->file('file_zip');

        $extractDir = storage_path('app/temp_zip_' . Str::random(16));
        $successCount = 0;
        $errorRows = [];

        try {
            $extractedOk = $this->extractArchiveFile($uploaded->getRealPath(), $extractDir);

            if (!$extractedOk) {
                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Gagal membuka file ZIP/RAR. Pastikan file tidak rusak.',
                    ], 422);
                }

                return back()
                    ->withInput()
                    ->with('import_error', 'Gagal membuka file ZIP/RAR. Pastikan file tidak rusak.');
            }

            // Unpack any nested archive files (.rar, .zip, .7z) found inside the extracted directory
            for ($pass = 0; $pass < 3; $pass++) {
                $nestedArchives = [];
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($extractDir, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if (!$file->isFile()) continue;
                    $fn = $file->getFilename();
                    $pn = $file->getPathname();
                    if (str_starts_with($fn, '._') || str_contains($pn, '__MACOSX')) continue;

                    $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
                    if (in_array($ext, ['rar', 'zip', '7z', 'tar', 'gz'], true)) {
                        $nestedArchives[] = $file->getRealPath();
                    }
                }

                if (empty($nestedArchives)) {
                    break;
                }

                foreach ($nestedArchives as $archivePath) {
                    $subDir = dirname($archivePath) . '/unpacked_' . Str::random(8);
                    $this->extractArchiveFile($archivePath, $subDir);
                    @unlink($archivePath);
                }
            }

            // Scan all document files recursively
            $docFiles = [];
            $allFoundFiles = [];
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($extractDir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                $filename = $file->getFilename();
                $pathname = $file->getPathname();

                // Skip macOS AppleDouble hidden files (._filename) and __MACOSX directories
                if (str_starts_with($filename, '._') || str_contains($pathname, '__MACOSX')) {
                    continue;
                }

                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allFoundFiles[] = $filename;

                if (in_array($ext, ['pdf', 'doc', 'docx', 'rtf', 'odt', 'txt'], true)) {
                    $docFiles[] = [
                        'path' => $file->getRealPath(),
                        'filename' => $filename,
                        'extension' => $ext,
                    ];
                }
            }

            if (empty($docFiles)) {
                $fileHint = !empty($allFoundFiles)
                    ? ' Berkas yang ditemukan di dalam arsip: ' . implode(', ', array_slice($allFoundFiles, 0, 5)) . '.'
                    : ' Berkas arsip kosong atau tidak berisi file.';

                $errMsg = 'Tidak ditemukan berkas dokumen (.pdf, .doc, .docx, .rtf, .odt, .txt) di dalam file ZIP/RAR.' . $fileHint;

                if ($request->ajax()) {
                    return response()->json([
                        'message' => $errMsg,
                    ], 422);
                }

                return back()
                    ->withInput()
                    ->with('import_error', $errMsg);
            }

            foreach ($docFiles as $index => $item) {
                $rowNum = $index + 1;
                $pdfPath = $item['path'];
                $originalName = $item['filename'];
                $ext = $item['extension'];

                try {
                    // Parse basic info from filename (remove extension)
                    $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

                    $mimeType = match ($ext) {
                        'pdf' => 'application/pdf',
                        'doc' => 'application/msword',
                        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'rtf' => 'application/rtf',
                        'odt' => 'application/vnd.oasis.opendocument.text',
                        'txt' => 'text/plain',
                        default => 'application/octet-stream',
                    };

                    // Store the document file
                    $storedPath = null;
                    $uploadedFile = new \Illuminate\Http\UploadedFile(
                        $pdfPath,
                        $originalName,
                        $mimeType,
                        null,
                        true
                    );
                    $storedPath = $this->storeUploadedFile($uploadedFile, 'repository-documents');

                    // Count PDF pages if PDF
                    $pdfPageCount = null;
                    if ($ext === 'pdf') {
                        try {
                            $pdfPageCount = $this->countPdfPages($pdfPath);
                        } catch (\Throwable $e) {
                            // ignore
                        }
                    }

                    RepositoryDocument::create([
                        'user_id'          => Auth::id(),
                        'input_by'         => Auth::id(),
                        'kategori'         => $kategori,
                        'jenis_input'      => 'upload',
                        'nama'             => $nameWithoutExt,
                        'judul'            => $nameWithoutExt,
                        'tahun'            => date('Y'),
                        'bulan'            => now()->month,
                        'file_dokumen'     => $storedPath,
                        'pdf_page_count'   => $pdfPageCount,
                        'status'           => 'pending',
                        'tanggal_upload'   => now(),
                        'submission_token' => Str::random(48),
                    ]);

                    $successCount++;
                } catch (\Throwable $e) {
                    $errorRows[] = [
                        'row'    => $rowNum,
                        'nama'   => $originalName,
                        'judul'  => $originalName,
                        'errors' => [$e->getMessage()],
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Clean up temp dir — errors suppressed
            try {
                if (is_dir($extractDir)) {
                    $this->deleteDirectory($extractDir);
                }
            } catch (\Throwable $e) {
                // Silently ignore cleanup errors
            }

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Gagal memproses file ZIP: ' . $e->getMessage(),
                ], 500);
            }

            return back()
                ->withInput()
                ->with('import_error', 'Gagal memproses file ZIP: ' . $e->getMessage());
        }

        // Clean up temp dir — errors suppressed, cleanup failures must not affect the response
        try {
            if (is_dir($extractDir)) {
                $this->deleteDirectory($extractDir);
            }
        } catch (\Throwable $e) {
            // Silently ignore cleanup errors (e.g. Windows file lock)
        }

        if ($request->ajax()) {
            if ($successCount === 0 && count($errorRows) > 0) {
                $firstErr = $errorRows[0]['errors'][0] ?? 'Gagal memproses file dokumen.';
                return response()->json([
                    'message' => 'Gagal memproses file ZIP: ' . $firstErr,
                    'errors' => $errorRows,
                ], 422);
            }

            $message = 'Upload ZIP berhasil! ' . $successCount . ' file dokumen berhasil diproses sebagai data ' . strtoupper($kategori) . '.';
            if (count($errorRows) > 0) {
                $message .= ' (' . count($errorRows) . ' file gagal diproses).';
            }

            return response()->json([
                'message' => $message,
                'success_count' => $successCount,
                'error_count' => count($errorRows),
                'kategori' => $kategori,
            ]);
        }

        return back()->with([
            'import_success'  => $successCount,
            'import_errors'   => $errorRows,
            'import_kategori' => $kategori,
            'status'          => 'Upload file ZIP berhasil diproses (' . $successCount . ' berkas).',
        ]);
    }

    /**
     * Hapus direktori secara rekursif.
     */
    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        try {
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($items as $item) {
                if ($item->isDir()) {
                    @rmdir($item->getRealPath());
                } else {
                    // On Windows, files may be temporarily locked by antivirus/OS.
                    // Retry up to 3 times with a short delay before giving up.
                    $path = $item->getRealPath();
                    $deleted = false;
                    for ($i = 0; $i < 3; $i++) {
                        if (@unlink($path)) {
                            $deleted = true;
                            break;
                        }
                        // Brief pause to allow OS to release the file lock
                        usleep(50000); // 50ms
                    }
                    // If still locked, skip silently — OS will clean temp files eventually
                }
            }
        } catch (\Throwable $e) {
            // Directory cleanup failed — silently ignore, temp files will be cleaned by OS
        }

        @rmdir($dir);
    }

    /**
     * Download template Excel (.xls) sesuai kategori.
     * Menggunakan format HTML Spreadsheet yang kompatibel dengan Microsoft Excel.
     */
    public function downloadTemplateExcel(string $kategori)
    {
        $validKategori = ['skripsi', 'magang', 'pkm', 'penelitian'];
        abort_if(!in_array($kategori, $validKategori, true), 404);

        $headers = $this->templateHeaders($kategori);
        $filename = 'template_import_' . $kategori . '.xls';

        $example = match ($kategori) {
            'skripsi' => ['12345678', 'Budi Santoso', 'budi@example.com', 'Judul Skripsi Contoh', date('Y'), 'Abstrak singkat tentang penelitian ini.', 'Teknik Informatika', 'Dr. Ahmad Fauzi', 'budi_skripsi.pdf'],
            'magang'  => ['12345678', 'Siti Rahayu', 'siti@example.com', 'Laporan Magang di Perusahaan X', date('Y'), 'Abstrak singkat laporan magang.', 'Sistem Informasi', 'PT. Karya Abadi', 'Dr. Budi Santoso', 'siti_magang.pdf'],
            'pkm'     => ['0123456789', 'Dr. Ahmad Fauzi', 'ahmad@kampus.ac.id', 'Judul PKM Contoh', date('Y'), 'Abstrak PKM singkat.', 'Teknik Informatika', 'Detail tambahan tentang PKM ini.', 'pkm_ahmad.pdf'],
            default   => ['0123456789', 'Prof. Dr. Sari Dewi', 'sari@kampus.ac.id', 'Judul Penelitian Contoh', date('Y'), 'Abstrak penelitian dosen.', 'Matematika', 'Detail tambahan penelitian.', 'penelitian_sari.pdf'],
        };

        $example = array_slice(array_pad($example, count($headers), ''), 0, count($headers));

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Template ' . ucfirst($kategori) . '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body><table border="1">';
        $html .= '<tr style="background-color: #2563eb; color: #ffffff; font-weight: bold;">';
        foreach ($headers as $h) {
            $html .= '<th style="padding: 5px;">' . htmlspecialchars($h) . '</th>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        foreach ($example as $val) {
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($val) . '</td>';
        }
        $html .= '</tr>';
        $html .= '</table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
