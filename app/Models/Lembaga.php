<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
  use HasFactory;

  protected $fillable = [
    'dinas_id',
    'nama_lembaga',
    'logo',
    'sampul',
    'jenjang',
    'npsn',
    'alamat',
    'sk_pendirian',
    'tanggal_sk_pendirian',
    'sk_izin_operasional',
    'masa_berlaku_izin',
    'akreditasi',
    'visi',
    'telepon',
    'email',
    'website',
    'status_kepemilikan',
  ];

  protected $casts = [
    'tanggal_sk_pendirian' => 'date',
    'masa_berlaku_izin' => 'date',
  ];

  public function dinas()
  {
    return $this->belongsTo(Dinas::class);
  }

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function getNamaAttribute()
  {
    return $this->nama_lembaga;
  }

  public function perizinans()
  {
    return $this->hasMany(Perizinan::class);
  }
}
