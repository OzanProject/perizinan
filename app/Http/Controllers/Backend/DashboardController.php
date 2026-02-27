<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Perizinan;
use App\Enums\PerizinanStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    if ($user->hasRole('super_admin')) {
      return $this->superAdminDashboard($user);
    } elseif ($user->hasRole('admin_lembaga')) {
      return $this->adminLembagaDashboard($user);
    }

    return view('backend.dashboard');
  }

  private function superAdminDashboard($user)
  {
    $stats = [
      'total_lembaga' => Lembaga::where('dinas_id', $user->dinas_id)->count(),
      'pending' => Perizinan::where('dinas_id', $user->dinas_id)
        ->where('status', PerizinanStatus::DIAJUKAN->value)
        ->count(),
      'disetujui' => Perizinan::where('dinas_id', $user->dinas_id)
        ->where('status', PerizinanStatus::DISETUJUI->value)
        ->count(),
      'perbaikan' => Perizinan::where('dinas_id', $user->dinas_id)
        ->where('status', PerizinanStatus::PERBAIKAN->value)
        ->count(),
    ];

    $pengajuanTerbaru = Perizinan::where('dinas_id', $user->dinas_id)
      ->with(['lembaga', 'jenisPerizinan'])
      ->latest()
      ->limit(5)
      ->get();

    // Chart Data: 6 Bulan Terakhir
    $chartData = [
      'labels' => [],
      'data' => []
    ];

    for ($i = 5; $i >= 0; $i--) {
      $date = now()->subMonths($i);
      $monthName = $date->translatedFormat('F');
      $count = Perizinan::where('dinas_id', $user->dinas_id)
        ->whereMonth('created_at', $date->month)
        ->whereYear('created_at', $date->year)
        ->count();

      $chartData['labels'][] = $monthName;
      $chartData['data'][] = $count;
    }

    return view('backend.super_admin.dashboard', compact('stats', 'pengajuanTerbaru', 'chartData'));
  }

  private function adminLembagaDashboard($user)
  {
    $stats = [
      'total_pengajuan' => Perizinan::where('lembaga_id', $user->lembaga_id)->count(),
      'sedang_proses' => Perizinan::where('lembaga_id', $user->lembaga_id)
        ->whereIn('status', [
          PerizinanStatus::DIAJUKAN->value,
          PerizinanStatus::PERBAIKAN->value
        ])->count(),
      'disetujui' => Perizinan::where('lembaga_id', $user->lembaga_id)
        ->whereIn('status', [
          PerizinanStatus::DISETUJUI->value,
          PerizinanStatus::SIAP_DIAMBIL->value,
          PerizinanStatus::SELESAI->value
        ])->count(),
    ];

    $perizinan = Perizinan::where('lembaga_id', $user->lembaga_id)
      ->with(['jenisPerizinan', 'dokumens', 'discussions'])
      ->latest()
      ->first();

    return view('backend.admin_lembaga.dashboard', compact('perizinan', 'stats'));
  }
}
