<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\RepositoryDocumentImport;
use App\Models\JenisDokumen;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $pendingCount     = $bulkPendingCount;
        $verifiedCount    = RepositoryDocument::where('status', 'terverifikasi')->count();
        $rejectedCount    = RepositoryDocument::where('status', 'ditolak')->count();
        $allCount         = RepositoryDocument::count();

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
        if (! in_array($kategori, ['skripsi', 'penelitian', 'pkm'], true)) {
            $kategori = 'skripsi';
        }

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();
        $jenisDokumen = JenisDokumen::where('aktif', true)->orderBy('nama')->get();

        return view('admin.documents.create', compact('kategori', 'programStudi', 'jenisDokumen'));
    }

    public function store(Request $request)
    {
        $kategori = $request->input('kategori', 'skripsi');
        if (! in_array($kategori, ['skripsi', 'penelitian', 'pkm'], true)) {
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
            'file_dokumen' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'file_project' => ['nullable', 'file', 'mimes:zip,rar', 'extensions:zip,rar', 'max:307200'],
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
            'file_project.max' => 'Ukuran file project ZIP/RAR melebihi batas maksimum 300 MB.',
        ];

        $data = $request->validate($rules, $messages);

        try {
            $uploadedPdf = $request->file('file_dokumen');
            $storedPdfPath = $uploadedPdf->store('repository-documents', 'local');

            $storedProjectPath = null;
            if ($request->hasFile('file_project')) {
                $storedProjectPath = $request->file('file_project')->store('repository-projects', 'local');
            }

            $pdfPageCount = $this->countPdfPages($uploadedPdf->getRealPath());

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
                ->with('status', 'Data '.strtoupper($kategori).' "'.$document->judul.'" berhasil diupload secara manual.');
        } catch (\Throwable $e) {
            Log::error('Upload manual admin gagal: '.$e->getMessage(), ['exception' => $e]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Upload dokumen gagal: '.$e->getMessage());
        }
    }

    private function countPdfPages(string $filePath): ?int
    {
        if (! file_exists($filePath)) {
            return null;
        }

        $handle = fopen($filePath, 'rb');
        if (! $handle) {
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
        abort_if($kategori && ! in_array($kategori, ['pkm', 'penelitian'], true), 404);

        $documents = $this->searchDocuments($request)
            ->whereIn('kategori', $kategori ? [$kategori] : ['pkm', 'penelitian'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.data.dosen', compact('documents', 'kategori'));
    }

    public function exportMahasiswa(Request $request, string $format)
    {
        abort_if(! in_array($format, ['excel', 'pdf'], true), 404);

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
        abort_if(! in_array($format, ['excel', 'pdf'], true), 404);

        $documents = $this->searchDocuments($request)
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
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
            ->map(fn ($line) => '('.str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line).') Tj T*')
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
            && ! $document->dosen_approved_at
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

        return back()->with('status', 'Status dokumen "'.$document->judul.'" berhasil diperbarui.');
    }

    public function verifyAll()
    {
        $count = RepositoryDocument::where('status', 'pending')->update([
            'status' => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => null,
        ]);

        return back()->with('status', $count.' upload berhasil diverifikasi semuanya.');
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
        abort_if(! $document->file_dokumen, 404, 'File dokumen belum tersedia.');
        // Prefer private storage; fallback to public
        if (Storage::disk('local')->exists($document->file_dokumen)) {
            return Storage::disk('local')->download($document->file_dokumen);
        }

        abort_if(! Storage::disk('public')->exists($document->file_dokumen), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('public')->download($document->file_dokumen);
    }

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

        return back()->with('status', 'Data dokumen "'.$title.'" berhasil dihapus.');
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
            'Dokumen: '.($downloadRequest->document->judul ?? '-'),
            'Anda dapat mengunduh melalui link ini (masukkan submission token jika diperlukan):',
            route('repository.download', $downloadRequest->document).'?submission_token='.$downloadRequest->submission_token,
            '',
            'Terima kasih.'
        ]);

        $waUrl = $phone ? ('https://wa.me/'.$phone.'?text='.rawurlencode($approvalMsg)) : null;

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
            'hard_copy_submitted'      => 'Status hard copy',
            'pdf_kelengkapan_confirmed' => 'Konfirmasi kelengkapan PDF',
            'has_active_loans'         => 'Status pinjaman buku',
        ];

        $statusText = $data['value'] ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('status', ($labels[$data['field']] ?? $data['field']).' berhasil '.$statusText.' untuk dokumen "'.$document->judul.'".');
    }

    /**
     * Admin explicitly approves the download of Kartu Bebas Pustaka for a specific document.
     * All other prerequisites must already be met before this can be granted.
     */
    public function approveBebasPustaka(RepositoryDocument $document)
    {
        // Ensure all other checklist items are done (except the approval itself)
        $requiredMet = ! $document->has_active_loans
            && $document->dosen_approved_at
            && $document->pdf_kelengkapan_deklarasi
            && $document->pdf_kelengkapan_confirmed
            && $document->hard_copy_submitted;

        if (! $requiredMet) {
            return back()->withErrors([
                'bebas_pustaka' => 'Tidak dapat memberikan izin: masih ada syarat bebas pustaka yang belum terpenuhi. Pastikan: tidak ada pinjaman buku, sudah ACC dosen, PDF sudah dikonfirmasi lengkap, dan hard copy sudah diserahkan.',
            ]);
        }

        $document->update([
            'bebas_pustaka_diizinkan'    => true,
            'bebas_pustaka_diizinkan_by' => Auth::id(),
            'bebas_pustaka_diizinkan_at' => now(),
        ]);

        return back()->with('status', 'Izin download Kartu Bebas Pustaka berhasil diberikan untuk "'.$document->judul.'".');
    }

    /**
     * Admin revokes the bebas pustaka download permission.
     */
    public function revokeBebasPustaka(RepositoryDocument $document)
    {
        $document->update([
            'bebas_pustaka_diizinkan'    => false,
            'bebas_pustaka_diizinkan_by' => null,
            'bebas_pustaka_diizinkan_at' => null,
        ]);

        return back()->with('status', 'Izin download Kartu Bebas Pustaka berhasil dicabut untuk "'.$document->judul.'".');
    }

    /* ─────────────────────────────────────────
     |  IMPORT EXCEL
     ───────────────────────────────────────── */

    /** Kolom header Excel per kategori */
    private function templateHeaders(string $kategori): array
    {
        $shared = ['Nama', 'Email', 'Judul', 'Tahun', 'Abstrak', 'Program Studi'];

        return match ($kategori) {
            'skripsi' => array_merge(['NIM'], $shared, ['Dosen Pembimbing']),
            'magang'  => array_merge(['NIM'], $shared, ['Tempat Magang', 'Dosen Pembimbing']),
            'pkm'     => array_merge(['NIDN'], $shared, ['Detail']),
            default   => array_merge(['NIDN'], $shared, ['Detail']), // penelitian
        };
    }

    /**
     * Tampilkan halaman form import Excel.
     */
    public function showImport(Request $request)
    {
        $kategori = $request->query('kategori', 'skripsi');
        $validKategori = ['skripsi', 'magang', 'pkm', 'penelitian'];
        if (! in_array($kategori, $validKategori, true)) {
            $kategori = 'skripsi';
        }

        $headers = $this->templateHeaders($kategori);

        return view('admin.documents.import', compact('kategori', 'headers', 'validKategori'));
    }

    /**
     * Proses file Excel/CSV yang diupload — tanpa library pihak ketiga.
     */
    public function import(Request $request)
    {
        $request->validate([
            'kategori' => ['required', 'in:skripsi,magang,pkm,penelitian'],
            'file'     => ['required', 'file', 'mimes:xlsx,csv', 'max:10240'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat .xlsx atau .csv.',
            'file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        $kategori  = $request->kategori;
        $uploaded  = $request->file('file');
        $extension = strtolower($uploaded->getClientOriginalExtension());
        $filePath  = $uploaded->getRealPath();

        $importer = new RepositoryDocumentImport($kategori, $uploaded->getClientOriginalName());

        try {
            $importer->importFromFile($filePath, $extension);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('import_error', 'Gagal memproses file: ' . $e->getMessage());
        }

        return back()->with([
            'import_success'  => $importer->successCount,
            'import_errors'   => $importer->errorRows,
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
        abort_if(! in_array($kategori, $validKategori, true), 404);

        $headers  = $this->templateHeaders($kategori);
        $filename = 'template_import_' . $kategori . '.csv';

        $example = [];
        switch ($kategori) {
            case 'skripsi':
                $example = [
                    '12345678', 'Budi Santoso', 'budi@example.com',
                    'Judul Skripsi Contoh', date('Y'), 'Abstrak singkat tentang penelitian ini.',
                    'Teknik Informatika', 'Dr. Ahmad Fauzi',
                ];
                break;
            case 'magang':
                $example = [
                    '12345678', 'Siti Rahayu', 'siti@example.com',
                    'Laporan Magang di Perusahaan X', date('Y'), 'Abstrak singkat laporan magang.',
                    'Sistem Informasi', 'PT. Karya Abadi', 'Dr. Budi Santoso',
                ];
                break;
            case 'pkm':
                $example = [
                    '0123456789', 'Dr. Ahmad Fauzi', 'ahmad@kampus.ac.id',
                    'Judul PKM Contoh', date('Y'), 'Abstrak PKM singkat.',
                    'Teknik Informatika', 'Detail tambahan tentang PKM ini.',
                ];
                break;
            default: // penelitian
                $example = [
                    '0123456789', 'Prof. Dr. Sari Dewi', 'sari@kampus.ac.id',
                    'Judul Penelitian Contoh', date('Y'), 'Abstrak penelitian dosen.',
                    'Matematika', 'Detail tambahan penelitian.',
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
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
