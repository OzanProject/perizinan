<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
  use HasFactory;

  protected $fillable = [
    'nama',
    'app_name',
    'logo',
    'stempel_img',
    'kode_surat',
    'alamat',
    'provinsi',
    'kota',
    'pimpinan_nama',
    'pimpinan_jabatan',
    'pimpinan_pangkat',
    'pimpinan_nip',
    'kabupaten',
    'footer_text',
    'watermark_img',
    'watermark_enabled',
    'watermark_opacity',
    'watermark_size',
  ];

  protected $casts = [
    'watermark_enabled' => 'boolean',
    'watermark_opacity' => 'float',
    'watermark_size' => 'integer',
  ];

  public function lembagas()
  {
    return $this->hasMany(Lembaga::class);
  }

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function jenisPerizinans()
  {
    return $this->hasMany(JenisPerizinan::class);
  }
}
