<?php

namespace App\Http\Controllers;

use App\Models\RepositoryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminDocumentController extends Controller
{
    public function pending()
    {
        $documents = RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen', 'dosenPembimbing'])
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNotIn('kategori', ['skripsi', 'magang'])
                    ->orWhereNull('dosen_pembimbing_id')
                    ->orWhereNotNull('dosen_approved_at');
            })
            ->latest()
            ->paginate(10);

        return view('admin.documents.pending', compact('documents'));
    }

    public function index()
    {
        $documents = RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen', 'dosenPembimbing'])
            ->latest()
            ->paginate(12);

        return view('admin.documents.index', compact('documents'));
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
}
