<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositorySetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function uploadOpen(string $kategori): bool
    {
        return static::query()
            ->where('key', 'upload_'.$kategori)
            ->value('value') === 'open';
    }

    public static function uploadStatuses(): array
    {
        return collect(['skripsi', 'magang', 'pkm', 'penelitian'])
            ->mapWithKeys(fn (string $kategori) => [$kategori => static::uploadOpen($kategori)])
            ->all();
    }
}
