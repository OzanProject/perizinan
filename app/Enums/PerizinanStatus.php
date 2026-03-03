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

  public function description(): string
  {
    return match ($this) {
      self::DRAFT => 'Pengajuan masih dalam draf dan belum dikirim.',
      self::DIAJUKAN => 'Sedang dalam proses tinjauan administratif oleh petugas.',
      self::PERBAIKAN => 'Perlu perbaikan dokumen. Silakan cek catatan perbaikan di dashboard.',
      self::DISETUJUI => 'Pengajuan telah disetujui. Sedang dalam proses penomoran dan pencetakan sertifikat.',
      self::SIAP_DIAMBIL => 'Sertifikat/Izin sudah selesai dan siap diambil di kantor dinas.',
      self::SELESAI => 'Proses perizinan telah selesai sepenuhnya.',
      self::DITOLAK => 'Mohon maaf, pengajuan Anda ditolak. Silakan cek alasan penolakan di dashboard.',
    };
  }
}
