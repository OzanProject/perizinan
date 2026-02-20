<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Enums\PerizinanStatus;
use App\Services\PerizinanWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{
  protected $workflowService;

  public function __construct(PerizinanWorkflowService $workflowService)
  {
    $this->workflowService = $workflowService;
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
    $perizinan->load(['lembaga', 'jenisPerizinan', 'discussions.user']);
    return view('backend.super_admin.perizinan.show', compact('perizinan'));
  }

  public function approve(Perizinan $perizinan)
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Pengajuan ini sudah diproses atau tidak dalam status Menunggu Verifikasi.');
    }

    $this->authorize('verify', $perizinan);

    try {
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

  public function showFinalisasi(Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    if ($perizinan->status !== PerizinanStatus::DISETUJUI->value && $perizinan->status !== PerizinanStatus::SELESAI->value) {
      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('warning', 'Pengajuan harus disetujui terlebih dahulu sebelum finalisasi.');
    }

    return view('backend.super_admin.perizinan.finalisasi', compact('perizinan'));
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
      $perizinan->update($data);

      if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
        $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat telah dirilis.');
      }

      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('success', 'Sertifikat perizinan berhasil dirilis ke lembaga.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}
