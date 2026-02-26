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
use Barryvdh\DomPDF\Facade\Pdf;

class PenerbitanController extends Controller
{
  protected $workflowService;
  protected $nomorSuratService;
  protected $renderService;

  public function __construct(
    PerizinanWorkflowService $workflowService,
    NomorSuratService $nomorSuratService,
    DocumentRenderService $renderService
  ) {
    $this->workflowService = $workflowService;
    $this->nomorSuratService = $nomorSuratService;
    $this->renderService = $renderService;
  }

  public function antrian()
  {
    $totalCount = Perizinan::where('status', PerizinanStatus::DISETUJUI)->count();
    $perizinans = Perizinan::with(['lembaga', 'jenisPerizinan'])
      ->where('status', PerizinanStatus::DISETUJUI)
      ->latest()
      ->paginate(10);

    return view('backend.super_admin.penerbitan.antrian', compact('perizinans', 'totalCount'));
  }

  public function riwayat()
  {
    $totalCount = Perizinan::whereIn('status', [PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI])->count();
    $perizinans = Perizinan::with(['lembaga', 'jenisPerizinan'])
      ->whereIn('status', [PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI])
      ->latest()
      ->paginate(10);

    return view('backend.super_admin.penerbitan.riwayat', compact('perizinans', 'totalCount'));
  }

