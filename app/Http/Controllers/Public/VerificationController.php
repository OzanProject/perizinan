<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Services\DocumentRenderService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
  protected $renderService;

  public function __construct(DocumentRenderService $renderService)
  {
    $this->renderService = $renderService;
  }

  /**
   * Public Verification Page
   */
  public function verify($hash)
  {
    // Try to find by hash (Phase 5 requirement) or qr_token (Barcode)
    $perizinan = Perizinan::where('document_hash', $hash)->orWhere('qr_token', $hash)->first();

    if (!$perizinan) {
      return view('public.verify', [
        'success' => false,
        'message' => 'Dokumen tidak ditemukan atau Hash tidak valid.',
      ]);
    }

    // Recalculate hash on-the-fly to check for database tampering
    $currentHash = hash('sha256', $perizinan->snapshot_html ?? '');
    $isValid = ($currentHash === $perizinan->document_hash);

    // Load relations for display
    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

    return view('public.verify', [
      'success' => true,
      'isValid' => $isValid,
      'perizinan' => $perizinan,
      'currentHash' => $currentHash,
      'storedHash' => $perizinan->document_hash
    ]);
  }

  public function download($hash)
  {
    $perizinan = Perizinan::where('document_hash', $hash)->orWhere('qr_token', $hash)->first();

    if (!$perizinan) {
      abort(404, 'Dokumen tidak ditemukan.');
    }

    if ($perizinan->pdf_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($perizinan->pdf_path)) {
      return response()->download(storage_path('app/' . $perizinan->pdf_path));
    }

    // Jika belum ada file fisik, generate on the fly
    return $this->renderService->generatePdf($perizinan);
  }
}
