<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    public function index()
    {
        $dinasId = Auth::user()->dinas_id;
        $downloads = Download::where('dinas_id', $dinasId)->latest()->paginate(10);
        return view('backend.super_admin.downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('backend.super_admin.downloads.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240', // max 10MB
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($request->judul) . '-' . time() . '.' . $extension;
        
        $path = $file->storeAs('public/downloads', $filename);

        Download::create([
            'dinas_id' => Auth::user()->dinas_id,
            'judul' => $request->judul,
            'file_path' => $path,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('super_admin.downloads.index')->with('success', 'File unduhan berhasil ditambahkan.');
    }

    public function edit(Download $download)
    {
        // Pastikan hanya dinas_id yang sama yang bisa edit
        if ($download->dinas_id !== Auth::user()->dinas_id) {
            abort(403);
        }
        
        return view('backend.super_admin.downloads.form', compact('download'));
    }

    public function update(Request $request, Download $download)
    {
        if ($download->dinas_id !== Auth::user()->dinas_id) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            if (Storage::exists($download->file_path)) {
                Storage::delete($download->file_path);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($request->judul) . '-' . time() . '.' . $extension;
            
            $path = $file->storeAs('public/downloads', $filename);
            $data['file_path'] = $path;
        }

        $download->update($data);

        return redirect()->route('super_admin.downloads.index')->with('success', 'File unduhan berhasil diperbarui.');
    }

    public function destroy(Download $download)
    {
        if ($download->dinas_id !== Auth::user()->dinas_id) {
            abort(403);
        }

        if (Storage::exists($download->file_path)) {
            Storage::delete($download->file_path);
        }

        $download->delete();

        return redirect()->route('super_admin.downloads.index')->with('success', 'File unduhan berhasil dihapus.');
    }
}
