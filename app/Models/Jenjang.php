<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    protected $fillable = ['nama', 'seksi', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
