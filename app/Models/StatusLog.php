<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'perizinan_id',
    'from_status',
    'to_status',
    'changed_by',
    'catatan',
  ];

  public function perizinan()
  {
    return $this->belongsTo(Perizinan::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'changed_by');
  }
}
