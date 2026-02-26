<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Lembaga;
use App\Enums\PerizinanStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
  public function index(Request $request)
  {
    $dinasId = Auth::user()->dinas_id;
    $startDate = $request->start_date;
    $endDate = $request->end_date;
    $lembagaId = $request->lembaga_id;

    // Base query for stats
    $baseQuery = Perizinan::where('dinas_id', $dinasId)
      ->where('status', '!=', PerizinanStatus::DRAFT);

    if ($startDate) {
      $baseQuery->whereDate('created_at', '>=', $startDate);
    }
    if ($endDate) {
      $baseQuery->whereDate('created_at', '<=', $endDate);
    }
    if ($lembagaId) {
      $baseQuery->where('lembaga_id', $lembagaId);
    }

    // Overview Stats
    $stats = [
      'total' => (clone $baseQuery)->count(),
      'approved' => (clone $baseQuery)->whereIn('status', [
        PerizinanStatus::DISETUJUI,
        PerizinanStatus::SIAP_DIAMBIL,
        PerizinanStatus::SELESAI
      ])->count(),
      'rejected' => (clone $baseQuery)->where('status', PerizinanStatus::DITOLAK)->count(),
      'processing' => (clone $baseQuery)->whereIn('status', [
        PerizinanStatus::DIAJUKAN,
        PerizinanStatus::PERBAIKAN
      ])->count(),
    ];

    // Monthly Trend (Last 7 Months) - Trend usually covers recent time, independent of range filter for context
    $months = collect();
    for ($i = 6; $i >= 0; $i--) {
      $months->push(now()->subMonths($i)->format('Y-m'));
    }

    $trendData = Perizinan::where('dinas_id', $dinasId)
      ->where('status', '!=', PerizinanStatus::DRAFT)
      ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
      ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as total'))
      ->groupBy('month')
      ->get()
      ->pluck('total', 'month');

    $monthlyTrend = $months->mapWithKeys(function ($month) use ($trendData) {
      return [now()->parse($month . '-01')->translatedFormat('M') => $trendData->get($month, 0)];
    });

    // Institution Stats Query
    $lembagaQuery = Lembaga::where('dinas_id', $dinasId);

    if ($lembagaId) {
      $lembagaQuery->where('id', $lembagaId);
    }

    $lembagaStats = $lembagaQuery->withCount([
      'perizinans as total_pengajuan' => function ($query) use ($startDate, $endDate) {
        $query->where('status', '!=', PerizinanStatus::DRAFT);
        if ($startDate)
          $query->whereDate('created_at', '>=', $startDate);
        if ($endDate)
          $query->whereDate('created_at', '<=', $endDate);
      },
      'perizinans as selesai' => function ($query) use ($startDate, $endDate) {
        $query->whereIn('status', [PerizinanStatus::DISETUJUI, PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI]);
        if ($startDate)
          $query->whereDate('created_at', '>=', $startDate);
        if ($endDate)
          $query->whereDate('created_at', '<=', $endDate);
      },
      'perizinans as proses' => function ($query) use ($startDate, $endDate) {
        $query->whereIn('status', [PerizinanStatus::DIAJUKAN, PerizinanStatus::PERBAIKAN]);
        if ($startDate)
          $query->whereDate('created_at', '>=', $startDate);
        if ($endDate)
          $query->whereDate('created_at', '<=', $endDate);
      }
    ])
      ->orderBy('total_pengajuan', 'desc')
      ->paginate(10);

    $listLembaga = Lembaga::where('dinas_id', $dinasId)->orderBy('nama_lembaga')->get();

    // Detailed perizinan list
    $perizinans = (clone $baseQuery)
      ->with(['lembaga', 'jenisPerizinan'])
      ->latest()
      ->paginate(15, ['*'], 'p_page');

    return view('backend.super_admin.report.index', compact('stats', 'monthlyTrend', 'lembagaStats', 'listLembaga', 'perizinans'));
  }

  public function exportExcel(Request $request)
  {
    $filters = $request->only(['start_date', 'end_date', 'lembaga_id']);
    $data = $this->getLembagaData($filters);
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LembagaStatsExport($data), 'Laporan_Perizinan_' . date('Y-m-d') . '.xlsx');
  }

  public function exportPdf(Request $request)
  {
    $user = Auth::user();
    $dinas = $user->dinas;
    $filters = $request->only(['start_date', 'end_date', 'lembaga_id']);

    $stats = $this->getOverviewStats($user->dinas_id, $filters);
    $lembagaStats = $this->getLembagaData($filters);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.super_admin.report.pdf', compact('stats', 'lembagaStats', 'dinas', 'filters'));
    return $pdf->download('Laporan_Perizinan_' . date('Y-m-d') . '.pdf');
  }

  private function getOverviewStats($dinasId, $filters = [])
  {
    $query = Perizinan::where('dinas_id', $dinasId)->where('status', '!=', PerizinanStatus::DRAFT);

    if (!empty($filters['start_date'])) {
      $query->whereDate('created_at', '>=', $filters['start_date']);
    }
    if (!empty($filters['end_date'])) {
      $query->whereDate('created_at', '<=', $filters['end_date']);
    }
    if (!empty($filters['lembaga_id'])) {
      $query->where('lembaga_id', $filters['lembaga_id']);
    }

    return [
      'total' => (clone $query)->count(),
      'approved' => (clone $query)->whereIn('status', [
        PerizinanStatus::DISETUJUI,
        PerizinanStatus::SIAP_DIAMBIL,
        PerizinanStatus::SELESAI
      ])->count(),
      'rejected' => (clone $query)->where('status', PerizinanStatus::DITOLAK)->count(),
      'processing' => (clone $query)->whereIn('status', [
        PerizinanStatus::DIAJUKAN,
        PerizinanStatus::PERBAIKAN
      ])->count(),
    ];
  }

  private function getLembagaData($filters = [])
  {
    $query = Lembaga::where('dinas_id', Auth::user()->dinas_id);

    if (!empty($filters['lembaga_id'])) {
      $query->where('id', $filters['lembaga_id']);
    }

    return $query->withCount([
      'perizinans as total_pengajuan' => function ($q) use ($filters) {
        $q->where('status', '!=', PerizinanStatus::DRAFT);
        if (!empty($filters['start_date']))
          $q->whereDate('created_at', '>=', $filters['start_date']);
        if (!empty($filters['end_date']))
          $q->whereDate('created_at', '<=', $filters['end_date']);
      },
      'perizinans as selesai' => function ($q) use ($filters) {
        $q->whereIn('status', [PerizinanStatus::DISETUJUI, PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI]);
        if (!empty($filters['start_date']))
          $q->whereDate('created_at', '>=', $filters['start_date']);
        if (!empty($filters['end_date']))
          $q->whereDate('created_at', '<=', $filters['end_date']);
      },
      'perizinans as proses' => function ($q) use ($filters) {
        $q->whereIn('status', [PerizinanStatus::DIAJUKAN, PerizinanStatus::PERBAIKAN]);
        if (!empty($filters['start_date']))
          $q->whereDate('created_at', '>=', $filters['start_date']);
        if (!empty($filters['end_date']))
          $q->whereDate('created_at', '<=', $filters['end_date']);
      }
    ])
      ->orderBy('total_pengajuan', 'desc')
      ->get();
  }
}
