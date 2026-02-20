<?php

namespace App\Enums;

enum PerizinanStatus: string
{
  case DRAFT = 'draft';
  case DIAJUKAN = 'diajukan';
  case PERBAIKAN = 'perbaikan';
  case DISETUJUI = 'disetujui';
  case SIAP_DIAMBIL = 'siap_diambil';
  case SELESAI = 'selesai';
  case DITOLAK = 'ditolak';

  public function label(): string
  {
    return match ($this) {
      self::DRAFT => 'Draft',
      self::DIAJUKAN => 'Diajukan',
      self::PERBAIKAN => 'Perbaikan',
      self::DISETUJUI => 'Disetujui',
      self::SIAP_DIAMBIL => 'Siap Diambil',
      self::SELESAI => 'Selesai',
      self::DITOLAK => 'Ditolak',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::DRAFT => 'secondary',
      self::DIAJUKAN => 'primary',
      self::PERBAIKAN => 'warning',
      self::DISETUJUI => 'success',
      self::SIAP_DIAMBIL => 'info',
      self::SELESAI => 'dark',
      self::DITOLAK => 'danger',
    };
  }
}
