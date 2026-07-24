<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepositoryDocumentAccess extends Model
{
    use HasFactory;

    protected $table = 'repository_document_accesses';

    protected $fillable = [
        'repository_document_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function document()
    {
        return $this->belongsTo(RepositoryDocument::class, 'repository_document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