  public function pusatCetak(Request $request)
  {
    $query = Perizinan::query();

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

  public function preview(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    // Use the service for consistent preview
    $html = $this->renderService->renderHtml($perizinan);

    return response()->json([
      'success' => true,
      'html' => $html
    ]);
  }

  public function exportPdf(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    // Accept overrides for paper size and orientation
    $paperSize = $request->query('paper_size');
    $orientation = $request->query('orientation');

    // This service now handles active presets and overrides
    return $this->renderService->generatePdf($perizinan, $paperSize, $orientation);
  }

  public function exportWord(Request $request, Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

    $activePreset = CetakPreset::where('dinas_id', $perizinan->dinas_id)
      ->where('is_active', true)
      ->first();

    // Mapping Paper Sizes for Word
    $paperSize = 'A4';
    $orientation = 'portrait';
    $margins = '1cm';

    if ($activePreset) {
      $paperSize = $request->query('paper_size') ?: strtoupper($activePreset->paper_size);
      $orientation = $request->query('orientation') ?: strtolower($activePreset->orientation);

      // Map "F4" to dimensions for Word CSS
      if (strtoupper($paperSize) === 'F4') {
        $paperSize = '21.5cm 33.0cm';
      }

      $mt = $activePreset->margin_top ?? 1.0;
      $mr = $activePreset->margin_right ?? 1.0;
      $mb = $activePreset->margin_bottom ?? 1.0;
      $ml = $activePreset->margin_left ?? 1.0;
      $margins = "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
    } else {
      // Fallback for overrides without preset
      $paperSize = $request->query('paper_size') ?: $paperSize;
      $orientation = $request->query('orientation') ?: $orientation;
      if (strtoupper($paperSize) === 'F4') {
        $paperSize = '21.5cm 33.0cm';
      }
    }

    // Use the user-designed template from template editor
    $finalHtml = $perizinan->replaceVariables();

    // Inject Page CSS for Word
    $htmlWithLayout = '
    <div class="word-page" style="page: word-page;">
        ' . $finalHtml . '
    </div>';

    $filename = $this->renderService->generateStandardFilename($perizinan) . '.doc';

    // Wrap in Word-compatible HTML
    $wordHtml = '
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:w="urn:schemas-microsoft-com:office:word"
          xmlns="http://www.w3.org/TR/REC-html40">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
        @page { 
          size: ' . $paperSize . ($orientation === 'landscape' ? ' landscape' : '') . '; 
          margin: ' . $margins . '; 
          mso-page-orientation: ' . $orientation . ';
        }
        body { font-family: \'Times New Roman\', serif; font-size: 11pt; line-height: 1.2; }
        table { border-collapse: collapse; width: 100%; }
        .signature-block { page-break-inside: avoid; }
      </style>
    </head>
    <body>' . $htmlWithLayout . '</body>
    </html>';

    return response($wordHtml)
      ->header('Content-Type', 'application/msword')
      ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
      ->header('Cache-Control', 'max-age=0');
  }

  public function exportExcel(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    $perizinan->load(['lembaga', 'jenisPerizinan']);

    $filename = $this->renderService->generateStandardFilename($perizinan) . '.csv';

    // Build data rows
    $rows = [
      ['INFORMASI PERIZINAN', ''],
      ['', ''],
      ['ID Perizinan', '#' . $perizinan->id],
      ['Nomor Surat', $perizinan->nomor_surat ?? '-'],
      ['Jenis Izin', $perizinan->jenisPerizinan->nama ?? '-'],
      ['Status', $perizinan->status],
      ['', ''],
      ['DATA LEMBAGA', ''],
      ['Nama Lembaga', $perizinan->lembaga->nama_lembaga ?? '-'],
      ['NPSN', $perizinan->lembaga->npsn ?? '-'],
      ['Jenjang', $perizinan->lembaga->jenjang ?? '-'],
      ['Alamat', $perizinan->lembaga->alamat ?? '-'],
      ['', ''],
      ['TANGGAL', ''],
      ['Tanggal Disetujui', $perizinan->approved_at ? $perizinan->approved_at->format('d/m/Y') : '-'],
      ['Tanggal Diterbitkan', $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('d/m/Y') : '-'],
    ];

    // Add perizinan_data if available
    $perizinanData = $perizinan->perizinan_data;
    if ($perizinanData && is_array($perizinanData)) {
      $rows[] = ['', ''];
      $rows[] = ['DATA ISIAN', ''];
      foreach ($perizinanData as $key => $value) {
        $rows[] = [ucwords(str_replace('_', ' ', $key)), is_array($value) ? json_encode($value) : ($value ?? '-')];
      }
    }

    // Generate CSV with BOM for Excel UTF-8
    $callback = function () use ($rows) {
      $file = fopen('php://output', 'w');
      fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
      foreach ($rows as $row) {
        fputcsv($file, $row);
      }
      fclose($file);
    };

    return response()->streamDownload($callback, $filename, [
      'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
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

  public function presetIndex()
  {
    $presets = CetakPreset::where('dinas_id', Auth::user()->dinas->id)->orderBy('is_active', 'desc')->get();
    return view('backend.super_admin.penerbitan.preset', compact('presets'));
  }

  public function presetStore(Request $request)
  {
    $data = $request->validate([
      'nama' => 'required',
      'paper_size' => 'required',
      'orientation' => 'required',
      'margin_top' => 'required|numeric',
      'margin_bottom' => 'required|numeric',
      'margin_left' => 'required|numeric',
      'margin_right' => 'required|numeric',
    ]);

    $data['dinas_id'] = Auth::user()->dinas->id;

    CetakPreset::create($data);

    return back()->with('success', 'Preset berhasil ditambahkan.');
  }

  public function presetUpdate(Request $request, CetakPreset $preset)
  {
    $data = $request->validate([
      'nama' => 'required',
      'paper_size' => 'required',
      'orientation' => 'required',
      'margin_top' => 'required|numeric',
      'margin_bottom' => 'required|numeric',
      'margin_left' => 'required|numeric',
      'margin_right' => 'required|numeric',
    ]);

    $preset->update($data);

    return back()->with('success', 'Preset berhasil diperbarui.');
  }

  public function presetDestroy(CetakPreset $preset)
  {
    if ($preset->is_active) {
      return back()->with('error', 'Preset aktif tidak bisa dihapus.');
    }

    $preset->delete();
    return back()->with('success', 'Preset berhasil dihapus.');
  }

  public function presetSetActive(CetakPreset $preset)
  {
    CetakPreset::where('dinas_id', $preset->dinas_id)->update(['is_active' => false]);
    $preset->update(['is_active' => true]);

    return back()->with('success', 'Preset aktif berhasil diubah.');
  }
}
