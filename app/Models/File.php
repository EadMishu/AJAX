<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'original_name',
        'name',
        'user_id',
        'size',
        'extension',
        'type',
        'external_link',
    ];
}
