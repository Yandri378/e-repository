<?php

namespace App\Http\Controllers;

use App\Events\RepositorySettingUpdated;
use App\Models\RepositorySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSettingController extends Controller
{
    private const UPLOAD_CATEGORIES = ['skripsi', 'magang', 'pkm', 'penelitian'];

    public function updateUploadSession(Request $request)
    {
        $data = $request->validate([
            'kategori' => ['required', Rule::in([...self::UPLOAD_CATEGORIES, 'all'])],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);

        $categories = $data['kategori'] === 'all'
            ? self::UPLOAD_CATEGORIES
            : [$data['kategori']];

        foreach ($categories as $category) {
            RepositorySetting::updateOrCreate(
                ['key' => 'upload_'.$category],
                ['value' => $data['status']]
            );

            try {
                event(new RepositorySettingUpdated($category, $data['status']));
            } catch (\Throwable $e) {
                // System works without broadcasting.
            }
        }

        if ($data['kategori'] === 'all') {
            $message = $data['status'] === 'open'
                ? 'Semua sesi upload berhasil dibuka.'
                : 'Semua sesi upload berhasil ditutup.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'statuses' => RepositorySetting::uploadStatuses(),
                ]);
            }

            return back()->with('status', $message);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sesi upload '.strtoupper($data['kategori']).' berhasil diperbarui.',
                'statuses' => RepositorySetting::uploadStatuses(),
            ]);
        }

        return back()->with('status', 'Sesi upload '.strtoupper($data['kategori']).' berhasil diperbarui.');
    }

    public function index()
    {
        $whatsapp = RepositorySetting::where('key', 'admin_whatsapp')->value('value') ?? '';
        $email = RepositorySetting::where('key', 'admin_email')->value('value') ?? '';

        return view('admin.settings.index', compact('whatsapp', 'email'));
    }

    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'admin_whatsapp' => ['nullable', 'string', 'max:32'],
            'admin_email' => ['nullable', 'email', 'max:255'],
        ]);

        RepositorySetting::updateOrCreate(
            ['key' => 'admin_whatsapp'],
            ['value' => $data['admin_whatsapp'] ?? '']
        );

        RepositorySetting::updateOrCreate(
            ['key' => 'admin_email'],
            ['value' => $data['admin_email'] ?? '']
        );

        try {
            event(new RepositorySettingUpdated('admin_contact', json_encode($data)));
        } catch (\Throwable $e) {
            // System works without broadcasting.
        }

        return back()->with('status', 'Kontak admin berhasil diperbarui.');
    }
}
