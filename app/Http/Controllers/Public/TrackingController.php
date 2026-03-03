<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use App\Enums\PerizinanStatus;

class TrackingController extends Controller
{
  public function index()
  {
    // Alternatif jika ingin halaman khusus tracking, tapi kita gunakan AJAX di landing page
    return redirect()->route('landing');
  }

  public function track(Request $request)
  {
    $search = $request->query('id'); // Variabel tetap 'id' dari frontend agar tidak merusak AJAX, tapi isinya sekarang query string (NPSN/Nama)

    if (!$search) {
      return response()->json([
        'success' => false,
        'message' => 'Silakan masukkan NPSN atau Nama Lembaga.'
      ], 400);
    }

    // Cari perizinan berdasarkan NPSN atau Nama Lembaga
    $results = Perizinan::with(['jenisPerizinan', 'lembaga', 'dinas', 'discussions.user'])
      ->whereHas('lembaga', function ($q) use ($search) {
        $q->where('npsn', $search)
          ->orWhere('nama_lembaga', 'like', "%$search%");
      })
      ->latest()
      ->get();

    if ($results->isEmpty()) {
      return response()->json([
        'success' => false,
        'message' => 'Data pengajuan untuk NPSN/Lembaga tersebut tidak ditemukan.'
      ], 404);
    }

    $formattedData = $results->map(function ($perizinan) {
      $status = PerizinanStatus::from($perizinan->status);
      $latestDiscussion = $perizinan->discussions()->latest()->first();

      return [
        'id' => $perizinan->id,
        'lembaga' => $perizinan->lembaga->nama_lembaga,
        'jenis' => $perizinan->jenisPerizinan->nama,
        'status' => $status->label(),
        'status_code' => $perizinan->status,
        'status_color' => $status->color(),
        'tanggal' => $perizinan->created_at->translatedFormat('d F Y'),
        'keterangan' => $perizinan->catatan_verifikator ?? $status->description(),
        'latest_discussion' => $latestDiscussion ? [
          'user' => $latestDiscussion->user->name,
          'message' => $latestDiscussion->message,
          'date' => $latestDiscussion->created_at->diffForHumans(),
        ] : null,
        'is_ready' => in_array($perizinan->status, [PerizinanStatus::SIAP_DIAMBIL->value, PerizinanStatus::SELESAI->value]),
        'nomor_surat' => $perizinan->nomor_surat,
      ];
    });

    return response()->json([
      'success' => true,
      'data' => $formattedData
    ]);
  }
}
