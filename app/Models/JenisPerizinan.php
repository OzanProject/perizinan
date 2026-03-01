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
    'use_border',
    'border_type',
    'form_config',
    'is_active',
  ];

  protected $casts = [
    'form_config' => 'array',
    'is_active' => 'boolean',
    'use_border' => 'boolean',
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

  /**
   * Validate that mandatory placeholders exist in the template
   */
  public function validateTemplate($html)
  {
    $mandatory = [
      '[NOMOR_SURAT]' => 'Nomor Surat',
      '[TANGGAL_TERBIT]' => 'Tanggal Terbit',
      '[PIMPINAN_NAMA]' => 'Nama Pimpinan',
    ];

    $missing = [];
    foreach ($mandatory as $placeholder => $label) {
      if (!str_contains($html, $placeholder)) {
        $missing[] = "{$label} ({$placeholder})";
      }
    }

    if (!empty($missing)) {
      throw new \Exception("Template tidak valid. Placeholder berikut wajib ada: " . implode(', ', $missing));
    }

    return true;
  }
}
