<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
  /**
   * Verifikasi keaslian perizinan.
   */
  public function verify(Perizinan $perizinan)
  {
    // Muat relasi yang diperlukan
    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

    // Jika status belum selesai, sertifikat mungkin belum valid atau masih proses
    $isValid = ($perizinan->status === \App\Enums\PerizinanStatus::SELESAI);

    return view('public.verification.show', compact('perizinan', 'isValid'));
  }
}
