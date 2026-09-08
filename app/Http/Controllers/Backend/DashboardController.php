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

    if ($user->hasAnyRole(['super_admin', 'bidang_dikmas', 'bidang_paud'])) {
      return $this->superAdminDashboard($user);
    } elseif ($user->hasRole('admin_lembaga')) {
      return $this->adminLembagaDashboard($user);
    }

    return view('backend.dashboard');
  }

  private function superAdminDashboard($user)
  {
    $baseLembagaQuery = Lembaga::where('dinas_id', $user->dinas_id);
    $basePerizinanQuery = Perizinan::where('dinas_id', $user->dinas_id);

    // Filter berdasarkan role bidang
    if ($user->hasRole('bidang_dikmas')) {
      $baseLembagaQuery->whereIn('jenjang', ['PKBM', 'LKP']);
      $basePerizinanQuery->whereHas('lembaga', function ($q) {
        $q->whereIn('jenjang', ['PKBM', 'LKP']);
      });
    } elseif ($user->hasRole('bidang_paud')) {
      $baseLembagaQuery->whereIn('jenjang', ['KB', 'TK', 'PAUD', 'SPS', 'TPA']);
      $basePerizinanQuery->whereHas('lembaga', function ($q) {
        $q->whereIn('jenjang', ['KB', 'TK', 'PAUD', 'SPS', 'TPA']);
      });
    }

    $stats = [
      'total_lembaga' => (clone $baseLembagaQuery)->count(),
      'pending' => (clone $basePerizinanQuery)
        ->where('status', PerizinanStatus::DIAJUKAN->value)
        ->count(),
      'disetujui' => (clone $basePerizinanQuery)
        ->whereIn('status', [
          PerizinanStatus::DISETUJUI->value,
          PerizinanStatus::SIAP_DIAMBIL->value,
          PerizinanStatus::SELESAI->value
        ])->count(),
      'perbaikan' => (clone $basePerizinanQuery)
        ->where('status', PerizinanStatus::PERBAIKAN->value)
        ->count(),
    ];

    $pengajuanTerbaru = (clone $basePerizinanQuery)
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
      $count = (clone $basePerizinanQuery)
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
