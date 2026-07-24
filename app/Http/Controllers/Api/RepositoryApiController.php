<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RepositoryDocument;
use Illuminate\Http\Request;

class RepositoryApiController extends Controller
{
    public function stats()
    {
        return response()->json([
            'data' => collect(['skripsi', 'magang', 'pkm', 'penelitian'])->mapWithKeys(fn ($kategori) => [
                $kategori => [
                    'total' => RepositoryDocument::where('kategori', $kategori)->count(),
                    'pending' => RepositoryDocument::where('kategori', $kategori)->where('status', 'pending')->count(),
                    'terverifikasi' => RepositoryDocument::where('kategori', $kategori)->where('status', 'terverifikasi')->count(),
                ],
            ]),
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->query('q');

        $documents = RepositoryDocument::query()
            ->when($search, function ($query, string $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(20)
            ->get();

        return response()->json(['data' => $documents]);
    }
}
