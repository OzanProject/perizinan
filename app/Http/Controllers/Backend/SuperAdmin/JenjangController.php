<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use Illuminate\Http\Request;

class JenjangController extends Controller
{
    public function index()
    {
        $jenjangs = Jenjang::latest()->get();
        return view('backend.super_admin.jenjang.index', compact('jenjangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenjangs,nama',
            'seksi' => 'required|in:dikmas,paud',
            'is_active' => 'boolean'
        ]);

        Jenjang::create([
            'nama' => strtoupper($request->nama),
            'seksi' => $request->seksi,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('super_admin.jenjang.index')->with('success', 'Jenjang berhasil ditambahkan.');
    }

    public function update(Request $request, Jenjang $jenjang)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenjangs,nama,' . $jenjang->id,
            'seksi' => 'required|in:dikmas,paud',
            'is_active' => 'boolean'
        ]);

        $jenjang->update([
            'nama' => strtoupper($request->nama),
            'seksi' => $request->seksi,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('super_admin.jenjang.index')->with('success', 'Jenjang berhasil diperbarui.');
    }

    public function destroy(Jenjang $jenjang)
    {
        $jenjang->delete();
        return redirect()->route('super_admin.jenjang.index')->with('success', 'Jenjang berhasil dihapus.');
    }
}
