<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function pending()
    {
        $users = User::with('programStudi')
            ->whereIn('role', ['mahasiswa', 'dosen'])
            ->where('status_akun', 'menunggu_verifikasi')
            ->latest()
            ->paginate(10);

        return view('admin.users.pending', compact('users'));
    }

    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'aktif');
        $allowedFilters = ['aktif', 'nonaktif', 'semua'];

        if (! in_array($statusFilter, $allowedFilters, true)) {
            $statusFilter = 'aktif';
        }

        $users = User::with('programStudi')
            ->whereIn('role', ['mahasiswa', 'dosen'])
            ->when($statusFilter !== 'semua', fn ($query) => $query->where('status_akun', $statusFilter))
            ->when($statusFilter === 'semua', fn ($query) => $query->whereIn('status_akun', ['aktif', 'nonaktif']))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $userStatusCounts = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->whereIn('status_akun', ['aktif', 'nonaktif'])
            ->selectRaw('status_akun, count(*) as total')
            ->groupBy('status_akun')
            ->pluck('total', 'status_akun');

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();

        return view('admin.users.index', compact('users', 'programStudi', 'statusFilter', 'userStatusCounts'));
    }

    public function store(Request $request)
    {
        $role = $request->validate([
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
        ])['role'];

        $identityField = $role === 'mahasiswa' ? 'nim' : 'nidn';

        $data = $request->validate([
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
            'name' => ['required', 'string', 'max:255'],
            'program_studi_id' => ['required', 'exists:program_studi,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'confirmed', 'min:8'],
            $identityField => ['required', 'string', 'max:30', 'unique:users,'.$identityField],
        ]);

        User::create(array_merge($data, [
            'role' => $role,
            'status_akun' => 'aktif',
        ]));

        return back()->with('status', 'Akun '.$data['name'].' berhasil dibuat dan langsung aktif. Berikan Email/NIM/NIDN serta password awal kepada pengguna.');
    }

    public function updateStatus(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 403, 'Admin tidak diverifikasi dari halaman publik.');

        $data = $request->validate([
            'status_akun' => ['required', Rule::in(['aktif', 'ditolak', 'nonaktif'])],
            'alasan_penolakan' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'status_akun' => $data['status_akun'],
            'alasan_penolakan' => $data['status_akun'] === 'ditolak'
                ? ($data['alasan_penolakan'] ?? 'Ditolak oleh admin.')
                : null,
        ]);

        return back()->with('status', 'Status akun '.$user->name.' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 403, 'Akun admin tidak dapat dihapus dari halaman ini.');

        $name = $user->name;
        $user->delete();

        return back()->with('status', 'Akun '.$name.' berhasil dihapus.');
    }
}
