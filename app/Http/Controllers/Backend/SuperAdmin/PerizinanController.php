<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Enums\PerizinanStatus;
use App\Services\PerizinanWorkflowService;
use App\Services\NomorSuratService;
use App\Models\CetakPreset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerizinanController extends Controller
{
  protected $workflowService;
  protected $nomorSuratService;

  public function __construct(PerizinanWorkflowService $workflowService, NomorSuratService $nomorSuratService)
  {
    $this->workflowService = $workflowService;
    $this->nomorSuratService = $nomorSuratService;
  }

  public function index(Request $request)
  {
    $query = Perizinan::where('dinas_id', Auth::user()->dinas_id)
      ->with(['lembaga', 'jenisPerizinan']);

    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $perizinans = $query->latest()->get();

    return view('backend.super_admin.perizinan.index', compact('perizinans'));
  }

  public function show(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);
    $perizinan->load(['lembaga', 'jenisPerizinan', 'discussions.user', 'dinas']);
    $perizinan->replaceVariables();
    return view('backend.super_admin.perizinan.show', compact('perizinan'));
  }

  public function approve(Perizinan $perizinan)
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Pengajuan ini sudah diproses atau tidak dalam status Menunggu Verifikasi.');
    }

    $this->authorize('verify', $perizinan);

    try {
      $perizinan->update(['approved_at' => now()]);
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::DISETUJUI, 'Disetujui oleh Dinas');
      return back()->with('success', 'Perizinan berhasil disetujui.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function needRevision(Request $request, Perizinan $perizinan)
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Pengajuan ini sudah diproses atau tidak dalam status Menunggu Verifikasi.');
    }

    $this->authorize('verify', $perizinan);

    $request->validate(['catatan' => 'required|string']);

    try {
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::PERBAIKAN, $request->catatan);
      return back()->with('success', 'Status diubah ke Perbaikan.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function reject(Perizinan $perizinan)
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Hanya pengajuan dengan status Diajukan yang dapat ditolak.');
    }

    $this->authorize('verify', $perizinan);

    try {
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::DITOLAK, 'Pengajuan ditolak oleh Dinas');
      return back()->with('success', 'Pengajuan berhasil ditolak.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function showFinalisasi(Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    if (
      $perizinan->status !== PerizinanStatus::DISETUJUI->value &&
      $perizinan->status !== PerizinanStatus::SELESAI->value &&
      $perizinan->status !== PerizinanStatus::DIAJUKAN->value &&
      $perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value
    ) {
      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('warning', 'Pengajuan harus disetujui terlebih dahulu sebelum finalisasi.');
    }

    if (!$perizinan->nomor_surat) {
      $perizinan->nomor_surat_auto = $this->nomorSuratService->generate($perizinan);
    }

    // Render the template so live preview has content
    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);
    $perizinan->replaceVariables();

    $activePreset = CetakPreset::where('is_active', true)->first();

    return view('backend.super_admin.perizinan.finalisasi', compact('perizinan', 'activePreset'));
  }

  public function release(Request $request, Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    $request->validate([
      'nomor_surat' => 'required|string',
      'tanggal_terbit' => 'required|date',
      'pimpinan_nama' => 'required|string',
      'pimpinan_jabatan' => 'required|string',
      'pimpinan_pangkat' => 'nullable|string',
      'pimpinan_nip' => 'nullable|string',
      'stempel_img' => 'nullable|image|max:2048',
    ]);

    $data = $request->except('stempel_img');

    if ($request->hasFile('stempel_img')) {
      $data['stempel_img'] = $request->file('stempel_img')->store('perizinan/stempel', 'public');
    }

    try {
      $data['approved_at'] = $perizinan->approved_at ?? now();
      $perizinan->allowImmutableUpdate = true;
      $perizinan->update($data);

      if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
        $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat telah dirilis.');
      }

      return redirect()->route('super_admin.penerbitan.riwayat')
        ->with('success', 'Sertifikat perizinan berhasil dirilis ke lembaga.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function autoRelease(Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value && $perizinan->status !== PerizinanStatus::DISETUJUI->value) {
      return back()->with('warning', 'Hanya pengajuan yang diajukan atau disetujui yang dapat diterbitkan otomatis.');
    }

    $dinas = Auth::user()->dinas;

    try {
      DB::beginTransaction();

      // Generate Auto Number if not exists
      if (!$perizinan->nomor_surat) {
        $perizinan->nomor_surat = $this->nomorSuratService->generate($perizinan);
      }

      // Fill with Defaults
      $perizinan->update([
        'approved_at' => $perizinan->approved_at ?? now(),
        'tanggal_terbit' => now(),
        'pimpinan_nama' => $dinas->pimpinan_nama,
        'pimpinan_jabatan' => $dinas->pimpinan_jabatan,
        'pimpinan_pangkat' => $dinas->pimpinan_pangkat,
        'pimpinan_nip' => $dinas->pimpinan_nip,
        'stempel_img' => $dinas->stempel_img,
      ]);

      // Workflow Transition
      if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
        $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat otomatis diterbitkan oleh sistem.');
      }

      DB::commit();

      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('success', 'Sertifikat berhasil diterbitkan secara otomatis dengan data default.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Gagal menerbitkan otomatis: ' . $e->getMessage());
    }
  }
}
