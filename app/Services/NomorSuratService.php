<?php

namespace App\Services;

use App\Models\Perizinan;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
  /**
   * Generate nomor surat format: {urutan}/{kode_dinas}/{tahun}
   */
  public function generate(Perizinan $perizinan): string
  {
    return DB::transaction(function () use ($perizinan) {
      $year = now()->year;
      $kodeDinas = $perizinan->dinas->kode_surat;

      // Cari urutan terakhir di tahun ini untuk dinas tersebut
      $lastNum = Perizinan::where('dinas_id', $perizinan->dinas_id)
        ->whereYear('approved_at', $year)
        ->whereNotNull('nomor_surat')
        ->lockForUpdate() // Mencegah race condition
        ->count();

      $nextNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);

      return "{$nextNum}/{$kodeDinas}/{$year}";
    });
  }
}
