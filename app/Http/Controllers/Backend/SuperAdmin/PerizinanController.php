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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PerizinanController extends Controller
{
  // Menggunakan Constructor Property Promotion (Fitur PHP modern untuk kode lebih ringkas)
  public function __construct(
    protected PerizinanWorkflowService $workflowService,
    protected NomorSuratService $nomorSuratService
  ) {
  }

  public function index(Request $request): View
  {
    $query = Perizinan::where('dinas_id', Auth::user()->dinas_id)
      ->with(['lembaga', 'jenisPerizinan']);

    // Pencarian Dinamis (ID, Nama Lembaga, NPSN)
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->whereHas('lembaga', function ($ql) use ($search) {
          $ql->where('nama_lembaga', 'like', "%{$search}%")
            ->orWhere('npsn', 'like', "%{$search}%");
        })->orWhere('id', 'like', "%{$search}%");
      });
    }

    // Filter Rentang Tanggal
    if ($request->filled('start_date')) {
      $query->whereDate('created_at', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
      $query->whereDate('created_at', '<=', $request->end_date);
    }

    // Filter Status
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    $perizinans = $query->latest()->paginate(15)->withQueryString();

    return view('backend.super_admin.perizinan.index', compact('perizinans'));
  }

  public function show(Perizinan $perizinan): View
  {
    $this->authorize('view', $perizinan);
    $perizinan->load(['lembaga', 'jenisPerizinan', 'discussions.user', 'dinas']);
    $perizinan->replaceVariables();

    return view('backend.super_admin.perizinan.show', compact('perizinan'));
  }

  public function approve(Perizinan $perizinan): RedirectResponse
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Pengajuan ini sudah diproses atau tidak dalam status Menunggu Verifikasi.');
    }

    $this->authorize('verify', $perizinan);

    try {
      DB::beginTransaction();

      $perizinan->update(['approved_at' => now()]);
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::DISETUJUI, 'Disetujui oleh Dinas');

      DB::commit();
      return back()->with('success', 'Perizinan berhasil disetujui.');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Gagal menyetujui perizinan: ' . $e->getMessage());
      return back()->with('error', 'Terjadi kesalahan sistem saat menyetujui perizinan.');
    }
  }

  public function needRevision(Request $request, Perizinan $perizinan): RedirectResponse
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Pengajuan ini sudah diproses atau tidak dalam status Menunggu Verifikasi.');
    }

    $this->authorize('verify', $perizinan);

    $validated = $request->validate([
      'catatan' => 'required|string|max:1000' // Tambahkan limit karakter untuk keamanan
    ]);

    try {
      DB::beginTransaction();

      $this->workflowService->transitionTo($perizinan, PerizinanStatus::PERBAIKAN, $validated['catatan']);

      DB::commit();
      return back()->with('success', 'Status berhasil diubah ke Perbaikan.');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Gagal memproses revisi: ' . $e->getMessage());
      return back()->with('error', 'Terjadi kesalahan sistem saat meminta revisi.');
    }
  }

  public function reject(Perizinan $perizinan): RedirectResponse
  {
    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value) {
      return back()->with('warning', 'Hanya pengajuan dengan status Diajukan yang dapat ditolak.');
    }

    $this->authorize('verify', $perizinan);

    try {
      DB::beginTransaction();

      $this->workflowService->transitionTo($perizinan, PerizinanStatus::DITOLAK, 'Pengajuan ditolak oleh Dinas');

      DB::commit();
      return back()->with('success', 'Pengajuan berhasil ditolak.');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Gagal menolak perizinan: ' . $e->getMessage());
      return back()->with('error', 'Terjadi kesalahan sistem saat menolak pengajuan.');
    }
  }

  public function showFinalisasi(Perizinan $perizinan)
  {
    $this->authorize('verify', $perizinan);

    $allowedStatuses = [
      PerizinanStatus::DISETUJUI->value,
      PerizinanStatus::SELESAI->value,
      PerizinanStatus::DIAJUKAN->value,
      PerizinanStatus::SIAP_DIAMBIL->value
    ];

    if (!in_array($perizinan->status, $allowedStatuses)) {
      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('warning', 'Pengajuan harus disetujui terlebih dahulu sebelum finalisasi.');
    }

    if (!$perizinan->nomor_surat) {
      $perizinan->nomor_surat_auto = $this->nomorSuratService->generate($perizinan);
    }

    // Render template agar live preview memiliki konten
    $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);
    $perizinan->replaceVariables();

    $activePreset = CetakPreset::where('is_active', true)->first();

    return view('backend.super_admin.perizinan.finalisasi', compact('perizinan', 'activePreset'));
  }

  public function release(Request $request, Perizinan $perizinan): RedirectResponse
  {
    $this->authorize('verify', $perizinan);

    // Validasi yang lebih ketat, pastikan mimes terisi untuk mencegah upload shell/script
    $validated = $request->validate([
      'nomor_surat' => 'required|string|max:255',
      'tanggal_terbit' => 'required|date',
      'pimpinan_nama' => 'required|string|max:255',
      'pimpinan_jabatan' => 'required|string|max:255',
      'pimpinan_pangkat' => 'nullable|string|max:255',
      'pimpinan_nip' => 'nullable|string|max:255',
      'stempel_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Hanya mengambil data yang lolos validasi (Keamanan Mass Assignment)
    $data = Arr::except($validated, ['stempel_img']);

    if ($request->hasFile('stempel_img')) {
      $data['stempel_img'] = $request->file('stempel_img')->store('perizinan/stempel', 'public');
    }

    try {
      DB::beginTransaction();

      $data['approved_at'] = $perizinan->approved_at ?? now();
      $perizinan->allowImmutableUpdate = true;
      $perizinan->update($data);

      if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
        $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat telah dirilis.');
      }

      DB::commit();
      return redirect()->route('super_admin.penerbitan.riwayat')
        ->with('success', 'Sertifikat perizinan berhasil dirilis ke lembaga.');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Gagal merilis sertifikat: ' . $e->getMessage());
      return back()->with('error', 'Terjadi kesalahan sistem saat merilis sertifikat.');
    }
  }

  public function autoRelease(Perizinan $perizinan): RedirectResponse
  {
    $this->authorize('verify', $perizinan);

    if ($perizinan->status !== PerizinanStatus::DIAJUKAN->value && $perizinan->status !== PerizinanStatus::DISETUJUI->value) {
      return back()->with('warning', 'Hanya pengajuan yang diajukan atau disetujui yang dapat diterbitkan otomatis.');
    }

    $dinas = Auth::user()->dinas;

    // Validasi null-safety jika relasi dinas tidak ditemukan
    if (!$dinas) {
      return back()->with('error', 'Data Dinas tidak ditemukan pada akun Anda.');
    }

    try {
      DB::beginTransaction();

      // Generate Nomor Otomatis jika belum ada
      if (!$perizinan->nomor_surat) {
        $perizinan->nomor_surat = $this->nomorSuratService->generate($perizinan);
      }

      // Isi dengan nilai default
      $perizinan->update([
        'approved_at' => $perizinan->approved_at ?? now(),
        'tanggal_terbit' => now(),
        'pimpinan_nama' => $dinas->pimpinan_nama,
        'pimpinan_jabatan' => $dinas->pimpinan_jabatan,
        'pimpinan_pangkat' => $dinas->pimpinan_pangkat,
        'pimpinan_nip' => $dinas->pimpinan_nip,
        'stempel_img' => $dinas->stempel_img,
      ]);

      // Transisi Workflow
      if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
        $this->workflowService->transitionTo($perizinan, PerizinanStatus::SIAP_DIAMBIL, 'Sertifikat otomatis diterbitkan oleh sistem.');
      }

      DB::commit();
      return redirect()->route('super_admin.perizinan.show', $perizinan)
        ->with('success', 'Sertifikat berhasil diterbitkan secara otomatis dengan data default.');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Gagal auto-release perizinan: ' . $e->getMessage());
      return back()->with('error', 'Gagal menerbitkan otomatis, hubungi tim IT.');
    }
  }
}