<?php

namespace App\Http\Controllers;

use App\Models\RepositoryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenApprovalController extends Controller
{
    public function index()
    {
        $documents = RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen'])
            ->where('dosen_pembimbing_id', Auth::id())
            ->whereIn('kategori', ['skripsi', 'magang'])
            ->latest()
            ->paginate(10);

        return view('dosen.approvals.index', compact('documents'));
    }

    public function approve(Request $request, RepositoryDocument $document)
    {
        $this->authorizeDocument($document);

        $data = $request->validate([
            'catatan_dosen' => ['nullable', 'string', 'max:500'],
        ]);

        $document->update([
            'dosen_approved_by' => Auth::id(),
            'dosen_approved_at' => now(),
            'catatan_dosen' => $data['catatan_dosen'] ?? null,
        ]);

        return back()->with('status', 'Dokumen "'.$document->judul.'" berhasil di-ACC dan masuk antrean verifikasi admin.');
    }

    public function reject(Request $request, RepositoryDocument $document)
    {
        $this->authorizeDocument($document);

        $data = $request->validate([
            'catatan_dosen' => ['nullable', 'string', 'max:500'],
        ]);

        $document->update([
            'status' => 'ditolak',
            'dosen_approved_by' => null,
            'dosen_approved_at' => null,
            'catatan_dosen' => $data['catatan_dosen'] ?? 'Dokumen ditolak oleh dosen pembimbing.',
        ]);

        return back()->with('status', 'Dokumen "'.$document->judul.'" ditolak oleh dosen pembimbing.');
    }

    private function authorizeDocument(RepositoryDocument $document): void
    {
        abort_if($document->dosen_pembimbing_id !== Auth::id(), 403, 'Dokumen ini bukan bimbingan Anda.');
        abort_if(! in_array($document->kategori, ['skripsi', 'magang'], true), 403, 'Hanya dokumen mahasiswa yang perlu ACC dosen.');
        abort_if($document->status !== 'pending', 403, 'Dokumen ini tidak sedang menunggu ACC.');
    }
}
