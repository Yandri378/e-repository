<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $request->session()->regenerateToken();

        return view('auth.login', ['role' => null]);
    }

    public function showLoginMahasiswa(Request $request)
    {
        $request->session()->regenerateToken();

        return view('auth.login', ['role' => 'mahasiswa']);
    }

    public function showLoginDosen(Request $request)
    {
        $request->session()->regenerateToken();

        return view('auth.login', ['role' => 'dosen']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'role' => ['nullable', Rule::in(['mahasiswa', 'dosen'])],
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cari user berdasarkan Email, NIM, atau NIDN di database
        $user = User::where('email', $credentials['identifier'])
            ->orWhere('nim', $credentials['identifier'])
            ->orWhere('nidn', $credentials['identifier'])
            ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()->withErrors(['login' => 'Email/NIM/NIDN tidak terdaftar.'])->onlyInput('identifier');
        }

        // Coba login dengan email user tersebut dan password yang diinput
        if (!Auth::attempt(['email' => $user->email, 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Password yang Anda masukkan salah.'])->onlyInput('identifier');
        }

        $request->session()->regenerate();

        // Cek status keaktifan akun
        if ($user->status_akun !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $msg = match ($user->status_akun) {
                'nonaktif' => 'Akun Anda berstatus nonaktif. Silakan hubungi Administrator untuk mengaktifkannya kembali.',
                'ditolak' => 'Akun Anda ditolak oleh Admin' . ($user->alasan_penolakan ? ': ' . $user->alasan_penolakan : '.'),
                default => 'Akun Anda tidak aktif. Silakan hubungi Administrator.',
            };

            return back()->withErrors([
                'login' => $msg,
            ])->onlyInput('identifier');
        }

        // Perbarui waktu login terakhir
        $user->update(['last_login_at' => now()]);

        // Cek role user dari database dan arahkan ke dashboard yang sesuai
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        } elseif ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika role tidak dikenali, redirect ke halaman utama
        return redirect()->route('home');
    }

    public function showRegister(?string $role = null)
    {
        abort_if($role && !in_array($role, ['mahasiswa', 'dosen'], true), 404);

        $programStudi = ProgramStudi::where('aktif', true)->orderBy('nama')->get();

        return view('auth.register', compact('role', 'programStudi'));
    }

    public function register(Request $request)
    {
        $role = $request->validate([
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
        ], [
            'role.required' => 'Pilih peran akun (Mahasiswa atau Dosen).',
            'role.in' => 'Peran akun tidak valid.',
        ])['role'];

        $identityField = $role === 'mahasiswa' ? 'nim' : 'nidn';

        $rules = [
            'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
            'name' => ['required', 'string', 'max:255'],
            'program_studi_id' => ['required', 'exists:program_studi,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            $identityField => ['required', 'string', 'max:30', 'unique:users,'.$identityField],
        ];

        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'program_studi_id.required' => 'Program studi wajib dipilih.',
            'program_studi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'nim.required' => 'NIM Mahasiswa wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'nidn.required' => 'NIDN Dosen wajib diisi.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];

        $data = $request->validate($rules, $messages);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $role === 'mahasiswa' ? $data['nim'] : null,
            'nidn' => $role === 'dosen' ? $data['nidn'] : null,
            'role' => $role,
            'program_studi_id' => $data['program_studi_id'],
            'whatsapp' => $data['whatsapp'] ?? null,
            'password' => $data['password'],
            'status_akun' => 'aktif',
            'last_login_at' => now(),
        ]);

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($role . '.dashboard')->with('status', 'Pendaftaran berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
