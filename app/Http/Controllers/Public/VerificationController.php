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
    // Try to find by hash (Phase 5 requirement)
    $perizinan = Perizinan::where('document_hash', $hash)->first();

    if (!$perizinan) {
      return view('public.verify', [
        'success' => false,
        'message' => 'Dokumen tidak ditemukan atau Hash tidak valid.',
      ]);
    }

    // Recalculate hash on-the-fly to check for database tampering
    $currentHash = $this->renderService->calculateHash($perizinan->snapshot_html);
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
}
