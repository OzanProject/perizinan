<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LembagaController extends Controller
{
  public function index(Request $request)
  {
    $query = Lembaga::where('dinas_id', Auth::user()->dinas_id)
      ->with([
        'users' => function ($q) {
          $q->role('admin_lembaga');
        }
      ]);

    if ($request->has('search')) {
      $search = $request->get('search');
      $query->where(function ($q) use ($search) {
        $q->where('nama_lembaga', 'like', "%{$search}%")
          ->orWhere('npsn', 'like', "%{$search}%");
      });
    }

    $lembagas = $query->latest()->paginate(10);

    return view('backend.super_admin.lembaga.index', compact('lembagas'));
  }

  public function create()
  {
    return view('backend.super_admin.lembaga.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama_lembaga' => 'required|string|max:255',
      'jenjang' => 'required|string',
      'npsn' => 'required|string|max:20|unique:lembagas,npsn',
      'alamat' => 'required|string',
      'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
      'npsn.unique' => 'NPSN ini sudah terdaftar di sistem. Jika tidak muncul di daftar Anda, kemungkinan terdaftar di Dinas lain.',
    ]);

    $data = $request->except('logo');
    $data['dinas_id'] = Auth::user()->dinas_id;

    if ($request->hasFile('logo')) {
      $file = $request->file('logo');
      $filename = 'logo_' . $request->npsn . '.' . $file->getClientOriginalExtension();
      $path = $file->storeAs('logos', $filename, 'public');
      $data['logo'] = $path;
    }

    Lembaga::create($data);

    return redirect()->route('super_admin.lembaga.index')->with('success', 'Lembaga berhasil ditambahkan.');
  }

  public function show(Lembaga $lembaga)
  {
    $this->authorizeSuperAdmin($lembaga);
    $lembaga->load(['users', 'perizinans.jenisPerizinan']);
    return view('backend.super_admin.lembaga.show', compact('lembaga'));
  }

  public function edit(Lembaga $lembaga)
  {
    $this->authorizeSuperAdmin($lembaga);
    return view('backend.super_admin.lembaga.edit', compact('lembaga'));
  }

  public function update(Request $request, Lembaga $lembaga)
  {
    $this->authorizeSuperAdmin($lembaga);

    $request->validate([
      'nama_lembaga' => 'required|string|max:255',
      'jenjang' => 'required|string',
      'npsn' => 'required|string|max:20|unique:lembagas,npsn,' . $lembaga->id,
      'alamat' => 'required|string',
      'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $request->except('logo');

    if ($request->hasFile('logo')) {
      // Delete old logo
      if ($lembaga->logo) {
        Storage::disk('public')->delete($lembaga->logo);
      }

      $file = $request->file('logo');
      $filename = 'logo_' . $request->npsn . '.' . $file->getClientOriginalExtension();
      $path = $file->storeAs('logos', $filename, 'public');
      $data['logo'] = $path;
    }

    $lembaga->update($data);

    return redirect()->route('super_admin.lembaga.index')->with('success', 'Data lembaga berhasil diperbarui.');
  }

  public function destroy(Lembaga $lembaga)
  {
    $this->authorizeSuperAdmin($lembaga);

    if ($lembaga->users()->exists() || $lembaga->perizinans()->exists()) {
      return back()->with('error', 'Lembaga tidak dapat dihapus karena memiliki data terkait (user/pengajuan).');
    }

    if ($lembaga->logo) {
      Storage::disk('public')->delete($lembaga->logo);
    }

    $lembaga->delete();

    return redirect()->route('super_admin.lembaga.index')->with('success', 'Lembaga berhasil dihapus.');
  }

  private function authorizeSuperAdmin(Lembaga $lembaga)
  {
    if ($lembaga->dinas_id !== Auth::user()->dinas_id) {
      abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
    }
  }
}
