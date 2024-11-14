<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    protected $fillable = ['class', 'method', 'params', 'status', 'retries', 'error_message', 'priority', 'delay'];

    protected $casts = [
        'params' => 'array',
    ];
}
