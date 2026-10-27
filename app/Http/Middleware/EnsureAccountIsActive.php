<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status_akun !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $msg = match ($user->status_akun) {
                'nonaktif' => 'Akun Anda berstatus nonaktif. Silakan hubungi Administrator untuk mengaktifkannya kembali.',
                'ditolak' => 'Akun Anda ditolak oleh Admin: ' . ($user->alasan_penolakan ?? 'Silakan hubungi Admin.'),
                default => 'Akun Anda tidak aktif. Silakan hubungi Administrator.',
            };

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => $msg,
                ]);
        }

        return $next($request);
    }
}
