<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\CetakPreset;
use App\Services\DocumentRenderService;
use App\Enums\PerizinanStatus;
use App\Services\PerizinanWorkflowService;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PenerbitanController extends Controller
{
  protected $workflowService;
  protected $nomorSuratService;
  protected $renderHtmlAction;
  protected $generatePdfAction;

  public function __construct(
    PerizinanWorkflowService $workflowService,
    NomorSuratService $nomorSuratService,
    \App\Actions\Perizinan\RenderHtmlAction $renderHtmlAction,
    \App\Actions\Perizinan\GeneratePdfAction $generatePdfAction
  ) {
    $this->workflowService = $workflowService;
    $this->nomorSuratService = $nomorSuratService;
    $this->renderHtmlAction = $renderHtmlAction;
    $this->generatePdfAction = $generatePdfAction;
  }

  private function scopeByRole($query)
  {
    $user = Auth::user();
    if ($user->hasRole('bidang_dikmas')) {
      $query->whereHas('lembaga', function ($q) {
        $q->whereIn('jenjang', ['PKBM', 'LKP']);
      });
    } elseif ($user->hasRole('bidang_paud')) {
      $query->whereHas('lembaga', function ($q) {
        $q->whereIn('jenjang', ['KB', 'TK', 'PAUD', 'SPS', 'TPA']);
      });
    }
    return $query;
  }

  public function antrian()
  {
    $query = Perizinan::where('status', PerizinanStatus::DISETUJUI);
    $query = $this->scopeByRole($query);

    $totalCount = (clone $query)->count();
    $perizinans = $query->with(['lembaga', 'jenisPerizinan'])
      ->latest()
      ->paginate(10);

    return view('backend.super_admin.penerbitan.antrian', compact('perizinans', 'totalCount'));
  }

  public function riwayat()
  {
    $query = Perizinan::whereIn('status', [PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI]);
    $query = $this->scopeByRole($query);

    $totalCount = (clone $query)->count();
    $perizinans = $query->with(['lembaga', 'jenisPerizinan'])
      ->latest()
      ->paginate(10);

    return view('backend.super_admin.penerbitan.riwayat', compact('perizinans', 'totalCount'));
  }

  public function pusatCetak(Request $request)
  {
    $query = Perizinan::query();
    $query = $this->scopeByRole($query);

    // Hanya status yang sudah disetujui atau lebih lanjut yang bisa dicetak
    $query->whereIn('status', [
      PerizinanStatus::DISETUJUI,
      PerizinanStatus::SIAP_DIAMBIL,
      PerizinanStatus::SELESAI
    ]);

    // Filter Pencarian
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->whereHas('lembaga', function ($ql) use ($search) {
          $ql->where('nama_lembaga', 'like', "%{$search}%");
        })->orWhere('id', 'like', "%{$search}%");
      });
    }

    // Filter Status
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // Filter Tanggal Approved
    if ($request->filled('date')) {
      $query->whereDate('approved_at', $request->date);
    }

    $totalCount = $query->count();

    $perizinans = $query->with(['lembaga', 'jenisPerizinan'])
      ->latest()
      ->paginate(10)
      ->withQueryString();

    $activePreset = CetakPreset::where('dinas_id', Auth::user()->dinas->id)->where('is_active', true)->first();

    return view('backend.super_admin.penerbitan.pusat_cetak', compact('perizinans', 'totalCount', 'activePreset'));
  }

  public function preview(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    // Gunakan preset aktif
    $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
      ->where('is_active', true)
      ->first();

    // Terima parameter dari URL jika ada (seperti di Pusat Cetak), kalau tidak ada gunakan rule hierarki
    $paperSize = $request->query('paper_size') ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4'));
    $orientation = $request->query('orientation') ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait'));

    // Render HTML dengan CSS size: ... agar iframe bisa mengambil ukuran yang benar
    $html = $this->renderHtmlAction->handle($perizinan, $paperSize, $orientation, false);

    return response()->json([
      'success' => true,
      'html' => $html
    ]);
  }


  // ==========================================================================
