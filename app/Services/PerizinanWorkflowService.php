<?php

namespace App\Services;

use App\Models\Perizinan;
use App\Models\StatusLog;
use App\Enums\PerizinanStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerizinanWorkflowService
{
  protected $nomorSuratService;

  public function __construct(NomorSuratService $nomorSuratService)
  {
    $this->nomorSuratService = $nomorSuratService;
  }

  /**
   * Transisi status perizinan.
   */
  public function transitionTo(Perizinan $perizinan, PerizinanStatus $toStatus, string $catatan = null)
  {
    $fromStatus = PerizinanStatus::from($perizinan->status);

    if (!$this->isValidTransition($fromStatus, $toStatus)) {
      throw new \Exception("Transisi status dari {$fromStatus->value} ke {$toStatus->value} tidak valid.");
    }

    return DB::transaction(function () use ($perizinan, $fromStatus, $toStatus, $catatan) {
      $perizinan->status = $toStatus->value;

      // Logika spesifik per status
      if ($toStatus === PerizinanStatus::DISETUJUI) {
        $perizinan->approved_at = \Illuminate\Support\Carbon::now();
        $perizinan->nomor_surat = $this->nomorSuratService->generate($perizinan);
      } elseif ($toStatus === PerizinanStatus::SIAP_DIAMBIL) {
        $perizinan->ready_at = \Illuminate\Support\Carbon::now();
      } elseif ($toStatus === PerizinanStatus::SELESAI) {
        $perizinan->taken_at = \Illuminate\Support\Carbon::now();
      }

      $perizinan->save();

      // Catat log status
      StatusLog::create([
        'perizinan_id' => $perizinan->id,
        'from_status' => $fromStatus->value,
        'to_status' => $toStatus->value,
        'changed_by' => Auth::id(),
        'catatan' => $catatan,
      ]);

      return $perizinan;
    });
  }

  /**
   * Validasi aturan transisi status.
   */
  public function isValidTransition(PerizinanStatus $from, PerizinanStatus $to): bool
  {
    $rules = [
      PerizinanStatus::DRAFT->value => [PerizinanStatus::DIAJUKAN->value],
      PerizinanStatus::DIAJUKAN->value => [PerizinanStatus::PERBAIKAN->value, PerizinanStatus::DISETUJUI->value],
      PerizinanStatus::PERBAIKAN->value => [PerizinanStatus::DIAJUKAN->value],
      PerizinanStatus::DISETUJUI->value => [PerizinanStatus::SIAP_DIAMBIL->value, PerizinanStatus::SELESAI->value],
      PerizinanStatus::SIAP_DIAMBIL->value => [PerizinanStatus::SELESAI->value],
      PerizinanStatus::SELESAI->value => [],
    ];

    return in_array($to->value, $rules[$from->value] ?? []);
  }
}
