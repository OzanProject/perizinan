<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use App\Models\SyaratPerizinan;
use Illuminate\Http\Request;

class JenisPerizinanSyaratController extends Controller
{
    public function index(JenisPerizinan $jenisPerizinan)
    {
        $syarats = $jenisPerizinan->syarats()->latest()->get();
        return view('backend.super_admin.jenis_perizinan.syarat', compact('jenisPerizinan', 'syarats'));
    }

    public function store(Request $request, JenisPerizinan $jenisPerizinan)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_file' => 'required|in:pdf,image,all',
        ]);

        $jenisPerizinan->syarats()->create([
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'tipe_file' => $request->tipe_file,
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Persyaratan berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPerizinan $jenisPerizinan, SyaratPerizinan $syarat)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_file' => 'required|in:pdf,image,all',
        ]);

        $syarat->update([
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'tipe_file' => $request->tipe_file,
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Persyaratan berhasil diperbarui.');
    }

    public function destroy(JenisPerizinan $jenisPerizinan, SyaratPerizinan $syarat)
    {
        $syarat->delete();
        return back()->with('success', 'Persyaratan berhasil dihapus.');
    }
}
