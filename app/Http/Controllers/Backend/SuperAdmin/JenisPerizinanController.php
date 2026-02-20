<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisPerizinanController extends Controller
{
    public function index()
    {
        $jenisPerizinans = JenisPerizinan::where('dinas_id', Auth::user()->dinas_id)
            ->latest()
            ->paginate(10);

        return view('backend.super_admin.jenis_perizinan.index', compact('jenisPerizinans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'masa_berlaku_nilai' => 'required|integer|min:1',
            'masa_berlaku_unit' => 'required|in:Tahun,Bulan',
            'deskripsi' => 'nullable|string',
        ]);

        JenisPerizinan::create([
            'dinas_id' => Auth::user()->dinas_id,
            'nama' => $request->nama,
            'kode' => $request->kode,
            'masa_berlaku_nilai' => $request->masa_berlaku_nilai,
            'masa_berlaku_unit' => $request->masa_berlaku_unit,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Jenis perizinan berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPerizinan $jenisPerizinan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'masa_berlaku_nilai' => 'required|integer|min:1',
            'masa_berlaku_unit' => 'required|in:Tahun,Bulan',
            'deskripsi' => 'nullable|string',
        ]);

        $jenisPerizinan->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'masa_berlaku_nilai' => $request->masa_berlaku_nilai,
            'masa_berlaku_unit' => $request->masa_berlaku_unit,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Jenis perizinan berhasil diperbarui.');
    }

    public function destroy(JenisPerizinan $jenisPerizinan)
    {
        $jenisPerizinan->delete();
        return back()->with('success', 'Jenis perizinan berhasil dihapus.');
    }

    public function template(JenisPerizinan $jenisPerizinan)
    {
        return view('backend.super_admin.jenis_perizinan.template', compact('jenisPerizinan'));
    }

    public function updateTemplate(Request $request, JenisPerizinan $jenisPerizinan)
    {
        $request->validate([
            'template_html' => 'required|string',
        ]);

        $jenisPerizinan->update([
            'template_html' => $request->template_html,
        ]);

        return redirect()->route('super_admin.jenis_perizinan.index')
            ->with('success', 'Template sertifikat berhasil diperbarui.');
    }
}
