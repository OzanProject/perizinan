<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PerizinanStatus;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Perizinan extends Model
{
  use HasFactory;

  public $allowImmutableUpdate = false;

  // =========================================================================
  // BOOT
  // =========================================================================

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($perizinan) {
      if (empty($perizinan->qr_token)) {
        $perizinan->qr_token = Str::uuid()->toString();
      }
    });

    static::updating(function ($perizinan) {
      if ($perizinan->allowImmutableUpdate) return;

      if (in_array($perizinan->getOriginal('status'), [
        PerizinanStatus::SIAP_DIAMBIL->value,
        PerizinanStatus::SELESAI->value,
      ])) {
        $protected = ['snapshot_html', 'document_hash', 'pdf_path', 'nomor_surat'];
        foreach ($protected as $field) {
          if ($perizinan->isDirty($field)) {
            throw new \Exception("Gagal: Kolom [{$field}] tidak dapat diubah karena dokumen sudah bersifat Immutable.");
          }
        }
      }
    });
  }

  // =========================================================================
  // FILLABLE & CASTS
  // =========================================================================

  protected $fillable = [
    'dinas_id', 'lembaga_id', 'jenis_perizinan_id',
    'perizinan_data', 'keterangan_lembaga', 'status',
    'nomor_surat', 'tanggal_terbit',
    'pimpinan_nama', 'pimpinan_jabatan', 'pimpinan_pangkat', 'pimpinan_nip',
    'stempel_img', 'catatan_verifikator',
    'approved_at', 'ready_at', 'taken_at',
    'snapshot_html', 'document_hash', 'pdf_path',
  ];

  protected $casts = [
    'perizinan_data' => 'array',
    'tanggal_terbit' => 'datetime',
    'approved_at'    => 'datetime',
    'ready_at'       => 'datetime',
    'taken_at'       => 'datetime',
  ];

  // =========================================================================
  // RELASI
  // =========================================================================

  public function dinas()         { return $this->belongsTo(Dinas::class); }
  public function lembaga()       { return $this->belongsTo(Lembaga::class); }
  public function jenisPerizinan(){ return $this->belongsTo(JenisPerizinan::class); }
  public function discussions()   { return $this->hasMany(PerizinanDiscussion::class); }
  public function dokumens()      { return $this->hasMany(Dokumen::class); }

  // =========================================================================
  // ACCESSOR
  // =========================================================================

  public function getRenderedTemplateAttribute(): string
  {
    return $this->replaceVariables();
  }

  // =========================================================================
  // ENGINE UTAMA
  // =========================================================================

  public function replaceVariables(bool $wrap = true): string
  {
    $template   = $this->jenisPerizinan->template_html;
    $formConfig = $this->jenisPerizinan->form_config;
    $data       = $this->perizinan_data ?? [];

    if (!$template || trim(strip_tags($template)) === '') {
      return $this->wrapPrintableTemplate($this->generateDefaultTable());
    }

    $dinas   = $this->dinas;
    $lembaga = $this->lembaga;

    // ── Helper: path → base64 data URI ──────────────────────────────────────
    $toBase64 = function (?string $path): string {
      if (!$path) return '';

      $cleanPath = ltrim(preg_replace('#^.*?/storage/#', '', $path), '/');
      $fullPath  = storage_path('app/public/' . $cleanPath);

      if (!file_exists($fullPath) || is_dir($fullPath)) return '';

      try {
        $ext  = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) ?: 'png';
        $mime = in_array($ext, ['jpg', 'jpeg']) ? 'jpeg' : $ext;
        return 'data:image/' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
      } catch (\Exception) {
        return '';
      }
    };

    // ── QR Code (ukuran aman: 58x58px) ──────────────────────────────────────
    $qrImage      = '';
    $qrCodeBase64 = '';
    if ($this->qr_token) {
      $qrUrl        = route('perizinan.verify', $this->qr_token);
      $qrCodeSvg    = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(82)->margin(0)->generate($qrUrl);
      $qrCodeBase64 = base64_encode($qrCodeSvg);
      $qrImage      = '<img src="data:image/svg+xml;base64,' . $qrCodeBase64 . '"'
                    . ' width="58" height="58"'
                    . ' style="display:inline-block;width:58px;height:58px;">';
    }

    // ── Variabel global ──────────────────────────────────────────────────────
    $globalVars = [
      '[NOMOR_SURAT]'      => $this->nomor_surat ?? '............................',
      '[TANGGAL_TERBIT]'   => $this->tanggal_terbit
                               ? $this->tanggal_terbit->translatedFormat('d F Y')
                               : '............................',
      '[JENIS_IZIN]'       => $this->jenisPerizinan->nama,
      '[NAMA_LEMBAGA]'     => $lembaga->nama_lembaga ?: $lembaga->nama,
      '[NPSN]'             => $lembaga->npsn,
      '[ALAMAT_LEMBAGA]'   => $lembaga->alamat,
      '[KOTA_DINAS]'       => $dinas->kabupaten ?? 'Garut',
      '[PROVINSI_DINAS]'   => $dinas->provinsi  ?? 'Jawa Barat',
      '[ALAMAT_DINAS]'     => $dinas->alamat    ?: 'Jl. Jenderal Sudirman No. 1, ' . ($dinas->kabupaten ?? 'Garut'),
      '[MASA_BERLAKU]'     => $this->jenisPerizinan->masa_berlaku_nilai . ' ' . $this->jenisPerizinan->masa_berlaku_unit,
      '[PIMPINAN_NAMA]'    => $this->pimpinan_nama    ?: ($dinas->pimpinan_nama    ?: '............................'),
      '[PIMPINAN_JABATAN]' => $this->pimpinan_jabatan ?: ($dinas->pimpinan_jabatan ?: 'KEPALA DINAS PENDIDIKAN'),
      '[PIMPINAN_PANGKAT]' => $this->pimpinan_pangkat ?: ($dinas->pimpinan_pangkat ?? ''),
      '[PIMPINAN_NIP]'     => $this->pimpinan_nip     ?: ($dinas->pimpinan_nip     ?: '............................'),

      '[LOGO_DINAS]'       => $this->makeImg($toBase64($dinas->logo),                              '70px', '70px'),
      '[WATERMARK_LOGO]'   => $this->makeImg($toBase64($dinas->watermark_img ?: $dinas->logo),     '70px', '70px'),
      '[STEMPEL_DINAS]'    => $this->makeImg($toBase64($this->stempel_img   ?? $dinas->stempel_img),'80px', '80px'),
      '[QR_CODE]'          => $qrImage,
    ];

    // TTL dinamis
    if (($tl = $data['tempat_lahir'] ?? null) && ($tgl = $data['tanggal_lahir'] ?? null)) {
      $globalVars['[TTL]'] = $tl . ', ' . Carbon::parse($tgl)->translatedFormat('d F Y');
    }

    // Shortcut kunci umum
    foreach (['NAMA', 'PENDIDIKAN', 'JABATAN', 'UNIT_KERJA'] as $ck) {
      if (isset($data[strtolower($ck)])) {
        $globalVars["[{$ck}]"] = $data[strtolower($ck)];
      }
    }

    foreach ($globalVars as $key => $val) {
      $template = str_ireplace($key, $val, $template);
    }

    // ── Auto-repair: img src /storage/ → base64 ─────────────────────────────
    if (str_contains($template, '/storage/') && str_contains($template, '<img')) {
      $template = preg_replace_callback(
        '/<img([^>]*)src=["\']([^"\']*\/storage\/[^"\']+)["\']([^>]*)>/i',
        function ($m) use ($toBase64) {
          $src = $toBase64(preg_replace('/^.*\/storage\//', '', $m[2]));
          return $src ? '<img' . $m[1] . 'src="' . $src . '"' . $m[3] . '>' : '';
        },
        $template
      );
    }

    // ── Mapping data dinamis dari form config ────────────────────────────────
    if (is_array($formConfig)) {
      foreach ($formConfig as $field) {
        $key = $field['name'] ?? '';
        $val = $data[$key] ?? '................';
        if (is_array($val)) $val = implode(', ', $val);

        $upperKey = strtoupper($key);
        $template = str_ireplace('[DATA:' . $upperKey . ']', $val, $template);
        $template = str_ireplace('[DATA:' . $key . ']',      $val, $template);
        $template = str_ireplace('[' . $upperKey . ']',      $val, $template);
      }
    }

    // Bersihkan sisa placeholder [DATA:xxx]
    $template = preg_replace('/\[DATA:[^\]]+\]/i', '................', $template);

    // ── Watermark tengah ─────────────────────────────────────────────────────
    if ($dinas->watermark_enabled ?? true) {
      $wmSrc = $toBase64($dinas->watermark_img ?: $dinas->logo);
      if ($wmSrc) {
        $wmOpacity = number_format(max(0, min(1, $dinas->watermark_opacity ?? 0.07)), 2);
        $template .= '
          <div style="position:fixed;top:50%;left:50%;
                      transform:translate(-50%,-50%);
                      width:180mm;height:180mm;
                      opacity:' . $wmOpacity . ';z-index:-1;pointer-events:none;">
            <img src="' . $wmSrc . '" style="width:100%;height:100%;object-fit:contain;" alt="">
          </div>';
      }
    }

    // ── Border/bingkai halaman ───────────────────────────────────────────────
    if (($dinas->watermark_enabled ?? true) && ($this->jenisPerizinan->use_border ?? false)) {
      $borderPath = $this->resolveBorderPath($dinas);
      if ($borderPath) {
        $isPublicAsset = !preg_match('#^(watermarks|logos|stempels)/#', $borderPath);
        if ($isPublicAsset) {
          $fp = public_path($borderPath);
          if (file_exists($fp) && !is_dir($fp)) {
            $ext       = strtolower(pathinfo($fp, PATHINFO_EXTENSION)) ?: 'jpg';
            $borderSrc = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($fp));
          } else {
            $borderSrc = null;
          }
        } else {
          $borderSrc = $toBase64($borderPath);
        }

        if ($borderSrc) {
          $borderOpacity = number_format(max(0, min(1, $dinas->watermark_border_opacity ?? 0.2)), 2);
          // Beri jarak 4mm dari tepi agar tidak menutup isi
          $template .= '
            <div style="position:fixed;top:4mm;left:4mm;right:4mm;bottom:4mm;
                        opacity:' . $borderOpacity . ';z-index:-2;pointer-events:none;">
              <img src="' . $borderSrc . '" style="width:100%;height:100%;" alt="">
            </div>';
        }
      }
    }

    // ── QR floating (pojok kiri bawah, hanya jika belum ada di template) ────
    if ($qrImage && !str_contains($template, '[QR_CODE]') && !str_contains($template, $qrCodeBase64)) {
      $template .= '
        <div class="print-qr-floating">
          ' . $qrImage . '
          <span>Scan untuk<br>Verifikasi Asli</span>
        </div>';
    }

    if ($wrap) {
      return $this->wrapPrintableTemplate($template);
    }
    
    return $template;
  }

  // =========================================================================
  // WRAPPER TAMPILAN CETAK (A4, Dompdf-safe)
  // =========================================================================

  private function wrapPrintableTemplate(string $template): string
  {
    return '<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  @page {
    size: A4 portrait;
    margin: 11mm 13mm 11mm 13mm;
  }

  * { box-sizing: border-box; }

  html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    font-family: "Times New Roman", Times, serif;
    font-size: 11.2pt;
    line-height: 1.28;
    color: #000;
    background: #fff;
    overflow: hidden;
  }

  /* ── Pembungkus satu halaman ── */
  .print-page {
    position: relative;
    width: 100%;
    min-height: 268mm;
    max-height: 268mm;
    padding: 7mm 8mm 8mm 8mm;
    overflow: hidden;
    page-break-after: avoid;
    page-break-inside: avoid;
  }

  .print-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-height: 252mm;
    overflow: hidden;
  }

  /* ── Heading ── */
  .print-content h1,
  .print-content h2,
  .print-content h3,
  .print-content h4 {
    margin: 0 0 4px 0;
    padding: 0;
    line-height: 1.15;
    text-align: center;
    font-weight: bold;
  }
  .print-content h1 { font-size: 15pt;   text-transform: uppercase; }
  .print-content h2 { font-size: 13.5pt; text-transform: uppercase; }
  .print-content h3 { font-size: 12.2pt; }

  /* ── Paragraf ── */
  .print-content p {
    margin: 0 0 5px 0;
    padding: 0;
    text-align: justify;
  }

  /* ── Tabel: dipaksa tidak melebar ── */
  .print-content table {
    width: 100% !important;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 2px 0 5px 0;
    page-break-inside: avoid;
  }
  .print-content table td,
  .print-content table th {
    vertical-align: top;
    word-wrap: break-word;
    overflow-wrap: break-word;
    padding: 1.5px 3px;
    font-size: 11pt;
    line-height: 1.25;
  }

  /* ── Gambar: tidak boleh melebar ── */
  .print-content img { max-width: 100%; height: auto; }

  /* ── Garis ── */
  .print-content hr {
    margin: 4px 0 7px 0;
    border: 0;
    border-top: 1.5px solid #000;
  }

  /* ── List ── */
  .print-content ol,
  .print-content ul { margin: 2px 0 4px 0; padding-left: 18px; }
  .print-content li  { margin-bottom: 2px; text-align: justify; }

  /* ── Cegah page-break di blok penting ── */
  .print-content .kop-surat,
  .print-content .letterhead,
  .print-content .kop,
  .print-content .header,
  .print-content .signature,
  .print-content .ttd,
  .print-content .tanda-tangan,
  .print-content .avoid-break { page-break-inside: avoid; }

  /* ── Normalkan font-size besar dari template DB ── */
  .print-content [style*="font-size: 14pt"],
  .print-content [style*="font-size:14pt"]  { font-size: 12.4pt !important; }
  .print-content [style*="font-size: 16pt"],
  .print-content [style*="font-size:16pt"]  { font-size: 13pt   !important; }
  .print-content [style*="font-size: 18pt"],
  .print-content [style*="font-size:18pt"]  { font-size: 14pt   !important; }
  .print-content [style*="font-size: 20pt"],
  .print-content [style*="font-size:20pt"]  { font-size: 14.5pt !important; }

  /* ── QR floating: pojok kiri bawah, tidak ganggu isi ── */
  .print-qr-floating {
    position: fixed;
    left: 17mm;
    bottom: 15mm;
    z-index: 30;
    width: 24mm;
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 6.8pt;
    line-height: 1.05;
    color: #333;
  }
  .print-qr-floating img {
    width: 16mm !important;
    height: 16mm !important;
    display: block;
    margin: 0 auto 1.5mm auto;
  }
  .print-qr-floating span { display: block; margin-top: 1mm; }
