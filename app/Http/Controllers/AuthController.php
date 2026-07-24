<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'role' => ['nullable', Rule::in(['mahasiswa', 'dosen'])],
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['identifier'])
            ->orWhere('nim', $credentials['identifier'])
            ->orWhere('nidn', $credentials['identifier'])
            ->first();

        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Email/NIM/NIDN atau password tidak sesuai.'])->onlyInput('identifier');
        }

        $request->session()->regenerate();

        if ($user->status_akun !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'login' => 'Akun Anda masih menunggu verifikasi Admin. Silakan tunggu atau hubungi Admin.',
            ]);
        }

        return redirect()->route($user->role . '.dashboard');
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
