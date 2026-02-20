<?php

namespace App\Http\Controllers\Backend\AdminLembaga;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\JenisPerizinan;
use App\Models\Dokumen;
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
    $query = Perizinan::where('lembaga_id', Auth::user()->lembaga_id)
      ->with('jenisPerizinan');

    // Filtering
    if ($request->has('status') && $request->status != '') {
      $query->where('status', $request->status);
    }

    if ($request->has('search') && $request->search != '') {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('id', 'like', "%$search%")
          ->orWhereHas('jenisPerizinan', function ($jq) use ($search) {
            $jq->where('nama', 'like', "%$search%");
          });
      });
    }

    // Stats
    $stats = [
      'total' => Perizinan::where('lembaga_id', Auth::user()->lembaga_id)->count(),
      'pending' => Perizinan::where('lembaga_id', Auth::user()->lembaga_id)
        ->whereIn('status', [PerizinanStatus::DIAJUKAN->value, PerizinanStatus::PERBAIKAN->value])->count(),
      'siap_diambil' => Perizinan::where('lembaga_id', Auth::user()->lembaga_id)
        ->where('status', PerizinanStatus::SIAP_DIAMBIL->value)->count(),
      'selesai' => Perizinan::where('lembaga_id', Auth::user()->lembaga_id)
        ->where('status', PerizinanStatus::SELESAI->value)->count(),
    ];

    $perizinans = $query->latest()->paginate(6)->withQueryString();

    return view('backend.admin_lembaga.perizinan.index', compact('perizinans', 'stats'));
  }


  public function create()
  {
    $jenisPerizinans = JenisPerizinan::where('is_active', true)->get();
    return view('backend.admin_lembaga.perizinan.create', compact('jenisPerizinans'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'jenis_perizinan_id' => 'required|exists:jenis_perizinans,id',
    ]);

    // Cek jika ada pengajuan aktif
    $activeRequest = Perizinan::where('lembaga_id', Auth::user()->lembaga_id)
      ->whereNotIn('status', [PerizinanStatus::SELESAI->value])
      ->exists();

    if ($activeRequest) {
      return back()->with('error', 'Anda masih memiliki pengajuan yang aktif.');
    }

    $perizinan = Perizinan::create([
      'dinas_id' => Auth::user()->dinas_id,
      'lembaga_id' => Auth::user()->lembaga_id,
      'jenis_perizinan_id' => $request->jenis_perizinan_id,
      'status' => PerizinanStatus::DRAFT->value,
    ]);

    return redirect()->route('admin_lembaga.perizinan.edit', $perizinan)->with('success', 'Draf pengajuan berhasil dibuat. Silakan lengkapi berkas.');
  }

  public function submit(Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);

    // Validasi Kelengkapan Data Detail (Form Config)
    if ($perizinan->jenisPerizinan->form_config) {
      $missing = [];
      foreach ($perizinan->jenisPerizinan->form_config as $field) {
        if (($field['required'] ?? false) && (!isset($perizinan->perizinan_data[$field['name']]) || empty($perizinan->perizinan_data[$field['name']]))) {
          $missing[] = $field['label'];
        }
      }

      if (count($missing) > 0) {
        return back()->with('error', 'Mohon lengkapi informasi detail pengajuan: ' . implode(', ', $missing));
      }
    }

    try {
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::DIAJUKAN, 'Pengajuan oleh lembaga');
      return redirect()->route('admin_lembaga.perizinan.index')->with('success', 'Perizinan berhasil diajukan.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function show(Perizinan $perizinan)
  {
    $this->authorize('view', $perizinan);
    $perizinan->load(['jenisPerizinan', 'discussions.user', 'dokumens']);
    $statusValue = $perizinan->status;
    return view('backend.admin_lembaga.perizinan.show', compact('perizinan', 'statusValue'));
  }

  public function edit(Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);

    // Eager load everything needed for the multi-step form
    $perizinan->load(['jenisPerizinan.syarats', 'dokumens.syarat']);

    $syarats = $perizinan->jenisPerizinan->syarats;
    $uploadedDokumens = $perizinan->dokumens->keyBy('syarat_perizinan_id');

    return view('backend.admin_lembaga.perizinan.edit', compact('perizinan', 'syarats', 'uploadedDokumens'));
  }

  public function uploadDokumen(Request $request, Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);

    $request->validate([
      'syarat_id' => 'required|exists:syarat_perizinans,id',
      'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB limit
    ]);

    $syarat = \App\Models\SyaratPerizinan::findOrFail($request->syarat_id);

    // Hapus file lama jika ada
    $oldDokumen = $perizinan->dokumens()->where('syarat_perizinan_id', $syarat->id)->first();
    if ($oldDokumen) {
      \Illuminate\Support\Facades\Storage::delete($oldDokumen->path);
      $oldDokumen->delete();
    }

    $path = $request->file('file')->store('perizinan/' . $perizinan->id, 'public');

    $perizinan->dokumens()->create([
      'syarat_perizinan_id' => $syarat->id,
      'nama_file' => $syarat->nama_dokumen,
      'path' => $path,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Dokumen berhasil diunggah.',
      'path' => \Illuminate\Support\Facades\Storage::url($path),
      'id' => $perizinan->dokumens()->where('syarat_perizinan_id', $syarat->id)->first()->id,
    ]);
  }

  public function deleteDokumen(Perizinan $perizinan, Dokumen $dokumen)
  {
    $this->authorize('update', $perizinan);

    if ($dokumen->perizinan_id !== $perizinan->id) {
      return response()->json(['success' => false, 'message' => 'Dokumen tidak valid.'], 403);
    }

    \Illuminate\Support\Facades\Storage::disk('public')->delete($dokumen->path);
    $dokumen->delete();

    return response()->json([
      'success' => true,
      'message' => 'Dokumen berhasil dihapus.'
    ]);
  }

  public function update(Request $request, Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);
    // This is now likely empty or simple redirect as sub-steps handle the actual updates
    return redirect()->route('admin_lembaga.perizinan.show', $perizinan)->with('success', 'Data pengajuan telah diperbarui.');
  }

  public function updateData(Request $request, Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);

    $perizinan->update([
      'perizinan_data' => $request->input('data', []),
    ]);

    return back()->with('success', 'Informasi detail pengajuan berhasil disimpan.');
  }

  public function confirmTaken(Perizinan $perizinan)
  {
    $this->authorize('update', $perizinan);

    if ($perizinan->status !== PerizinanStatus::SIAP_DIAMBIL->value) {
      return back()->with('warning', 'Hanya pengajuan dengan status Siap Diambil yang dapat dikonfirmasi.');
    }

    try {
      $this->workflowService->transitionTo($perizinan, PerizinanStatus::SELESAI, 'Dokumen diterima oleh lembaga');
      return redirect()->route('admin_lembaga.perizinan.show', $perizinan)->with('success', 'Konfirmasi berhasil. Status pengajuan kini Selesai.');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  public function destroy(Perizinan $perizinan)
  {
    $this->authorize('delete', $perizinan);

    if (!in_array($perizinan->status, ['draft', 'perbaikan'])) {
      return back()->with('error', 'Pengajuan yang sedang diproses tidak dapat dihapus.');
    }

    $perizinan->delete();
    return redirect()->route('admin_lembaga.perizinan.index')->with('success', 'Pengajuan berhasil dihapus.');
  }
}
