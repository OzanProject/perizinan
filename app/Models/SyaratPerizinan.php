<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyaratPerizinan extends Model
{
    protected $fillable = [
        'jenis_perizinan_id',
        'nama_dokumen',
        'deskripsi',
        'tipe_file',
        'is_required',
    ];

    public function jenisPerizinan()
    {
        return $this->belongsTo(JenisPerizinan::class);
    }
}
