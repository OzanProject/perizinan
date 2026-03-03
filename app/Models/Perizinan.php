<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PerizinanStatus;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Perizinan extends Model
{
  use HasFactory;

  public $allowImmutableUpdate = false;

  protected static function boot()
  {
    parent::boot();

    static::updating(function ($perizinan) {
      if ($perizinan->allowImmutableUpdate)
        return;

      if (in_array($perizinan->getOriginal('status'), [PerizinanStatus::SIAP_DIAMBIL->value, PerizinanStatus::SELESAI->value])) {
        $protected = ['snapshot_html', 'document_hash', 'pdf_path', 'nomor_surat'];
        foreach ($protected as $field) {
          if ($perizinan->isDirty($field)) {
            throw new \Exception("Gagal: Kolom [{$field}] tidak dapat diubah karena dokumen sudah bersifat Immutable.");
          }
        }
      }
    });
  }

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
    'snapshot_html',
    'document_hash',
    'pdf_path',
  ];

  protected $casts = [
    'perizinan_data' => 'array',
    'tanggal_terbit' => 'datetime',
    'approved_at' => 'datetime',
    'ready_at' => 'datetime',
    'taken_at' => 'datetime',
  ];

  // Relasi
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

  /**
   * Engine Utama Pengganti Variabel
   */
  public function replaceVariables()
  {
    $template = $this->jenisPerizinan->template_html;
    $formConfig = $this->jenisPerizinan->form_config;
    $data = $this->perizinan_data ?? [];

    if (!$template || trim(strip_tags($template)) == '') {
      return $this->generateDefaultTable();
    }

    $dinas = $this->dinas;
    $lembaga = $this->lembaga;

    // Helper Base64 yang lebih tangguh untuk Hosting
    $toBase64 = function ($path) {
      if (!$path)
        return '';

      // Bersihkan path dari prefix storage jika ada
      $cleanPath = str_replace(['/storage/', 'storage/'], '', $path);
      $fullPath = storage_path('app/public/' . $cleanPath);

      if (!file_exists($fullPath) || is_dir($fullPath)) {
        return '';
      }

      try {
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($fullPath);
        return 'data:image/' . ($type ?: 'png') . ';base64,' . base64_encode($imageData);
      } catch (\Exception $e) {
        return '';
      }
    };

    // 1. Variabel Global (Gunakan str_ireplace agar tidak peka huruf besar/kecil)
    $globalVars = [
      '[NOMOR_SURAT]' => $this->nomor_surat ?? '............................',
      '[TANGGAL_TERBIT]' => $this->tanggal_terbit ? $this->tanggal_terbit->translatedFormat('d F Y') : '............................',
      '[JENIS_IZIN]' => $this->jenisPerizinan->nama,
      '[NAMA_LEMBAGA]' => $lembaga->nama_lembaga ?: $lembaga->nama,
      '[NPSN]' => $lembaga->npsn,
      '[ALAMAT_LEMBAGA]' => $lembaga->alamat,
      '[KOTA_DINAS]' => $dinas->kabupaten ?? 'Garut',
      '[PROVINSI_DINAS]' => $dinas->provinsi ?? 'Jawa Barat',
      '[ALAMAT_DINAS]' => $dinas->alamat ?: 'Jl. Jenderal Sudirman No. 1, ' . ($dinas->kabupaten ?? 'Garut'),
      '[MASA_BERLAKU]' => $this->jenisPerizinan->masa_berlaku_nilai . ' ' . $this->jenisPerizinan->masa_berlaku_unit,
      '[PIMPINAN_NAMA]' => $this->pimpinan_nama ?: ($dinas->pimpinan_nama ?: '............................'),
      '[PIMPINAN_JABATAN]' => $this->pimpinan_jabatan ?: ($dinas->pimpinan_jabatan ?: 'KEPALA DINAS PENDIDIKAN'),
      '[PIMPINAN_PANGKAT]' => $this->pimpinan_pangkat ?: ($dinas->pimpinan_pangkat ?? ''),
      '[PIMPINAN_NIP]' => $this->pimpinan_nip ?: ($dinas->pimpinan_nip ?: '............................'),

      // Gambar — base64 agar bisa dirender oleh dompdf
      '[LOGO_DINAS]' => '<img src="' . $toBase64($dinas->logo) . '" width="70" height="70" style="display:inline-block; max-width:100%;">',
      '[WATERMARK_LOGO]' => '<img src="' . $toBase64($dinas->watermark_img ?: $dinas->logo) . '" width="70" height="70" style="display:inline-block; max-width:100%;">',
      '[STEMPEL_DINAS]' => '<img src="' . $toBase64($this->stempel_img ?? $dinas->stempel_img) . '" width="80" height="80" style="display:inline-block; max-width:100%;">',
    ];

    // TTL dinamis jika ada
    $tempatLahir = $data['tempat_lahir'] ?? null;
    $tglLahir = $data['tanggal_lahir'] ?? null;
    if ($tempatLahir && $tglLahir) {
      $globalVars['[TTL]'] = "{$tempatLahir}, " . Carbon::parse($tglLahir)->translatedFormat('d F Y');
    }

    // Auto-repair: key umum di form yang sering muncul langsung
    $commonKeys = ['NAMA', 'PENDIDIKAN', 'JABATAN', 'UNIT_KERJA'];
    foreach ($commonKeys as $ck) {
      if (isset($data[strtolower($ck)])) {
        $globalVars["[{$ck}]"] = $data[strtolower($ck)];
      }
    }

    foreach ($globalVars as $key => $val) {
      $template = str_ireplace($key, $val, $template);
    }

    // === AUTO-REPAIR: Konversi img URL /storage/ yang masih tersisa ke base64 ===
    // Menangani template lama yang tersimpan dengan URL img (bukan placeholder)
    if (str_contains($template, '/storage/') && str_contains($template, '<img')) {
      $template = preg_replace_callback(
        '/<img([^>]*)src=["\']([^"\']*\/storage\/[^"\']+)["\']([^>]*)>/i',
        function ($matches) use ($toBase64) {
          $storagePath = preg_replace('/^.*\/storage\//', '', $matches[2]);
          $base64Src = $toBase64($storagePath);
          return '<img' . $matches[1] . 'src="' . $base64Src . '"' . $matches[3] . '>';
        },
        $template
      );
    }

    // 2. Mapping Data Dinamis dari Form
    if (is_array($formConfig)) {
      foreach ($formConfig as $field) {
        $key = $field['name'] ?? '';
        $val = $data[$key] ?? '................';
        if (is_array($val))
          $val = implode(', ', $val);

        $template = str_ireplace('[DATA:' . strtoupper($key) . ']', $val, $template);
        $template = str_ireplace('[DATA:' . $key . ']', $val, $template);
        $template = str_ireplace('[' . strtoupper($key) . ']', $val, $template);
      }
    }

    // 3. Bersihkan sisa placeholder [DATA:xxx] yang tidak terpetakan
    $template = preg_replace('/\[DATA:[^\]]+\]/i', '................', $template);

    // 4. Watermark tengah + Bingkai/Border
    if ($dinas->watermark_enabled ?? true) {
      $wmOpacity = $dinas->watermark_opacity ?? 0.08;
      $wmSize = $dinas->watermark_size ?? 400;
      $halfSize = $wmSize / 2;

      // Layer 1: Watermark logo di tengah
      $wmImg = $dinas->watermark_img ?: $dinas->logo;
      if ($wmImg) {
        $wmBase64 = $toBase64($wmImg);
        if ($wmBase64) {
          $template .= '
          <div style="position: fixed; top: 50%; left: 50%;
                      width: ' . $wmSize . 'px; height: ' . $wmSize . 'px;
                      margin-top: -' . $halfSize . 'px; margin-left: -' . $halfSize . 'px;
                      opacity: ' . $wmOpacity . '; z-index: -1;">
            <img src="' . $wmBase64 . '" width="' . $wmSize . '" height="' . $wmSize . '" style="object-fit:contain;" />
          </div>';
        }
      }

      // Layer 2: Bingkai/frame full-page
      $useBorder = $this->jenisPerizinan->use_border ?? false;
      if ($useBorder) {
        $namaIzin = strtolower($this->jenisPerizinan->nama ?? '');
        $borderType = $this->jenisPerizinan->border_type;
        $borderPath = $dinas->watermark_border_img;

        if ($borderType === 'paud') {
          $borderPath = $dinas->watermark_border_paud_img ?: 'images/bingkai-paud.jpg';
        } elseif ($borderType === 'pkbm') {
          $borderPath = 'images/bingkai-pkbm.jpg';
        } elseif ($borderType === 'default') {
          $borderPath = $dinas->watermark_border_img ?: 'images/default-border.png';
        } else {
          if (str_contains($namaIzin, 'paud') || str_contains($namaIzin, 'tk')) {
            $borderPath = $dinas->watermark_border_paud_img ?: 'images/bingkai-paud.jpg';
          } elseif (str_contains($namaIzin, 'pkbm')) {
            $borderPath = 'images/bingkai-pkbm.jpg';
          }
        }

        if ($borderPath) {
          $isPublic = !str_starts_with($borderPath, 'watermarks/')
            && !str_starts_with($borderPath, 'logos/')
            && !str_starts_with($borderPath, 'stempels/');

          if ($isPublic) {
            $fullBorderPath = public_path($borderPath);
            if (file_exists($fullBorderPath) && !is_dir($fullBorderPath)) {
              $type = pathinfo($fullBorderPath, PATHINFO_EXTENSION);
              $borderBase64 = 'data:image/' . ($type ?: 'jpg') . ';base64,' . base64_encode(file_get_contents($fullBorderPath));
            } else {
              $borderBase64 = null;
            }
          } else {
            $borderBase64 = $toBase64($borderPath);
          }

          if ($borderBase64) {
            $borderOpacity = $dinas->watermark_border_opacity ?? 0.2;
            $template .= '
            <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                        width: 100%; height: 100%;
                        opacity: ' . $borderOpacity . '; z-index: -2;">
              <img src="' . $borderBase64 . '" width="100%" height="100%" style="object-fit:cover;" />
            </div>';
          }
        }
      }
    }

    return $template;
  }

  private function generateDefaultTable()
  {
    return "<h2>" . $this->jenisPerizinan->nama . "</h2><p>Template belum dikonfigurasi.</p>";
  }
}