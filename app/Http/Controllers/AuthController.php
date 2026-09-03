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

            return back()->withErrors([
                'login' => 'Akun Anda masih menunggu verifikasi Admin. Silakan tunggu atau hubungi Admin.',
            ]);
        }

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
        return redirect()
            ->route('register', $request->input('role'))
            ->withErrors([
                'register' => 'Registrasi akun mahasiswa dan dosen dilakukan oleh Admin. Silakan minta username dan password awal ke Admin.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
