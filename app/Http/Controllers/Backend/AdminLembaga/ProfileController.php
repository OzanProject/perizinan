<?php

namespace App\Http\Controllers\Backend\AdminLembaga;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  /**
   * Tampilkan profil lembaga.
   */
  public function index()
  {
    $lembaga = Auth::user()->lembaga;
    return view('backend.admin_lembaga.profile.index', compact('lembaga'));
  }

  /**
   * Update profil lembaga.
   */
  public function update(Request $request)
  {
    $lembaga = Auth::user()->lembaga;

    $request->validate([
      'nama_lembaga' => 'required|string|max:255',
      'npsn' => 'required|string|max:20|unique:lembagas,npsn,' . $lembaga->id,
      'alamat' => 'required|string',
      'sk_pendirian' => 'nullable|string|max:255',
      'tanggal_sk_pendirian' => 'nullable|date',
      'sk_izin_operasional' => 'nullable|string|max:255',
      'masa_berlaku_izin' => 'nullable|date',
      'akreditasi' => 'nullable|string|max:1',
      'visi' => 'nullable|string',
      'telepon' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'website' => 'nullable|url|max:255',
      'status_kepemilikan' => 'nullable|string|max:255',
      'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    $data = $request->except(['logo', 'sampul']);

    // Handle Logo Upload
    if ($request->hasFile('logo')) {
      if ($lembaga->logo) {
        Storage::disk('public')->delete($lembaga->logo);
      }
      $file = $request->file('logo');
      $path = $file->store('logos', 'public');
      $data['logo'] = $path;
    }

    // Handle Sampul Upload
    if ($request->hasFile('sampul')) {
      if ($lembaga->sampul) {
        Storage::disk('public')->delete($lembaga->sampul);
      }
      $file = $request->file('sampul');
      $path = $file->store('sampul', 'public');
      $data['sampul'] = $path;
    }

    $lembaga->update($data);

    return redirect()->back()->with('success', 'Profil lembaga berhasil diperbarui.');
  }
}
