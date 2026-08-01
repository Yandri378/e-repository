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
        $search = trim((string) $request->query('q', ''));
        $isSingleLetterSearch = strlen($search) === 1 && ctype_alpha($search);

        $documents = RepositoryDocument::query()
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
                        ->orWhere('nidn', 'like', $keyword);
                });
            })
            ->when($search !== '', function ($query) use ($search, $isSingleLetterSearch) {
                if (! $isSingleLetterSearch) {
                    $query->orderByRaw('CASE WHEN LOWER(judul) LIKE LOWER(?) THEN 0 ELSE 1 END', [$search.'%']);
                }

                $query->orderByRaw('LOWER(judul) ASC');
            }, fn ($query) => $query->latest())
            ->limit(20)
            ->get();

        return response()->json(['data' => $documents]);
    }
}
