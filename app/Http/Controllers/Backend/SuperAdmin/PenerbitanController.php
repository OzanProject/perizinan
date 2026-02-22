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
    $html = $this->renderService->renderHtml($perizinan, false);

    return response()->json([
      'success' => true,
      'html' => $html
    ]);
  }

  public function exportPdf(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);

    $cleanLembaga = preg_replace('/[^A-Za-z0-9 _-]/', '', $perizinan->lembaga->nama);
    $filename = 'Sertifikat_' . str_replace(' ', '_', $cleanLembaga) . '.pdf';

    // Phase 4: Total Immutability - Prioritize Permanent Physical File (Private Storage)
    if ($perizinan->pdf_path && \Storage::disk('local')->exists($perizinan->pdf_path)) {
      return response()->download(\Storage::disk('local')->path($perizinan->pdf_path), $filename);
    }

    // Strict Authority Requirement: If finalized but file missing, do not re-render blindly
    if (in_array($perizinan->status, [PerizinanStatus::SIAP_DIAMBIL->value, PerizinanStatus::SELESAI->value])) {
      // If we reach here, it means the document is final but the file is missing from disk
      return back()->with('error', 'Gagal: File fisik sertifikat tidak ditemukan di storage aman. Silakan hubungi Administrator.');
    }

    // Fallback for draft permits or older documents (re-render)
    $finalHtml = $this->renderService->renderHtml($perizinan, true);
    $pdf = Pdf::loadHTML($finalHtml);

    $activePreset = CetakPreset::where('dinas_id', $perizinan->dinas_id)->where('is_active', true)->first();
    if ($activePreset) {
      $pdf->setPaper(strtolower($activePreset->paper_size), $activePreset->orientation);
    } else {
      $pdf->setPaper('a4', 'portrait');
    }

    return $pdf->download($filename);
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
