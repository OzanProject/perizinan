<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
  use HasFactory;

  protected $fillable = [
    'dinas_id',
    'lembaga_id',
    'jenis_perizinan_id',
    'perizinan_data',
    'keterangan_lembaga',
    'status',
    'nomor_surat',
    'tanggal_terbit',
    'pimpinan_nama',
    'pimpinan_jabatan',
    'pimpinan_pangkat',
    'pimpinan_nip',
    'stempel_img',
    'catatan_verifikator',
    'approved_at',
    'ready_at',
    'taken_at',
  ];

  protected $casts = [
    'perizinan_data' => 'array',
    'tanggal_terbit' => 'date',
    'approved_at' => 'datetime',
    'ready_at' => 'datetime',
    'taken_at' => 'datetime',
  ];

  public function dinas()
  {
    return $this->belongsTo(Dinas::class);
  }

  public function lembaga()
  {
    return $this->belongsTo(Lembaga::class);
  }

  public function jenisPerizinan()
  {
    return $this->belongsTo(JenisPerizinan::class);
  }

  public function dokumens()
  {
    return $this->hasMany(Dokumen::class);
  }

  public function statusLogs()
  {
    return $this->hasMany(StatusLog::class);
  }

  public function discussions()
  {
    return $this->hasMany(PerizinanDiscussion::class);
  }
}
