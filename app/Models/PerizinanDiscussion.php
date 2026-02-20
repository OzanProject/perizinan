<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerizinanDiscussion extends Model
{
  use HasFactory;

  protected $fillable = [
    'perizinan_id',
    'user_id',
    'message',
    'attachments',
  ];

  protected $casts = [
    'attachments' => 'array',
  ];

  public function perizinan()
  {
    return $this->belongsTo(Perizinan::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
