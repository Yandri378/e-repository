<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('programStudi');

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();

        $stats = [
            'total_uploads' => RepositoryDocument::where('user_id', $user->id)->count(),
            'pending_uploads' => RepositoryDocument::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved_uploads' => RepositoryDocument::where('user_id', $user->id)->where('status', 'terverifikasi')->count(),
        ];

        if ($user->role === 'dosen') {
            $stats['pending_bimbingan'] = RepositoryDocument::where('dosen_pembimbing_id', $user->id)
                ->where('status', 'pending')
                ->count();
        }

        $userDocuments = RepositoryDocument::with(['programStudi', 'jenisDokumen'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.index', compact('user', 'programStudi', 'stats', 'userDocuments'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password berhasil diperbarui.');
    }
}
