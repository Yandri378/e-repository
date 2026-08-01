<?php

namespace App\Http\Controllers;

use App\Models\GuideTemplate;
use App\Models\RepositoryDocument;
use App\Models\RepositorySetting;
use App\Models\RepositoryDocumentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function home()
    {
        $stats = [
            'skripsi' => $this->publicDocuments()->where('kategori', 'skripsi')->count(),
            'magang' => $this->publicDocuments()->where('kategori', 'magang')->count(),
            'pkm' => $this->publicDocuments()->where('kategori', 'pkm')->count(),
            'penelitian' => $this->publicDocuments()->where('kategori', 'penelitian')->count(),
        ];

        $featured = $this->publicDocuments()->latest()->limit(6)->get();
        $uploadStatuses = RepositorySetting::uploadStatuses();

        return view('pages.home', compact('stats', 'featured', 'uploadStatuses'));
    }

    public function repository(Request $request, ?string $kategori = null)
    {
        $search = trim((string) ($request->query('search') ?? $request->query('q') ?? ''));
        $isSingleLetterSearch = strlen($search) === 1 && ctype_alpha($search);

        $documents = $this->publicDocuments()
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->when($search !== '', function ($query) use ($search, $isSingleLetterSearch) {
                if ($isSingleLetterSearch) {
                    $query->where('judul', 'like', $search.'%');

                    return;
                }

                $query->where(function ($subQuery) use ($search) {
                    $keyword = '%'.$search.'%';

                    $subQuery->where('judul', 'like', $keyword)
                        ->orWhere('nama', 'like', $keyword)
                        ->orWhere('nim', 'like', $keyword)
                        ->orWhere('nidn', 'like', $keyword)
                        ->orWhere('abstrak', 'like', $keyword)
                        ->orWhere('detail', 'like', $keyword)
                        ->orWhere('tempat_magang', 'like', $keyword)
                        ->orWhere('tahun', 'like', $keyword);
                });
            })
            ->when($search !== '', function ($query) use ($search, $isSingleLetterSearch) {
                if (! $isSingleLetterSearch) {
                    $query->orderByRaw('CASE WHEN LOWER(judul) LIKE LOWER(?) THEN 0 ELSE 1 END', [$search.'%']);
                }

                $query->orderByRaw('LOWER(judul) ASC');
            }, fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('pages.repository', compact('documents', 'kategori', 'search'));
    }

    public function guides()
    {
        $guides = GuideTemplate::where('aktif', true)->latest()->get();

        return view('pages.guides', compact('guides'));
    }

    public function showDocument(RepositoryDocument $document)
    {
        // Allow owners to preview their own uploads even if not yet verified
        $isOwner = auth()->check() && auth()->id() === $document->user_id;
        abort_if(! $isOwner && ! in_array($document->status, ['terverifikasi', 'arsip'], true), 404);
        abort_if(! $document->file_dokumen, 404, 'File dokumen belum tersedia.');
        // check storage presence in private/public
        if (Storage::disk('local')->exists($document->file_dokumen) || Storage::disk('public')->exists($document->file_dokumen)) {
            // create a temporary signed URL valid for 15 minutes
            $signed = URL::temporarySignedRoute('repository.stream.signed', now()->addMinutes(15), ['document' => $document]);
            $fileUrl = $signed.'#toolbar=0&navpanes=0&scrollbar=1';
        } else {
            abort(404, 'File dokumen tidak ditemukan.');
        }
        $watermark = null;
        if ($isOwner) {
            $user = auth()->user();
            $watermark = trim(($user->name ?? $document->nama).' | '.($user->email ?? $document->email).' | '.now()->toDateTimeString());
        }

        return view('pages.document-viewer', [
            'document' => $document,
            'fileUrl' => $fileUrl,
            'watermark' => $watermark,
        ]);
    }

    public function streamDocument(RepositoryDocument $document)
    {
        // Allow owner to stream own upload regardless of verification
        $isOwner = auth()->check() && auth()->id() === $document->user_id;
        abort_if(! $isOwner && ! in_array($document->status, ['terverifikasi', 'arsip'], true), 404);
        abort_if(! $document->file_dokumen, 404, 'File dokumen belum tersedia.');

        // Prefer private storage for uploaded documents
        if (Storage::disk('local')->exists($document->file_dokumen)) {
            $path = Storage::disk('local')->path($document->file_dokumen);
        } elseif (Storage::disk('public')->exists($document->file_dokumen)) {
            $path = Storage::disk('public')->path($document->file_dokumen);
        } else {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        // Log access: who and when
        try {
            RepositoryDocumentAccess::create([
                'repository_document_id' => $document->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // non-fatal logging error
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="repository-view.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            // Prevent browsers from caching the PDF to reduce easy saving
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            // Hint older IE not to open downloaded files
            'X-Download-Options' => 'noopen',
            // Allow same-origin framing so the modal iframe can render the PDF
            'X-Frame-Options' => 'SAMEORIGIN',
            // Ensure frame-ancestors allows self (some servers add restrictive CSP)
            'Content-Security-Policy' => "default-src 'self'; frame-ancestors 'self'; object-src 'none'",
        ]);
    }

    public function metadata(RepositoryDocument $document)
    {
        // Similar access rules as showDocument
        $isOwner = auth()->check() && auth()->id() === $document->user_id;
        abort_if(! $isOwner && ! in_array($document->status, ['terverifikasi', 'arsip'], true), 404);

        if (! (Storage::disk('local')->exists($document->file_dokumen) || Storage::disk('public')->exists($document->file_dokumen))) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        $signed = URL::temporarySignedRoute('repository.stream.signed', now()->addMinutes(15), ['document' => $document]);
        $fileUrl = $signed.'#toolbar=0&navpanes=0&scrollbar=1';
        $watermark = null;
        if ($isOwner) {
            $user = auth()->user();
            $watermark = trim(($user->name ?? $document->nama).' | '.($user->email ?? $document->email).' | '.now()->toDateTimeString());
        }

        return response()->json([
            'fileUrl' => $fileUrl,
            'watermark' => $watermark,
        ]);
    }

    public function uploadStatuses()
    {
        return response()->json(RepositorySetting::uploadStatuses());
    }

    public function mahasiswaHome()
    {
        $uploadStatuses = RepositorySetting::uploadStatuses();
        $categories = ['skripsi' => 'Skripsi/TA', 'magang' => 'Laporan Magang'];

        return view('pages.actor-home', [
            'title' => 'Beranda Mahasiswa',
            'heroLabel' => 'Mahasiswa',
            'heroHeading' => 'Upload skripsi dan laporan magang tanpa akun.',
            'heroDescription' => 'Mahasiswa dapat langsung mengunggah dokumen PDF saat sesi dibuka oleh admin, tanpa perlu membuat akun.',
            'categories' => $categories,
            'uploadStatuses' => $uploadStatuses,
            'actor' => 'mahasiswa',
            'ctaLabel' => 'Upload sekarang',
        ]);
    }

    public function dosenHome()
    {
        $uploadStatuses = RepositorySetting::uploadStatuses();
        $categories = ['pkm' => 'PKM', 'penelitian' => 'Penelitian'];

        return view('pages.actor-home', [
            'title' => 'Beranda Dosen',
            'heroLabel' => 'Dosen',
            'heroHeading' => 'Upload PKM dan penelitian tanpa akun.',
            'heroDescription' => 'Dosen dapat langsung mengunggah karya ilmiah dan penelitian saat sesi dibuka oleh admin.',
            'categories' => $categories,
            'uploadStatuses' => $uploadStatuses,
            'actor' => 'dosen',
            'ctaLabel' => 'Upload sekarang',
        ]);
    }

    public function downloadRequested(Request $request, RepositoryDocument $document)
    {
        // expecting submission_token in query
        $token = $request->query('submission_token');
        abort_if(! $token, 403, 'Submission token diperlukan.');

        $req = \App\Models\RepositoryDownloadRequest::where('repository_document_id', $document->id)
            ->where('submission_token', $token)
            ->where('status', 'approved')
            ->latest()
            ->first();

        abort_if(! $req, 403, 'Permintaan unduh belum disetujui atau tidak ditemukan.');

        // Stream file (reuse streaming helper)
        if (Storage::disk('local')->exists($document->file_dokumen)) {
            $path = Storage::disk('local')->path($document->file_dokumen);
        } elseif (Storage::disk('public')->exists($document->file_dokumen)) {
            $path = Storage::disk('public')->path($document->file_dokumen);
        } else {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        // Stream as download attachment for approved requester
        return response()->download($path, basename($path));
    }

    public function streamSigned(Request $request, RepositoryDocument $document)
    {
        // verify signature
        if (! $request->hasValidSignature()) {
            abort(403, 'Tanda tangan URL tidak valid atau sudah kadaluarsa.');
        }

        // Use same rules as streamDocument (owner always allowed, otherwise must be verified/arsip)
        $isOwner = auth()->check() && auth()->id() === $document->user_id;
        abort_if(! $isOwner && ! in_array($document->status, ['terverifikasi', 'arsip'], true), 404);
        abort_if(! $document->file_dokumen, 404, 'File dokumen belum tersedia.');

        if (Storage::disk('local')->exists($document->file_dokumen)) {
            $path = Storage::disk('local')->path($document->file_dokumen);
        } elseif (Storage::disk('public')->exists($document->file_dokumen)) {
            $path = Storage::disk('public')->path($document->file_dokumen);
        } else {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        try {
            RepositoryDocumentAccess::create([
                'repository_document_id' => $document->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log document access: '.$e->getMessage());
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="repository-view.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Download-Options' => 'noopen',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Content-Security-Policy' => "default-src 'self'; frame-ancestors 'self'; object-src 'none'",
        ];

        $isOwner = auth()->check() && auth()->id() === $document->user_id;
        $watermark = null;
        if ($isOwner) {
            $user = auth()->user();
            $watermark = trim(($user->name ?? $document->nama).' | '.($user->email ?? $document->email).' | '.now()->toDateTimeString());
        }

        return $this->streamPdfWithOptionalWatermark($path, $watermark, $headers);
    }

    /**
     * Stream PDF, applying a simple textual watermark if possible.
     * This uses setasign/fpdi if available; otherwise returns the file directly.
     */
    private function streamPdfWithOptionalWatermark(string $path, ?string $watermark, array $headers)
    {
        if ($watermark && class_exists('\\setasign\\Fpdi\\Fpdi')) {
            try {
                $pdf = new \setasign\Fpdi\Fpdi();
                $pageCount = $pdf->setSourceFile($path);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);
                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                    $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);

                    // watermark: light gray centered text
                    $pdf->SetFont('Helvetica', 'B', 14);
                    $pdf->SetTextColor(150, 150, 150);
                    $pdf->SetXY(10, $size['height'] / 2);
                    $pdf->Cell($size['width'] - 20, 0, $watermark, 0, 0, 'C');
                }

                $tmp = tempnam(sys_get_temp_dir(), 'wm_pdf');
                if (! $tmp) {
                    return response()->file($path, $headers);
                }
                // Ensure file has .pdf extension for some clients
                $tmpPdf = $tmp.'.pdf';
                $pdf->Output($tmpPdf, 'F');

                return response()->file($tmpPdf, $headers)->deleteFileAfterSend(true);
            } catch (\Throwable $e) {
                Log::error('Watermarking failed: '.$e->getMessage());
                return response()->file($path, $headers);
            }
        }

        return response()->file($path, $headers);
    }

    private function publicDocuments()
    {
        return RepositoryDocument::whereIn('status', ['terverifikasi', 'arsip']);
    }
}
