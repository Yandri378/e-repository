<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\RepositorySetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        // Verify user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access to admin dashboard');
        }

        $statusCounts = RepositoryDocument::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $roleCounts = collect([
            'mahasiswa' => RepositoryDocument::whereIn('kategori', ['skripsi', 'magang'])->distinct('nama')->count('nama'),
            'dosen' => RepositoryDocument::whereIn('kategori', ['pkm', 'penelitian'])->distinct('nama')->count('nama'),
        ]);

        return view('dashboards.admin', [
            'pendingUsers' => User::where('status_akun', 'menunggu_verifikasi')->count(),
            'latestPendingUsers' => User::with('programStudi')
                ->whereIn('role', ['mahasiswa', 'dosen'])
                ->where('status_akun', 'menunggu_verifikasi')
                ->latest()
                ->limit(5)
                ->get(),
            'pendingDocuments' => RepositoryDocument::where('status', 'pending')->count(),
            'totalDocuments' => RepositoryDocument::count(),
            'totalUsers' => RepositoryDocument::distinct('nama')->count('nama'),
            'totalMahasiswa' => RepositoryDocument::whereIn('kategori', ['skripsi', 'magang'])->distinct('nama')->count('nama'),
            'totalDosen' => RepositoryDocument::whereIn('kategori', ['pkm', 'penelitian'])->distinct('nama')->count('nama'),
            'totalTa' => RepositoryDocument::where('kategori', 'skripsi')->count(),
            'totalMagang' => RepositoryDocument::where('kategori', 'magang')->count(),
            'statusCounts' => $statusCounts,
            'roleCounts' => $roleCounts,
            'documentsThisMonth' => RepositoryDocument::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'verifiedThisMonth' => RepositoryDocument::where('status', 'terverifikasi')
                ->whereMonth('verified_at', now()->month)
                ->whereYear('verified_at', now()->year)
                ->count(),
            'latestPendingDocuments' => RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen'])
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get(),
            'recentDocuments' => RepositoryDocument::with(['owner', 'programStudi'])
                ->latest()
                ->limit(6)
                ->get(),
            'programDistribution' => ProgramStudi::query()
                ->withCount('documents')
                ->orderByDesc('documents_count')
                ->orderBy('nama')
                ->limit(6)
                ->get(),
            'uploadStatuses' => RepositorySetting::uploadStatuses(),
            'cards' => $this->cards(),
        ]);
    }

    public function dosen()
    {
        // Verify user is dosen
        if (Auth::user()->role !== 'dosen') {
            abort(403, 'Unauthorized access to dosen dashboard');
        }

        return view('dashboards.dosen', [
            'documents' => RepositoryDocument::where('user_id', Auth::id())->latest()->limit(8)->get(),
            'approvalDocuments' => RepositoryDocument::with(['owner', 'programStudi', 'jenisDokumen'])
                ->where('dosen_pembimbing_id', Auth::id())
                ->where('status', 'pending')
                ->latest()
                ->limit(8)
                ->get(),
            'uploadStatuses' => RepositorySetting::uploadStatuses(),
            'cards' => $this->cards(Auth::id()),
        ]);
    }

    public function mahasiswa()
    {
        // Verify user is mahasiswa
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Unauthorized access to mahasiswa dashboard');
        }

        return view('dashboards.mahasiswa', [
            'documents' => RepositoryDocument::where('user_id', Auth::id())->latest()->limit(8)->get(),
            'uploadStatuses' => RepositorySetting::uploadStatuses(),
            'cards' => $this->cards(Auth::id()),
        ]);
    }

    private function cards(?int $userId = null): array
    {
        return collect(['skripsi', 'magang', 'pkm', 'penelitian'])
            ->mapWithKeys(function (string $kategori) use ($userId) {
                $query = RepositoryDocument::where('kategori', $kategori)
                    ->when($userId, fn ($builder) => $builder->where('user_id', $userId));

                return [$kategori => [
                    'total' => (clone $query)->count(),
                    'bulan_ini' => (clone $query)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                ]];
            })
            ->all();
    }
}
