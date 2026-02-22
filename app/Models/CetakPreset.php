<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CetakPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinas_id',
        'nama',
        'paper_size',
        'orientation',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',
        'is_active',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }
}
