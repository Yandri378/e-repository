<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * FIXED VERSION: EnsureAccountIsActive
 * 
 * Now checks account status for ALL roles, including admin
 * This prevents unverified admins from accessing the system
 */
class EnsureAccountIsActiveFixed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // CHECK STATUS FOR ALL ROLES - including admin
        if ($user->status_akun !== 'aktif') {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Akun Anda masih menunggu verifikasi Admin. Silakan tunggu atau hubungi Admin.',
                ]);
        }

        return $next($request);
    }
}
