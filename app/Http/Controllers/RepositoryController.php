<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\RepositorySetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RepositoryController extends Controller
{
    public function create(string $kategori)
    {
        $this->authorizeCategory($kategori);

        return view('repository.form', [
            'kategori' => $kategori,
            'actor' => 'admin',
            'isPublic' => false,
            'formAction' => route('repository.store', $kategori),
            'programStudi' => ProgramStudi::where('aktif', true)->orderBy('nama')->get(),
            'jenisDokumen' => JenisDokumen::where('aktif', true)->orderBy('nama')->get(),
            'dosenPembimbing' => User::where('role', 'dosen')
                ->where('status_akun', 'aktif')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request, string $kategori)
    {
        $this->authorizeCategory($kategori);
        $user = $request->user();

        abort_if(! $user, 403);

        $data = $request->validate([
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'jenis_dokumen_id' => ['nullable', 'exists:jenis_dokumen,id'],
            'dosen_pembimbing_id' => [
                $user->role === 'mahasiswa' && in_array($kategori, ['skripsi', 'magang'], true) ? 'required' : 'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'dosen')->where('status_akun', 'aktif')),
            ],
            'jenis_input' => ['nullable', 'in:arsip,upload'],
            'nim' => ['nullable', 'string', 'max:30'],
            'nidn' => ['nullable', 'string', 'max:30'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'tahun' => ['required', 'digits:4'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'judul' => ['required', 'string', 'max:255'],
            'tempat_magang' => ['nullable', 'string', 'max:255'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'abstrak' => ['nullable', 'string'],
            'detail' => ['nullable', 'string'],
            'status_penelitian' => ['nullable', 'in:berjalan,selesai'],
            'file_dokumen' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'file_project' => ['nullable', 'file', 'mimes:zip,rar', 'extensions:zip,rar', 'max:51200'],
            // PDF completeness declaration (required for mahasiswa skripsi/magang if file is uploaded)
            'pdf_kelengkapan_deklarasi' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('file_dokumen')) {
            $uploadedPdf = $request->file('file_dokumen');
            $data['file_dokumen'] = $uploadedPdf->store('repository-documents', 'local');
            $data['jenis_input'] = 'upload';
            $data['status'] = 'pending';

            // Save PDF page count (best-effort binary scan server-side)
            $data['pdf_page_count'] = $this->countPdfPages($uploadedPdf->getRealPath());

            // Save declaration flag from mahasiswa
            if ($user->role === 'mahasiswa' && in_array($kategori, ['skripsi', 'magang'], true)) {
                $data['pdf_kelengkapan_deklarasi'] = $request->boolean('pdf_kelengkapan_deklarasi');

                // Server-side guard: reject if mahasiswa didn't declare completeness
                if (! $data['pdf_kelengkapan_deklarasi']) {
                    throw ValidationException::withMessages([
                        'pdf_kelengkapan_deklarasi' => 'Anda wajib mencentang pernyataan kelengkapan PDF (halaman pengesahan, persetujuan, dan orisinalitas).',
                    ]);
                }
            }
        } elseif (($data['jenis_input'] ?? null) === 'arsip') {
            if ($user->role !== 'admin') {
                throw ValidationException::withMessages([
                    'file_dokumen' => 'Mahasiswa dan dosen wajib upload dokumen PDF.',
                ]);
            }

            $data['status'] = 'arsip';
        } else {
            throw ValidationException::withMessages([
                'file_dokumen' => 'Upload dokumen PDF wajib diisi untuk data upload.',
            ]);
        }

        if ($request->hasFile('file_project')) {
            $data['file_project'] = $request->file('file_project')->store('repository-projects', 'local');
        }

        $document = RepositoryDocument::create(array_merge($data, [
            'kategori' => $kategori,
            'user_id' => Auth::id(),
            'input_by' => Auth::id(),
            'bulan' => $data['bulan'] ?? now()->month,
            'tanggal_upload' => now(),
            'status' => $data['status'] ?? 'pending',
            'submission_token' => $user->role === 'admin' ? Str::random(48) : null,
        ]));

        if ($user->role === 'admin') {
            return redirect()
                ->route('public.upload.detail', [$document, $document->submission_token])
                ->with('status', 'Data berhasil disimpan. Silakan cek preview data di halaman ini.');
        }

        if ($document->jenis_input === 'upload') {
            $message = $user->role === 'mahasiswa'
                ? 'Data berhasil disimpan. Dokumen menunggu ACC dosen pembimbing, lalu akan diverifikasi admin.'
                : 'Data berhasil disimpan. Silakan kirim pemberitahuan WhatsApp ke admin untuk verifikasi.';

            return redirect()
                ->route($user->role.'.dashboard')
                ->with('status', $message)
                ->with('whatsapp_notification_url', $this->adminWhatsappUrl($document));
        }

        return redirect()
            ->route($user->role.'.dashboard')
            ->with('status', 'Data berhasil disimpan.');
    }

    public function download(RepositoryDocument $document)
    {
        abort_if(! $this->canDownloadDocument($document), 403, 'Anda tidak memiliki akses untuk mengunduh dokumen ini.');
        if (! $document->file_dokumen) {
            abort(404, 'File dokumen belum tersedia.');
        }

        $filePath = $document->file_dokumen;

        if (Storage::disk('local')->exists($filePath)) {
            return Storage::disk('local')->download($filePath, $this->downloadFilename($document));
        }

        abort_if(! Storage::disk('public')->exists($filePath), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('public')->download($filePath, $this->downloadFilename($document));
    }

    public function downloadProject(RepositoryDocument $document)
    {
        abort_if(! $this->canDownloadDocument($document), 403, 'Anda tidak memiliki akses untuk mengunduh file project ini.');
        if (! $document->file_project) {
            abort(404, 'File project belum tersedia.');
        }

        $filePath = $document->file_project;

        if (Storage::disk('local')->exists($filePath)) {
            return Storage::disk('local')->download($filePath, $this->projectFilename($document));
        }

        abort_if(! Storage::disk('public')->exists($filePath), 404, 'File project tidak ditemukan.');

        return Storage::disk('public')->download($filePath, $this->projectFilename($document));
    }

    public function publicCreate(string $actor, string $kategori)
    {
        $this->authorizePublicUpload($actor, $kategori);

        if (! RepositorySetting::uploadOpen($kategori)) {
            return view('repository.closed', compact('actor', 'kategori'));
        }

        return view('repository.form', [
            'kategori' => $kategori,
            'actor' => $actor,
            'isPublic' => true,
            'formAction' => route('public.upload.store', [$actor, $kategori]),
            'programStudi' => ProgramStudi::where('aktif', true)->orderBy('nama')->get(),
            'jenisDokumen' => JenisDokumen::where('aktif', true)->where('kategori', $kategori)->orderBy('nama')->get(),
            'dosenPembimbing' => User::where('role', 'dosen')
                ->where('status_akun', 'aktif')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function publicStore(Request $request, string $actor, string $kategori)
    {
        $this->authorizePublicUpload($actor, $kategori);

        if (! RepositorySetting::uploadOpen($kategori)) {
            return back()->withErrors(['sesi' => 'Sesi belum dibuka. Silakan cek kembali setelah admin mengaktifkan sesi kompre.']);
        }

        $identityField = $actor === 'mahasiswa' ? 'nim' : 'nidn';
        $data = $request->validate([
            'program_studi_id' => ['required', 'exists:program_studi,id'],
            'jenis_dokumen_id' => ['nullable', 'exists:jenis_dokumen,id'],
            'dosen_pembimbing_id' => [
                $actor === 'mahasiswa' && in_array($kategori, ['skripsi', 'magang'], true) ? 'required' : 'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'dosen')->where('status_akun', 'aktif')),
            ],
            $identityField => ['required', 'string', 'max:30'],
            // stricter identity format
            'nim' => [$identityField === 'nim' ? 'required' : 'nullable', $identityField === 'nim' ? 'regex:/^\d{6,15}$/' : 'nullable'],
            'nidn' => [$identityField === 'nidn' ? 'required' : 'nullable', $identityField === 'nidn' ? 'regex:/^\d{8,18}$/' : 'nullable'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'tahun' => ['required', 'digits:4'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'judul' => ['required', 'string', 'max:255'],
            'tempat_magang' => ['nullable', 'string', 'max:255'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'abstrak' => ['nullable', 'string'],
            'detail' => ['nullable', 'string', 'max:2000'],
            'status_penelitian' => ['nullable', 'in:berjalan,selesai'],
            'file_dokumen' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'file_project' => ['nullable', 'file', 'mimes:zip,rar', 'extensions:zip,rar', 'max:51200'],
        ]);

        // Store uploads in private disk to prevent direct public access
        $storedPath = $request->file('file_dokumen')->store('repository-documents', 'local');
        $storedProjectPath = $request->hasFile('file_project')
            ? $request->file('file_project')->store('repository-projects', 'local')
            : null;

        $document = RepositoryDocument::create(array_merge($data, [
            'kategori' => $kategori,
            'jenis_input' => 'upload',
            'file_dokumen' => $storedPath,
            'file_project' => $storedProjectPath,
            'bulan' => $data['bulan'] ?? now()->month,
            'tanggal_upload' => now(),
            'status' => 'pending',
            'submission_token' => Str::random(48),
        ]));

        return redirect()
            ->route('public.upload.detail', [$document, $document->submission_token])
            ->with('status', 'Upload berhasil dikirim. Simpan halaman detail ini sebagai bukti submission.');
    }

    public function submissionDetail(RepositoryDocument $document, string $token)
    {
        abort_if(! $document->submission_token || ! hash_equals($document->submission_token, $token), 404);

        return view('repository.detail', compact('document'));
    }

    public function requestDownload(Request $request, RepositoryDocument $document)
    {
        $data = $request->validate([
            'submission_token' => ['required', 'string'],
            'requester_email' => ['required', 'email'],
            'requester_phone' => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        // ensure the token matches the document (owner submitting request)
        abort_if(! $document->submission_token || ! hash_equals($document->submission_token, $data['submission_token']), 403, 'Token submission tidak valid.');

        \App\Models\RepositoryDownloadRequest::create([
            'repository_document_id' => $document->id,
            'submission_token' => $data['submission_token'],
            'requester_email' => $data['requester_email'],
            'requester_phone' => $data['requester_phone'],
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        // Get admin WhatsApp number from settings (fallback to configured constant)
        $adminNumber = RepositorySetting::query()->where('key', 'admin_whatsapp')->value('value') ?? '6285363097108';
        $adminUrl = route('admin.download.requests');
        $msg = implode("\n", [
            'Halo Admin Pustaka,',
            '',
            'Saya mengajukan permintaan unduh dokumen.',
            'Nama: '.$document->nama,
            'Email: '.$data['requester_email'],
            'Telepon: '.$data['requester_phone'],
            'Judul: '.$document->judul,
            '',
            'Mohon dicek dan disetujui. Terima kasih.'
        ]);

        $waUrl = 'https://wa.me/'.$adminNumber.'?text='.rawurlencode($msg).'&source=repo_app';

        return redirect()->route('public.upload.detail', [$document, $document->submission_token])
            ->with('status', 'Permintaan unduh terkirim. Silakan hubungi pustaka via WhatsApp untuk notifikasi cepat.')
            ->with('whatsapp_notification_url', $waUrl);
    }

    private function authorizeCategory(string $kategori): void
    {
        abort_if(! in_array($kategori, ['skripsi', 'magang', 'pkm', 'penelitian'], true), 404);

        $user = Auth::user();

        abort_if(! $user, 403);

        $role = $user->role;
        $allowed = match ($role) {
            'admin' => ['skripsi', 'magang', 'pkm', 'penelitian'],
            'mahasiswa' => ['skripsi', 'magang'],
            'dosen' => ['pkm', 'penelitian'],
            default => [],
        };

        abort_if(! in_array($kategori, $allowed, true), 403, 'Kategori ini tidak tersedia untuk role Anda.');
    }

    private function authorizePublicUpload(string $actor, string $kategori): void
    {
        abort_if(! in_array($actor, ['mahasiswa', 'dosen'], true), 404);
        abort_if(! in_array($kategori, ['skripsi', 'magang', 'pkm', 'penelitian'], true), 404);

        $allowed = $actor === 'mahasiswa'
            ? ['skripsi', 'magang']
            : ['pkm', 'penelitian'];

        abort_if(! in_array($kategori, $allowed, true), 403, 'Kategori ini tidak tersedia untuk '.$actor.'.');
    }

    private function canDownloadDocument(RepositoryDocument $document): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->role === 'admin'
            || $document->user_id === $user->id
            || $document->dosen_pembimbing_id === $user->id
            || in_array($document->status, ['terverifikasi', 'arsip'], true);
    }

    private function downloadFilename(RepositoryDocument $document): string
    {
        return Str::slug($document->kategori.'-'.$document->judul ?: 'repository-document').'.pdf';
    }

    private function projectFilename(RepositoryDocument $document): string
    {
        $extension = pathinfo($document->file_project ?? '', PATHINFO_EXTENSION) ?: 'zip';

        return Str::slug('project-'.$document->kategori.'-'.$document->judul).'.'.$extension;
    }

    /**
     * Count pages in a PDF file by scanning binary content for /Type /Page markers.
     * This is a best-effort detection; does not require external PDF libraries.
     */
    private function countPdfPages(string $filePath): ?int
    {
        if (! file_exists($filePath)) {
            return null;
        }

        // Read first 200KB — enough to find page count in most PDFs
        $handle = fopen($filePath, 'rb');
        if (! $handle) {
            return null;
        }

        $chunk = fread($handle, 204800);
        fclose($handle);

        if ($chunk === false) {
            return null;
        }

        // Match /Type /Page (not /Pages) patterns
        preg_match_all('/\/Type\s*\/Page[^s]/', $chunk, $matches);

        return count($matches[0]) ?: null;
    }

    private function adminWhatsappUrl(RepositoryDocument $document): string
    {
        $identity = $document->nim ?: $document->nidn ?: '-';
        $message = implode("\n", [
            'Halo Admin Repository, saya sudah mengupload dokumen baru.',
            '',
            'Nama: '.$document->nama,
            'Identitas: '.$identity,
            'Kategori: '.strtoupper($document->kategori),
            'Judul: '.$document->judul,
            'Tahun: '.$document->tahun,
            'Status: Menunggu verifikasi',
            '',
            'Mohon dicek dan diverifikasi. Terima kasih.',
        ]);

        $adminNumber = RepositorySetting::query()->where('key', 'admin_whatsapp')->value('value') ?? '6285363097108';

        return 'https://wa.me/'.$adminNumber.'?text='.rawurlencode($message);
    }

    public function downloadBebasPustaka(Request $request, RepositoryDocument $document)
    {
        // Authorization check
        $user = $request->user();
        $isOwner = $user && $user->id === $document->user_id;
        $isAdmin = $user && $user->role === 'admin';
        $hasValidToken = $request->filled('token') && $document->submission_token && hash_equals($document->submission_token, $request->query('token'));

        abort_if(! $isOwner && ! $isAdmin && ! $hasValidToken, 403, 'Anda tidak memiliki akses untuk mengunduh kartu bebas pustaka ini.');

        // Prerequisite check (admin can bypass for inspection purposes)
        if (! $isAdmin) {
            $blockers = $document->bebasPustakaBlockers();
            if (! empty($blockers)) {
                return redirect()
                    ->route('mahasiswa.dashboard')
                    ->withErrors(['bebas_pustaka' => 'Kartu Bebas Pustaka belum dapat diunduh. Syarat yang belum terpenuhi: ' . implode(' | ', $blockers)])
                    ->with('bebas_pustaka_blockers', $blockers);
            }
        }


        // Generate PDF using FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(20, 20, 20);

        // Header Logo
        $logoPath = public_path('assets/metamedia.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 20, 18, 22);
        }

        // Header Text
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(48, 18);
        $pdf->Cell(0, 6, 'UNIVERSITAS METAMEDIA', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(48);
        $pdf->Cell(0, 5, 'Jl. Khatib Sulaiman Dalam No.1, Padang Sumatera Barat', 0, 1, 'L');
        $pdf->SetX(48);
        $pdf->Cell(0, 5, 'Telp. (0751) 7056199', 0, 1, 'L');

        // Double Line
        $pdf->SetLineWidth(0.6);
        $pdf->Line(20, 36, 190, 36);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(20, 37.5, 190, 37.5);

        // Title
        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'SURAT KETERANGAN BEBAS PUSTAKA', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 4, 'Nomor: ' . date('Y') . '/BP/' . str_pad($document->id, 5, '0', STR_PAD_LEFT), 0, 1, 'C');

        // Body Intro
        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, 'Kepala UPT Perpustakaan Universitas Metamedia menerangkan bahwa yang tersebut di bawah ini:', 0, 'L');
        $pdf->Ln(4);

        // Details Table
        $leftCol = 45;
        $midCol = 5;

        $pdf->Cell($leftCol, 6, 'Nama', 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, $document->nama, 0, 1);
        $pdf->SetFont('Arial', '', 11);

        $identityLabel = $document->nim ? 'NIM / No. BP' : 'NIDN';
        $identityValue = $document->nim ?: $document->nidn ?: '-';
        $pdf->Cell($leftCol, 6, $identityLabel, 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $identityValue, 0, 1);

        $pdf->Cell($leftCol, 6, 'Program Studi', 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $document->programStudi?->nama ?: '-', 0, 1);

        $pdf->Cell($leftCol, 6, 'Kategori Dokumen', 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        $pdf->Cell(0, 6, strtoupper($document->kategori ?? ''), 0, 1);

        $pdf->Cell($leftCol, 6, 'Judul Dokumen', 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        // MultiCell for long titles
        $pdf->MultiCell(0, 6, $document->judul, 0, 'L');

        // Keperluan
        $pdf->SetY($pdf->GetY() + 2);
        $pdf->Cell($leftCol, 6, 'Keperluan', 0, 0);
        $pdf->Cell($midCol, 6, ':', 0, 0);
        $keperluan = 'Pendaftaran Wisuda / Pengambilan Ijazah / Pengunduran Diri';
        $pdf->Cell(0, 6, $keperluan, 0, 1);

        // Statement
        $pdf->Ln(6);
        $pdf->MultiCell(0, 6, 'Telah menyelesaikan segala urusan dengan Perpustakaan Universitas Metamedia sebagai berikut:', 0, 'L');
        $pdf->Ln(2);

        $pdf->SetX(20);
        $pdf->Cell(8, 6, '1.', 0, 0);
        $pdf->SetX(28);
        $pdf->MultiCell(0, 6, 'Mengembalikan semua koleksi buku/pustaka yang dipinjam.', 0, 'L');

        $pdf->Ln(2);
        $pdf->SetX(20);
        $pdf->Cell(8, 6, '2.', 0, 0);
        $pdf->SetX(28);
        $pdf->MultiCell(0, 6, 'Menyerahkan ' . strtolower($document->kategori ?? '') . ' dalam bentuk hard copy dan soft copy ke perpustakaan (khusus untuk soft copynya) dokumen ' . strtoupper($document->kategori ?? '') . ' yang telah diunggah ke sistem repository.', 0, 'L');

        // Closing
        $pdf->Ln(6);
        $pdf->MultiCell(0, 6, 'Demikianlah surat keterangan ini diberikan untuk dapat dipergunakan oleh yang bersangkutan sesuai dengan keperluan.', 0, 'L');

        // Signature
        $pdf->Ln(12);

        // Get Indonesian Date
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $d = date('j');
        $m = $months[(int)date('n')];
        $y = date('Y');
        $formattedDate = "$d $m $y";

        $pdf->SetX(120);
        $pdf->Cell(0, 6, 'Padang, ' . $formattedDate, 0, 1, 'L');
        $pdf->SetX(120);
        $pdf->Cell(0, 6, 'Staf Perpustakaan,', 0, 1, 'L');
        $pdf->Ln(18);
        $pdf->SetX(120);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, 'UPT Perpustakaan', 0, 1, 'L');

        // Output PDF
        $filename = 'kartu-bebas-pustaka-' . Str::slug($document->nama) . '.pdf';
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
