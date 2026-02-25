<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\PerizinanStatus;

class Perizinan extends Model
{
  use HasFactory;

  public $allowImmutableUpdate = false;

  protected static function boot()
  {
    parent::boot();

    // Immutability Guard: Prevent editing final document data
    static::updating(function ($perizinan) {
      if ($perizinan->allowImmutableUpdate) {
        return;
      }

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

  public function dokumens()
  {
    return $this->hasMany(Dokumen::class);
  }

  public function statusLogs()
  {
    return $this->hasMany(StatusLog::class);
  }

  public function discussions()
  {
    return $this->hasMany(PerizinanDiscussion::class);
  }

  /**
   * Render Template Attribute
   */
  public function getRenderedTemplateAttribute()
  {
    if ($this->snapshot_html) {
      return $this->snapshot_html;
    }
    return $this->replaceVariables();
  }

  /**
   * Freezes the current state of the certificate into a permanent snapshot.
   */
  public function freezeSnapshot()
  {
    $html = $this->replaceVariables();
    $this->snapshot_html = $html;
    $this->document_hash = hash('sha256', $html);
    $this->save();
    return $this->snapshot_html;
  }

  public $rendered_template;

  /**
   * Main logic to replace [VARIABLES] or direct labels with actual data
   * This is the "Instant Engine" that allows Zero-Placeholder usage.
   */
  public function replaceVariables()
  {
    $template = $this->jenisPerizinan->template_html;
    $formConfig = $this->jenisPerizinan->form_config; // Assumes this is cast to array or json
    $data = $this->perizinan_data ?? [];

    if (!$template || trim(strip_tags($template)) == '') {
      // Instant Generation if template is empty
      $this->rendered_template = $this->generateDefaultTable();
      return $this->rendered_template;
    }

    $dinas = $this->dinas;
    $lembaga = $this->lembaga;

    // Helper for base64 images with defensive check
    $toBase64 = function ($path) {
      if (!$path || !\Storage::disk('public')->exists($path)) {
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // Transparent pixel
      }
      try {
        $fullPath = \Storage::disk('public')->path($path);
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64 = base64_encode($data);

        // Defensive: Check if base64 is not empty
        if (!$base64) {
          return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        }

        return 'data:image/' . ($type ?: 'png') . ';base64,' . $base64;
      } catch (\Exception $e) {
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
      }
    };

    // 1. Map Core Global Variables (Always active)
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
      '[LOGO_DINAS]' => '<img src="' . $toBase64($dinas->logo) . '" width="70" height="70" style="display:block;">',
      '[WATERMARK_LOGO]' => '<img src="' . $toBase64($dinas->watermark_img ?: $dinas->logo) . '" width="70" height="70">',
      '[STEMPEL_DINAS]' => '<img src="' . $toBase64($this->stempel_img ?? $dinas->stempel_img) . '" width="80" height="80">',
    ];

    // Helper: Build dynamic TTL if exists in data
    $tempatLahir = $data['tempat_lahir'] ?? null;
    $tglLahir = $data['tanggal_lahir'] ?? null;
    if ($tempatLahir && $tglLahir) {
      $globalVars['[TTL]'] = "{$tempatLahir}, " . \Carbon\Carbon::parse($tglLahir)->translatedFormat('d F Y');
    }

    // Common Dynamic Data Mapping (Auto-Fallback)
    $commonKeys = ['NAMA', 'PENDIDIKAN', 'JABATAN', 'UNIT_KERJA'];
    foreach ($commonKeys as $ck) {
      if (isset($data[strtolower($ck)])) {
        $globalVars["[{$ck}]"] = $data[strtolower($ck)];
      }
    }

    foreach ($globalVars as $key => $val) {
      $template = str_replace($key, $val, $template);
    }

    // Defensive: Handle <img> tags with onerror for any remaining or custom images
    // This ensures if a placeholder results in empty/invalid src, it doesn't break layout
    if (str_contains($template, '<img')) {
      $template = preg_replace('/<img([^>]+)>/i', '<img$1 onerror="this.style.display=\'none\';">', $template);
    }

    // 2. INSTANT ENGINE: Map by Labels & Automatic Tags
    if (is_array($formConfig)) {
      foreach ($formConfig as $field) {
        $key = $field['name'] ?? '';
        $label = $field['label'] ?? '';
        $val = $data[$key] ?? '';

        if (is_array($val))
          $val = implode(', ', $val);
        if (!$val)
          $val = '................';

        // A. Direct Replacement by [DATA:key] or [key] (Standard)
        $template = str_ireplace('[DATA:' . strtoupper($key) . ']', $val, $template);
        $template = str_ireplace('[DATA:' . $key . ']', $val, $template);
        $template = str_ireplace('[' . strtoupper($key) . ']', $val, $template);

        // B. Instant Replacement by Label (Zero-Placeholder Logic)
        // Matches: "Nama :" or "Nama  :" or "Nama:......" or "Nama: "
        if ($label) {
          $safeLabel = preg_quote($label, '/');
          // Pattern: Label followed by optional whitespace, colon, then optional whitespace and dots
          $pattern = "/({$safeLabel}\s*:\s*[.\s]*)/i";

          // We want to keep the label and colon, then put the value
          // But if it's already a complex HTML, we be careful.
          // Simple replace:
          $template = preg_replace($pattern, "{$label} : <strong>{$val}</strong>", $template);

          // Also match naked Label if it is inside a table cell or similar tagged area
          // But we prioritize the colon version for safety.
          $template = str_ireplace('[' . strtoupper($label) . ']', $val, $template);
          $template = str_ireplace('[' . $label . ']', $val, $template);
        }
      }
    }


    // 3. Fallback/Cleanup
    $template = preg_replace('/\[DATA:[^\]]+\]/i', '................', $template);

    // 4. Auto-inject full-page watermark layers if enabled
    if ($dinas->watermark_enabled ?? true) {
      $wmOpacity = $dinas->watermark_opacity ?? 0.08;
      $wmSize = $dinas->watermark_size ?? 400;
      $halfSize = $wmSize / 2;

      // Layer 1: Center watermark (logo behind text)
      $wmImg = $dinas->watermark_img ?: $dinas->logo;
      if ($wmImg) {
        $wmBase64 = $toBase64($wmImg);
        // DomPDF doesn't support transform, use fixed position with negative margins to center
        $template .= '
        <div style="position: fixed; top: 50%; left: 50%;
                    width: ' . $wmSize . 'px; height: ' . $wmSize . 'px;
                    margin-top: -' . $halfSize . 'px; margin-left: -' . $halfSize . 'px;
                    opacity: ' . $wmOpacity . '; z-index: -1;">
          <img src="' . $wmBase64 . '" width="' . $wmSize . '" height="' . $wmSize . '" />
        </div>';
      }

      // Layer 2: Border/frame decoration (full-page ornament)
      if ($dinas->watermark_border_img) {
        $borderBase64 = $toBase64($dinas->watermark_border_img);
        $template .= '
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                    width: 100%; height: 100%;
                    opacity: ' . min($wmOpacity * 3, 1.0) . '; z-index: -2;">
          <img src="' . $borderBase64 . '" width="100%" height="100%" />
        </div>';
      }
    }

    $this->rendered_template = $template;
    return $template;
  }

  /**
   * Generates a clean professional table of all data if no template is provided.
   */
  private function generateDefaultTable()
  {
    $lembaga = $this->lembaga;
    $data = $this->perizinan_data ?? [];
    $formConfig = $this->jenisPerizinan->form_config;

    $html = "<div style='text-align: center; margin-bottom: 30px;'>";
    $html .= "<h2 style='text-transform: uppercase; margin-bottom: 5px;'>" . $this->jenisPerizinan->nama . "</h2>";
    $html .= "<p>Nomor : " . ($this->nomor_surat ?? '................') . "</p></div>";

    $html .= "<table style='width: 100%; border-collapse: collapse;'><tbody>";

    // Institutional fixed data
    $items = [
      'Nama Lembaga' => $lembaga->nama_lembaga ?: $lembaga->nama,
      'NPSN' => $lembaga->npsn,
      'Alamat' => $lembaga->alamat,
    ];

    foreach ($items as $l => $v) {
      $html .= "<tr><td style='padding: 5px; width: 200px;'>{$l}</td><td>:</td><td style='padding: 5px;'>{$v}</td></tr>";
    }

    // Dynamic Form Data
    if (is_array($formConfig)) {
      foreach ($formConfig as $field) {
        $label = $field['label'] ?? '';
        $val = $data[$field['name'] ?? ''] ?? '................';
        if (is_array($val))
          $val = implode(', ', $val);
        $html .= "<tr><td style='padding: 5px;'>{$label}</td><td>:</td><td style='padding: 5px; font-weight: bold;'>{$val}</td></tr>";
      }
    }

    $html .= "</tbody></table>";
    return $html;
  }
}
