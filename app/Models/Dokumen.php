<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
  use HasFactory;

  protected $fillable = [
    'perizinan_id',
    'syarat_perizinan_id',
    'nama_file',
    'path',
  ];

  public function perizinan()
  {
    return $this->belongsTo(Perizinan::class);
  }

  public function syarat()
  {
    return $this->belongsTo(SyaratPerizinan::class, 'syarat_perizinan_id');
  }
}
