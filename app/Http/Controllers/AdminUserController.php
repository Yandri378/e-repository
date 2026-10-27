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
        $statusFilter = $request->query('status', 'semua');
        $roleFilter = $request->query('role', 'semua');
        $search = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'terbaru');

        $inactiveThreshold = now()->subDays(30);

        $query = User::with('programStudi')
            ->whereIn('role', ['mahasiswa', 'dosen']);

        // Filter Role
        if (in_array($roleFilter, ['mahasiswa', 'dosen'], true)) {
            $query->where('role', $roleFilter);
        }

        // Filter Status
        if ($statusFilter === 'aktif') {
            $query->where('status_akun', 'aktif');
        } elseif ($statusFilter === 'nonaktif') {
            $query->where('status_akun', 'nonaktif');
        } elseif ($statusFilter === 'jarang_aktif') {
            // Akun yang berstatus aktif tapi tidak pernah login dalam 30 hari terakhir atau belum pernah login
            $query->where('status_akun', 'aktif')
                ->where(function ($q) use ($inactiveThreshold) {
                    $q->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', $inactiveThreshold);
                });
        }

        // Pencarian (Nama, Email, NIM, NIDN)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        // Pengurutan
        if ($sort === 'terlama_login') {
            $query->orderByRaw('last_login_at IS NULL DESC, last_login_at ASC');
        } elseif ($sort === 'terbaru_login') {
            $query->orderByRaw('last_login_at IS NULL, last_login_at DESC');
        } elseif ($sort === 'nama') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();

        // Hitung statistik untuk pemantauan
        $baseCountQuery = User::whereIn('role', ['mahasiswa', 'dosen']);
        $totalUsers = (clone $baseCountQuery)->count();
        $activeCount = (clone $baseCountQuery)->where('status_akun', 'aktif')->count();
        $inactiveCount = (clone $baseCountQuery)->where('status_akun', 'nonaktif')->count();
        $rarelyActiveCount = (clone $baseCountQuery)
            ->where('status_akun', 'aktif')
            ->where(function ($q) use ($inactiveThreshold) {
                $q->whereNull('last_login_at')
                  ->orWhere('last_login_at', '<', $inactiveThreshold);
            })->count();

        $stats = [
            'total' => $totalUsers,
            'aktif' => $activeCount,
            'nonaktif' => $inactiveCount,
            'jarang_aktif' => $rarelyActiveCount,
        ];

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();

        return view('admin.users.index', compact(
            'users',
            'programStudi',
            'statusFilter',
            'roleFilter',
            'search',
            'sort',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $role = $request->validate([
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
        ])['role'];

        $identityField = $role === 'mahasiswa' ? 'nim' : 'nidn';

        $rules = [
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
            'name' => ['required', 'string', 'max:255'],
            'program_studi_id' => ['required', 'exists:program_studi,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'confirmed', 'min:8'],
            $identityField => ['required', 'string', 'max:30', 'unique:users,'.$identityField],
        ];

        $messages = [
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'program_studi_id.required' => 'Program studi wajib dipilih.',
            'program_studi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar pada akun pengguna lain.',
            'nim.required' => 'NIM Mahasiswa wajib diisi.',
            'nim.unique' => 'NIM Mahasiswa ini sudah terdaftar pada akun pengguna lain.',
            'nidn.required' => 'NIDN Dosen wajib diisi.',
            'nidn.unique' => 'NIDN Dosen ini sudah terdaftar pada akun pengguna lain.',
            'password.required' => 'Password awal wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan Password Awal.',
        ];

        $data = $request->validate($rules, $messages);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $role === 'mahasiswa' ? $data['nim'] : null,
            'nidn' => $role === 'dosen' ? $data['nidn'] : null,
            'role' => $role,
            'program_studi_id' => $data['program_studi_id'],
            'whatsapp' => $data['whatsapp'] ?? null,
            'password' => $data['password'],
            'status_akun' => 'aktif',
        ]);

        return back()->with('status', 'Akun '.$data['name'].' ('.ucfirst($role).') berhasil dibuat dan langsung aktif.');
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

    public function updatePassword(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 403, 'Password akun admin tidak dapat diubah dari halaman ini.');

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => $data['password'],
        ]);

        return back()->with('status', 'Password untuk akun '.$user->name.' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 403, 'Akun admin tidak dapat dihapus dari halaman ini.');

        $name = $user->name;
        $user->delete();

        return back()->with('status', 'Akun '.$name.' berhasil dihapus.');
    }
}
