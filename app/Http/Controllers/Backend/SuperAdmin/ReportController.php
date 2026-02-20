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

    return view('backend.super_admin.report.index', compact('stats', 'monthlyTrend', 'lembagaStats', 'listLembaga'));
  }

  public function exportExcel()
  {
    $data = $this->getLembagaData();
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LembagaStatsExport($data), 'Laporan_Perizinan_' . date('Y-m-d') . '.xlsx');
  }

  public function exportPdf()
  {
    $dinasId = Auth::user()->dinas_id;
    $stats = $this->getOverviewStats($dinasId);
    $lembagaStats = $this->getLembagaData();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.super_admin.report.pdf', compact('stats', 'lembagaStats'));
    return $pdf->download('Laporan_Perizinan_' . date('Y-m-d') . '.pdf');
  }

  private function getOverviewStats($dinasId)
  {
    return [
      'total' => Perizinan::where('dinas_id', $dinasId)->where('status', '!=', PerizinanStatus::DRAFT)->count(),
      'approved' => Perizinan::where('dinas_id', $dinasId)->whereIn('status', [
        PerizinanStatus::DISETUJUI,
        PerizinanStatus::SIAP_DIAMBIL,
        PerizinanStatus::SELESAI
      ])->count(),
      'rejected' => Perizinan::where('dinas_id', $dinasId)->where('status', PerizinanStatus::DITOLAK)->count(),
      'processing' => Perizinan::where('dinas_id', $dinasId)->whereIn('status', [
        PerizinanStatus::DIAJUKAN,
        PerizinanStatus::PERBAIKAN
      ])->count(),
    ];
  }

  private function getLembagaData()
  {
    return Lembaga::where('dinas_id', Auth::user()->dinas_id)
      ->withCount([
        'perizinans as total_pengajuan' => function ($query) {
          $query->where('status', '!=', PerizinanStatus::DRAFT);
        },
        'perizinans as selesai' => function ($query) {
          $query->whereIn('status', [PerizinanStatus::DISETUJUI, PerizinanStatus::SIAP_DIAMBIL, PerizinanStatus::SELESAI]);
        },
        'perizinans as proses' => function ($query) {
          $query->whereIn('status', [PerizinanStatus::DIAJUKAN, PerizinanStatus::PERBAIKAN]);
        }
      ])
      ->orderBy('total_pengajuan', 'desc')
      ->get();
  }
}
