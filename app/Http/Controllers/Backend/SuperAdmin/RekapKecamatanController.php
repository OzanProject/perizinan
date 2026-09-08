<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapKecamatanController extends Controller
{
    public function index(Request $request)
    {
        $dinasId = Auth::user()->dinas_id;

        // Query to get recap per kecamatan
        $rekapData = DB::table('lembagas')
            ->where('lembagas.dinas_id', $dinasId)
            ->whereNotNull('lembagas.kecamatan')
            ->where('lembagas.kecamatan', '!=', '')
            ->leftJoin('perizinans', 'lembagas.id', '=', 'perizinans.lembaga_id')
            ->select(
                'lembagas.kecamatan',
                DB::raw('COUNT(DISTINCT lembagas.id) as total_lembaga'),
                DB::raw('COUNT(perizinans.id) as total_perizinan'),
                DB::raw('SUM(CASE WHEN perizinans.status IN ("disetujui", "siap_diambil", "selesai") THEN 1 ELSE 0 END) as perizinan_disetujui')
            )
            ->groupBy('lembagas.kecamatan')
            ->orderBy('lembagas.kecamatan', 'asc')
            ->get();

        return view('backend.super_admin.laporan.rekap_kecamatan', compact('rekapData'));
    }
}