// PATCH untuk PenerbitanController.php
// Ganti seluruh isi method printHtml() dengan versi di bawah ini.
// Hanya bagian $inject (blok CSS/JS) yang berubah — logic lain tetap sama.
// ==========================================================================

  public function printHtml(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
      ->where('is_active', true)
      ->first();

    $paperSize = strtoupper($request->query('paper_size') ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
    $orientation = strtolower($request->query('orientation') ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));

    // Dimensi kertas dalam mm
    $paperDims = ['A4' => ['w' => 210, 'h' => 297], 'F4' => ['w' => 215, 'h' => 330], 'A3' => ['w' => 297, 'h' => 420]];
    $dims = $paperDims[$paperSize] ?? $paperDims['A4'];

    // Landscape: tukar lebar & tinggi
    if ($orientation === 'landscape') {
      $bodyW = $dims['h'] . 'mm';
      $bodyH = $dims['w'] . 'mm';
    } else {
      $bodyW = $dims['w'] . 'mm';
      $bodyH = $dims['h'] . 'mm';
    }

    // @page size keyword (F4 pakai mm karena tidak ada di CSS standard)
    $pageSize = ($paperSize === 'F4')
      ? ($orientation === 'landscape' ? '330mm 215mm' : '215mm 330mm')
      : strtolower($paperSize) . ' ' . $orientation;

    // Render HTML — forPdf=false, otomatis pakai buffer khusus browser (lihat RenderHtmlAction)
    $html = $this->renderHtmlAction->handle($perizinan, $paperSize, $orientation, false);

    // Inject CSS & JS untuk force landscape + jaminan 1 lembar rapi di Chrome
    $inject = '
<style id="force-print-css">
@page { size: ' . $pageSize . '; margin: 0; }

@media print {
    @page { size: ' . $pageSize . '; margin: 0; }

    html, body {
        width: ' . $bodyW . ' !important;
        height: ' . $bodyH . ' !important;
        overflow: hidden !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* JAMINAN: apapun elemen selain .print-page yang ada langsung di <body>
       (banner panduan, toolbar, tombol, dsb) TIDAK PERNAH ikut tercetak,
       terlepas dari CSS bawaan elemen itu sendiri. Ini mencegah banner
       mendorong konten turun sehingga lompat ke halaman ke-2. */
    body > *:not(.print-page) {
        display: none !important;
    }

    /* .print-page sendiri dipastikan pas 1 halaman penuh saat print,
       tidak mewarisi sisa spacing dari elemen lain di atasnya. */
    .print-page {
        margin: 0 !important;
        page-break-after: avoid !important;
        page-break-before: avoid !important;
        page-break-inside: avoid !important;
    }
}

/* Tampilan preview di layar (sebelum tombol print/dialog muncul) */
html, body { width: ' . $bodyW . '; min-height: ' . $bodyH . '; }
</style>
<script>
// Chrome: inject ulang @page tepat sebelum print agar tidak di-override dialog
window.addEventListener("beforeprint", function() {
    var old = document.getElementById("dyn-page");
    if (old) old.remove();
    var s = document.createElement("style");
    s.id = "dyn-page";
    s.textContent = "@page { size: ' . $pageSize . '; margin: 0; }";
    document.head.appendChild(s);
});
window.addEventListener("load", function() {
    setTimeout(function() { window.print(); }, 800);
});
window.addEventListener("afterprint", function() { window.close(); });
</script>';

    // Inject banner panduan di bagian atas halaman (otomatis di-hide saat print oleh CSS di atas)
    // PENTING: pakai preg_replace dengan limit 1, BUKAN str_replace — str_replace akan
    // mengganti SEMUA kemunculan literal string "<body>" di seluruh dokumen, termasuk
    // kalau ada teks "<body>" yang muncul di dalam komentar CSS/JS, yang menyebabkan
    // sisa <style> ikut "terpotong" dan ke-render sebagai teks acak di halaman.
    $banner = $this->buildPrintGuidanceBanner($orientation, $paperSize);
    $html = preg_replace('/<body(\s[^>]*)?>/i', '$0' . $banner, $html, 1);

    $html = str_replace('</head>', $inject . '</head>', $html);

    return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
  }

  // Helper untuk menampilkan halaman cetak dengan panduan orientasi
  private function buildPrintGuidanceBanner(string $orientation, string $paperSize): string
  {
    if ($orientation !== 'landscape')
      return '';
    return '
<div id="print-guidance" style="
    position: fixed; top: 0; left: 0; right: 0; z-index: 99999;
    background: linear-gradient(135deg, #1e3a5f, #2563eb);
    color: #fff; padding: 12px 20px;
    display: flex; align-items: center; justify-content: space-between;
    font-family: Arial, sans-serif; font-size: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
">
    <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-size:22px;">🖨️</span>
        <div>
            <strong style="font-size:15px;">Dokumen ini harus dicetak LANDSCAPE (' . $paperSize . ')</strong><br>
            <span style="opacity:0.85; font-size:13px;">Di dialog Print Chrome: klik <strong>"More settings"</strong> → ubah <strong>"Layout"</strong> ke <strong>"Landscape"</strong></span>
        </div>
    </div>
    <button onclick="document.getElementById(\'print-guidance\').style.display=\'none\'" style="
        background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
        color: #fff; border-radius: 6px; padding: 6px 14px; cursor: pointer; font-size: 13px;
    ">✕ Tutup</button>
</div>
<div style="height: 60px;" id="print-guidance-spacer"></div>
<style>
@media print {
    #print-guidance, #print-guidance-spacer { display: none !important; }
}
</style>';
  }

  public function exportPdf(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    // Tidak perlu query param — generatePdf membaca otomatis dari jenisPerizinan → preset global → default A4 portrait
    return $this->generatePdfAction->handle($perizinan);
  }

  public function exportWord(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);
    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

    $lembaga = $perizinan->lembaga->nama_lembaga ?? 'Lembaga';
    $jenis = $perizinan->jenisPerizinan->nama ?? 'Perizinan';
    $tanggal = date('d-m-Y');

    // 1. Cek apakah ada template DOCX asli yang diunggah
    if ($perizinan->jenisPerizinan && $perizinan->jenisPerizinan->template_word_path) {
      $templatePath = storage_path('app/public/' . $perizinan->jenisPerizinan->template_word_path);
      if (file_exists($templatePath)) {
        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $template->setValue('NOMOR_SURAT', $perizinan->nomor_surat ?? '-');
        $template->setValue('NAMA_LEMBAGA', $perizinan->lembaga->nama_lembaga ?? '-');
        $template->setValue('NPSN', $perizinan->lembaga->npsn ?? '-');
        $template->setValue('ALAMAT_LEMBAGA', $perizinan->lembaga->alamat ?? '-');
        $template->setValue('TANGGAL_TERBIT', $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->translatedFormat('d F Y') : '-');
        $template->setValue('MASA_BERLAKU', $perizinan->masa_berlaku ? $perizinan->masa_berlaku->translatedFormat('d F Y') : '-');

        $dinas = $perizinan->dinas;
        if ($dinas) {
          $template->setValue('KOTA_DINAS', $dinas->kota ?? '');
          $template->setValue('ALAMAT_DINAS', $dinas->alamat ?? '');
          $template->setValue('PIMPINAN_NAMA', $dinas->nama_pimpinan ?? '');
          $template->setValue('PIMPINAN_NIP', $dinas->nip_pimpinan ?? '');
          $template->setValue('PIMPINAN_JABATAN', $dinas->jabatan_pimpinan ?? '');
          $template->setValue('PIMPINAN_PANGKAT', $dinas->pangkat_pimpinan ?? '');
        }

        if (is_array($perizinan->data)) {
          foreach ($perizinan->data as $key => $value) {
            $valStr = is_string($value) ? $value : (is_array($value) ? implode(', ', $value) : json_encode($value));
            $template->setValue('DATA:' . strtoupper($key), $valStr);
          }
        }

        if ($perizinan->qr_file && file_exists(storage_path('app/public/qr_codes/' . $perizinan->qr_file))) {
          try {
            $template->setImageValue('QR_CODE', [
              'path' => storage_path('app/public/qr_codes/' . $perizinan->qr_file),
              'width' => 80,
              'height' => 80,
              'ratio' => false
            ]);
          } catch (\Exception $e) {
          }
        }

        $filename = "{$lembaga}_{$jenis}_{$tanggal}.docx";
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
        $tempPath = storage_path('app/public/temp/' . $filename);

        if (!file_exists(storage_path('app/public/temp'))) {
          mkdir(storage_path('app/public/temp'), 0755, true);
        }

        $template->saveAs($tempPath);
        return response()->download($tempPath)->deleteFileAfterSend(true);
      }
    }

    // 2. Fallback: Gunakan raw HTML wrapper (Hindari RenderHtmlAction karena CSS strict-nya merusak MS Word)
    $finalHtml = $perizinan->replaceVariables(false);
    $finalHtml = $this->embedLocalImagesAsBase64($finalHtml);
    $qrBlock = $this->buildWordQrBlock($perizinan);

    // --- Tentukan ukuran kertas & orientasi dengan hierarki YANG SAMA seperti jalur PDF ---
    $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
      ->where('is_active', true)
      ->first();

    $paperSize = strtoupper($request->query('paper_size') ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
    $orientation = strtolower($request->query('orientation') ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));
    $isLandscape = $orientation === 'landscape';

    $paperDims = ['A4' => ['w' => 210, 'h' => 297], 'F4' => ['w' => 215, 'h' => 330], 'A3' => ['w' => 297, 'h' => 420]];
    $dims = $paperDims[$paperSize] ?? $paperDims['A4'];

    // Untuk landscape, lebar-halaman WAJIB ditulis lebih besar dari tinggi (konvensi MS Word)
    $pageW = $isLandscape ? $dims['h'] : $dims['w'];
    $pageH = $isLandscape ? $dims['w'] : $dims['h'];

    // Margin: pakai preset kalau ada, sama seperti default non-preset di RenderHtmlAction
    if ($preset && ($preset->margin_top || $preset->margin_right || $preset->margin_bottom || $preset->margin_left)) {
      $mt = $preset->margin_top ?? ($isLandscape ? 1.0 : 2.5);
      $mr = $preset->margin_right ?? ($isLandscape ? 2.5 : 3.0);
      $mb = $preset->margin_bottom ?? ($isLandscape ? 0.5 : 1.5);
      $ml = $preset->margin_left ?? ($isLandscape ? 2.5 : 3.0);
      $marginCss = "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
    } else {
      $marginCss = $isLandscape ? '1.5cm 2cm' : '1.5cm 1.5cm';
    }

    $fontSize = $isLandscape ? '9.5pt' : '10.5pt';

    $wordHtml = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
      <meta charset="utf-8">
      <!--[if gte mso 9]>
      <xml>
        <w:WordDocument>
          <w:View>Print</w:View>
          <w:Zoom>100</w:Zoom>
          <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
      </xml>
      <![endif]-->
      <style>
        /* @page Section1 + div.page:Section1 adalah cara MS Word membaca ukuran
           kertas & orientasi CUSTOM (termasuk F4 yang bukan ukuran bawaan Word).
           mso-page-orientation dipasangkan dengan size yang sudah ditukar posisi
           w/h untuk landscape — kombinasi ini yang paling konsisten dibaca Word. */
        @page Section1 {
            size: ' . $pageW . 'mm ' . $pageH . 'mm;
            mso-page-orientation: ' . $orientation . ';
            margin: ' . $marginCss . ';
        }
        div.Section1 { page: Section1; }
 
        body { font-family: "Times New Roman", serif; font-size: ' . $fontSize . '; line-height: 1.15; }
        table { border-collapse: collapse; width: 100%; }
        td { vertical-align: top; }
        p { text-align: justify; margin: 0 0 4px 0; }
        .print-qr-floating { margin-top: 20px; }
      </style>
    </head>
    <body>
        <div class="Section1">
            ' . $finalHtml . '
            ' . $qrBlock . '
        </div>
    </body>
    </html>';

    $filename = "{$lembaga}_{$jenis}_{$tanggal}.doc";
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);

    return response($wordHtml)
      ->header('Content-Type', 'application/msword')
      ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
      ->header('Cache-Control', 'max-age=0');
  }

  private function embedLocalImagesAsBase64(string $html): string
  {
    // Ukuran default (px) untuk gambar yang TIDAK punya atribut width/height HTML eksplisit.
    // MS Word mengabaikan CSS (class Tailwind, width:100%, dst) sepenuhnya saat merender HTML
    // sebagai .doc, jadi tanpa atribut ini gambar akan tampil di resolusi asli filenya (bisa
    // sangat besar). Sesuaikan angka ini kalau ternyata masih kurang pas untuk logo kop surat.
    $defaultMaxSize = 90;

    return preg_replace_callback(
      '/<img([^>]*)>/i',
      function ($m) use ($defaultMaxSize) {
        $fullTag = $m[0];
        $attrsPart = $m[1];

        if (!preg_match('/src=["\']([^"\']+)["\']/i', $attrsPart, $sm)) {
          return $fullTag; // img tanpa src, biarkan
        }
        $src = $sm[1];

        $dataUri = $src;

        if (!str_starts_with($src, 'data:')) {
          // Ambil path relatif dari URL storage publik (mis. ".../storage/xxx.png" -> "xxx.png")
          $relativePath = null;
          if (preg_match('#/storage/(.+)$#', $src, $pm)) {
            $relativePath = $pm[1];
          } elseif (!str_starts_with($src, 'http')) {
            $relativePath = ltrim($src, '/');
          }

          if (!$relativePath || !Storage::disk('public')->exists($relativePath)) {
            // Tidak ditemukan di disk lokal (kemungkinan memang gambar eksternal) -> biarkan src, lanjut cek ukuran
          } else {
            $ext = pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'png';
            $data = Storage::disk('public')->get($relativePath);
            $dataUri = 'data:image/' . $ext . ';base64,' . base64_encode($data);
          }
        }

        // Ganti src (kalau berubah jadi base64)
        $attrsPart = preg_replace('/src=["\'][^"\']+["\']/i', 'src="' . $dataUri . '"', $attrsPart);

        // Kalau belum ada atribut width DAN height eksplisit, paksa ukuran default supaya
        // tidak tampil raksasa di Word. Hapus dulu style width/height persen yang mungkin
        // ada (tidak akan dipatuhi Word, tapi rapikan saja).
        $hasWidthAttr = preg_match('/\bwidth=["\']?\d/i', $attrsPart);
        $hasHeightAttr = preg_match('/\bheight=["\']?\d/i', $attrsPart);

        if (!$hasWidthAttr || !$hasHeightAttr) {
          $attrsPart .= ' width="' . $defaultMaxSize . '" height="' . $defaultMaxSize . '" style="width:' . $defaultMaxSize . 'px; height:' . $defaultMaxSize . 'px;"';
        }

        return '<img' . $attrsPart . '>';
      },
      $html
    );
  }

  /**
   * Bangun blok QR code untuk export Word, dengan tabel (bukan position:absolute) supaya
   * posisinya konsisten, dan gambar di-embed sebagai base64 supaya selalu tampil.
   */
  private function buildWordQrBlock(Perizinan $perizinan): string
  {
    // Belum ada QR sama sekali di record ini (kemungkinan besar penyebab "QR tidak muncul" —
    // bukan soal render, tapi memang belum di-generate). Tampilkan catatan kecil supaya
    // kelihatan jelas alasannya alih-alih diam-diam kosong.
    if (!$perizinan->qr_file) {
      return '<p style="font-size:8pt; color:#888; margin-top:10px;">[QR code belum tersedia untuk dokumen ini]</p>';
    }

    $path = storage_path('app/public/qr_codes/' . $perizinan->qr_file);
    if (!file_exists($path)) {
      return '<p style="font-size:8pt; color:#888; margin-top:10px;">[QR code tidak ditemukan di: qr_codes/' . e($perizinan->qr_file) . ']</p>';
    }

    $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
    $dataUri = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($path));

    // Tabel 1 baris 1 kolom, rata kanan, lebar tetap — cara paling aman untuk MS Word
    // menempatkan gambar di posisi yang konsisten tanpa position:absolute/float.
    return '
        <table style="width:100%; border:none; margin-top:10px;">
            <tr>
                <td style="width:70%; border:none;">&nbsp;</td>
                <td style="width:30%; border:none; text-align:center;">
                    <img src="' . $dataUri . '" width="80" height="80" style="width:80px; height:80px;" alt="QR Code">
                    <div style="font-size:8pt; margin-top:2px;">Kode Verifikasi</div>
                </td>
            </tr>
        </table>';
  }

  public function exportExcel(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    $perizinan->load(['lembaga', 'jenisPerizinan']);

    $lembaga = $perizinan->lembaga->nama_lembaga ?? 'Lembaga';
    $jenis = $perizinan->jenisPerizinan->nama ?? 'Perizinan';
    $tanggal = date('d-m-Y');
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', "{$lembaga}_{$jenis}_{$tanggal}.xls");

    // Build HTML table for Excel
    $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta charset="utf-8">
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Detail Perizinan</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                        <x:FreezePanes/>
                        <x:FrozenNoSplit/>
                        <x:SplitHorizontal>1</x:SplitHorizontal>
                        <x:TopRowBottomPane>1</x:TopRowBottomPane>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        @page { margin: 1in .75in 1in .75in; mso-header-margin: .3in; mso-footer-margin: .3in; }
        table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; white-space: normal; mso-number-format: "\@"; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .section-header { background-color: #0d6efd; color: white; font-weight: bold; font-size: 13pt; }
    </style>
</head>
<body>
    <table>
        <col style="width:220px;">
        <col style="width:520px;">
 
        <tr><td colspan="2" class="section-header">INFORMASI PERIZINAN</td></tr>
        <tr><td>ID Perizinan</td><td>#' . $perizinan->id . '</td></tr>
        <tr><td>Nomor Surat</td><td>' . ($perizinan->nomor_surat ?? '-') . '</td></tr>
        <tr><td>Jenis Izin</td><td>' . ($perizinan->jenisPerizinan->nama ?? '-') . '</td></tr>
        <tr><td>Status</td><td>' . $perizinan->status . '</td></tr>
        <tr><td colspan="2">&nbsp;</td></tr>
 
        <tr><td colspan="2" class="section-header">DATA LEMBAGA</td></tr>
        <tr><td>Nama Lembaga</td><td>' . ($perizinan->lembaga->nama_lembaga ?? '-') . '</td></tr>
        <tr><td>NPSN</td><td>' . ($perizinan->lembaga->npsn ?? '-') . '</td></tr>
        <tr><td>Jenjang</td><td>' . ($perizinan->lembaga->jenjang ?? '-') . '</td></tr>
        <tr><td>Alamat</td><td>' . ($perizinan->lembaga->alamat ?? '-') . '</td></tr>
        <tr><td colspan="2">&nbsp;</td></tr>
 
        <tr><td colspan="2" class="section-header">TANGGAL</td></tr>
        <tr><td>Tanggal Disetujui</td><td>' . ($perizinan->approved_at ? $perizinan->approved_at->format('d/m/Y') : '-') . '</td></tr>
        <tr><td>Tanggal Diterbitkan</td><td>' . ($perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('d/m/Y') : '-') . '</td></tr>
        <tr><td colspan="2">&nbsp;</td></tr>';

    $perizinanData = $perizinan->perizinan_data;
    if ($perizinanData && is_array($perizinanData)) {
      $html .= '<tr><td colspan="2" class="section-header">DATA ISIAN</td></tr>';
      foreach ($perizinanData as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $val = is_array($value) ? json_encode($value) : ($value ?? '-');
        $html .= '<tr><td>' . htmlspecialchars($label) . '</td><td>' . htmlspecialchars($val) . '</td></tr>';
      }
    }

    $html .= '
    </table>
</body>
</html>';

    return response($html)
      ->header('Content-Type', 'application/vnd.ms-excel')
      ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
      ->header('Cache-Control', 'max-age=0');
  }

  /**
   * HELPERS: Standarized Layout Rendering (Moved to DocumentRenderService)
   */

  public function finalisasi(Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    // Kirim Sertifikat ke Step selanjutnya (Misal Cetak -> Selesai)
    $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat telah dicetak dan siap diambil.');

    return response()->json([
      'success' => true,
      'message' => 'Status berhasil diperbarui menjadi Siap Diambil.'
    ]);
  }


}