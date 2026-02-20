<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SyaratPerizinan;

class JenisPerizinan extends Model
{
  use HasFactory;

  protected $fillable = [
    'dinas_id',
    'nama',
    'kode',
    'masa_berlaku_nilai',
    'masa_berlaku_unit',
    'deskripsi',
    'template_html',
    'form_config',
    'is_active',
  ];

  protected $casts = [
    'form_config' => 'array',
    'is_active' => 'boolean',
  ];

  public function dinas()
  {
    return $this->belongsTo(Dinas::class);
  }

  public function perizinans()
  {
    return $this->hasMany(Perizinan::class);
  }

  public function syarats()
  {
    return $this->hasMany(SyaratPerizinan::class);
  }
}
