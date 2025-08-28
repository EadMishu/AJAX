<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Optional: specify the table name if it's not the plural of the model name
    protected $table = 'images';

    // Optional: define which fields can be mass-assigned
    protected $fillable = [
        'name',
        'phone',
        'number',
        'image',
    ];
}
