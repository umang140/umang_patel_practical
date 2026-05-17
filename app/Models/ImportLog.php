<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'total_rows',
        'imported_count',
        'failed_count',
        'status',
        'errors',
    ];
    
    protected $casts = [
        'errors' => 'array',
    ];
}
