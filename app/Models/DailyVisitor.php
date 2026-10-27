<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyVisitor extends Model
{
    protected $fillable = [
        'visit_date',
        'session_id',
        'ip_address',
        'user_agent',
        'hits',
        'user_id',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'hits' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat kunjungan pengunjung untuk hari ini berdasarkan sesi unik.
     */
    public static function recordVisit($request): self
    {
        $today = now()->format('Y-m-d');
        $sessionId = $request->session()->getId();

        $visitor = static::whereDate('visit_date', $today)
            ->where('session_id', $sessionId)
            ->first();

        if ($visitor) {
            $visitor->increment('hits');
            if (! $visitor->user_id && auth()->check()) {
                $visitor->update(['user_id' => auth()->id()]);
            }
            return $visitor;
        }

        try {
            return static::create([
                'visit_date' => $today,
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'hits' => 1,
                'user_id' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            $existing = static::whereDate('visit_date', $today)
                ->where('session_id', $sessionId)
                ->first();

            if ($existing) {
                $existing->increment('hits');
                return $existing;
            }

            throw $e;
        }
    }

    /**
     * Jumlah pengunjung unik hari ini (otomatis 0 saat pergantian hari).
     */
    public static function todayCount(): int
    {
        return static::whereDate('visit_date', now()->format('Y-m-d'))->count();
    }

    /**
     * Total hits (akses halaman) hari ini.
     */
    public static function todayHits(): int
    {
        return (int) static::whereDate('visit_date', now()->format('Y-m-d'))->sum('hits');
    }

    /**
     * Jumlah pengunjung unik kemarin.
     */
    public static function yesterdayCount(): int
    {
        return static::whereDate('visit_date', now()->subDay()->format('Y-m-d'))->count();
    }

    /**
     * Total seluruh pengunjung unik sepanjang masa.
     */
    public static function totalCount(): int
    {
        return static::count();
    }

    /**
     * Laporan statistik pengunjung per hari untuk dashboard admin.
     */
    public static function dailyReport(int $limit = 30)
    {
        return static::query()
            ->selectRaw('DATE(visit_date) as visit_date, COUNT(*) as total_visitors, SUM(hits) as total_hits')
            ->groupByRaw('DATE(visit_date)')
            ->orderByRaw('DATE(visit_date) DESC')
            ->limit($limit)
            ->get();
    }
}