</style>
</head>
<body>
  <div class="print-page">
    <div class="print-content">
      ' . $template . '
    </div>
  </div>
</body>
</html>';
  }

  // =========================================================================
  // HELPER METHODS
  // =========================================================================

  /** Buat <img> dengan ukuran eksplisit, object-fit: contain */
  private function makeImg(string $src, string $w = '70px', string $h = '70px'): string
  {
    if (!$src) return '';
    return '<img src="' . $src . '"'
         . ' style="width:' . $w . ';height:' . $h . ';object-fit:contain;display:inline-block;"'
         . ' alt="">';
  }

  /** Resolve path bingkai/border berdasarkan jenis izin dan konfigurasi dinas */
  private function resolveBorderPath($dinas): ?string
  {
    $borderType = $this->jenisPerizinan->border_type ?? null;
    $namaIzin   = strtolower($this->jenisPerizinan->nama ?? '');

    return match (true) {
      $borderType === 'paud'                                              => $dinas->watermark_border_paud_img ?: 'images/bingkai-paud.jpg',
      in_array($borderType, ['lkp', 'pkbm'])                             => 'images/bingkai-pkbm.jpg',
      $borderType === 'default'                                          => $dinas->watermark_border_img ?: 'images/default-border.png',
      str_contains($namaIzin, 'paud') || str_contains($namaIzin, 'tk') => $dinas->watermark_border_paud_img ?: 'images/bingkai-paud.jpg',
      str_contains($namaIzin, 'pkbm')                                   => 'images/bingkai-pkbm.jpg',
      default                                                            => $dinas->watermark_border_img ?: null,
    };
  }

  /** Fallback jika template belum dikonfigurasi */
  private function generateDefaultTable(): string
  {
    $nama = htmlspecialchars($this->jenisPerizinan->nama ?? 'Perizinan');
    return '<h2 style="text-align:center;margin:10mm 0 4mm;">' . $nama . '</h2>'
         . '<p style="text-align:center;">Template belum dikonfigurasi.</p>';
  }
}