<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use App\Http\Requests\StoreJenisPerizinanRequest;
use App\Http\Requests\UpdateJenisPerizinanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisPerizinanController extends Controller
{
    public function index()
    {
        $jenisPerizinans = Auth::user()->dinas->jenisPerizinans()
            ->latest()
            ->paginate(10);

        return view('backend.super_admin.jenis_perizinan.index', compact('jenisPerizinans'));
    }

    public function store(StoreJenisPerizinanRequest $request)
    {
        Auth::user()->dinas->jenisPerizinans()->create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'masa_berlaku_nilai' => $request->masa_berlaku_nilai,
            'masa_berlaku_unit' => $request->masa_berlaku_unit,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Jenis perizinan berhasil ditambahkan.');
    }

    public function update(UpdateJenisPerizinanRequest $request, JenisPerizinan $jenisPerizinan)
    {
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

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jenis_perizinans,id'
        ]);

        JenisPerizinan::whereIn('id', $request->ids)
            ->where('dinas_id', Auth::user()->dinas_id)
            ->delete();

        return back()->with('success', count($request->ids) . ' jenis perizinan berhasil dihapus.');
    }
}
