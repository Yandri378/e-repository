<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepositoryDownloadRequest extends Model
{
    use HasFactory;

    protected $table = 'download_requests';

    protected $fillable = [
        'repository_document_id',
        'submission_token',
        'requester_email',
        'requester_phone',
        'message',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function document()
    {
        return $this->belongsTo(RepositoryDocument::class, 'repository_document_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
