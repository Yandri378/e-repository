<?php

namespace App\Http\Middleware;

use App\Models\DailyVisitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackDailyVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya track request GET browser umum (bukan live stream pdf, asset, ajax, bot probe)
        if ($request->isMethod('GET')
            && ! $request->ajax()
            && ! $request->is('stream/*', 'download/*', 'api/*', 'up', '_debugbar/*')
            && $request->hasSession()
        ) {
            try {
                DailyVisitor::recordVisit($request);
            } catch (\Throwable $e) {
                // Non-blocking log/silence
            }
        }

        return $next($request);
    }
}
