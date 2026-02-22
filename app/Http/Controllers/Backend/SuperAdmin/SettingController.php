<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $dinas = $user->dinas;
    return view('backend.super_admin.settings.index', compact('user', 'dinas'));
  }

  public function updateProfile(Request $request)
  {
    $user = Auth::user();
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
      'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->hasFile('photo')) {
      // Hapus foto lama jika ada
      if ($user->photo) {
        Storage::disk('public')->delete($user->photo);
      }

      // Store file and get path relative to storage/app/public
      $path = $request->file('photo')->store('profiles', 'public');
      $user->photo = $path;
    }

    $user->save();

    return back()->with('success', 'Profil berhasil diperbarui.');
  }

  public function updateApp(Request $request)
  {
    $dinas = Auth::user()->dinas;

    $request->validate([
      'app_name' => 'required|string|max:255',
      'kode_surat' => 'required|string|max:50',
      'pimpinan_nama' => 'required|string|max:255',
      'pimpinan_jabatan' => 'required|string|max:255',
      'pimpinan_pangkat' => 'nullable|string|max:255',
      'pimpinan_nip' => 'nullable|string|max:255',
      'footer_text' => 'nullable|string',
      'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
      'stempel_img' => 'nullable|image|mimes:png|max:2048', // PNG only for transparency
      'watermark_img' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
      'watermark_enabled' => 'nullable|string', // Checkbox
      'watermark_opacity' => 'required|numeric|min:0.01|max:1',
      'watermark_size' => 'required|integer|min:50|max:1000',
    ]);

    $dinas->app_name = $request->app_name;
    $dinas->kode_surat = $request->kode_surat;
    $dinas->pimpinan_nama = $request->pimpinan_nama;
    $dinas->pimpinan_jabatan = $request->pimpinan_jabatan;
    $dinas->pimpinan_pangkat = $request->pimpinan_pangkat;
    $dinas->pimpinan_nip = $request->pimpinan_nip;
    $dinas->footer_text = $request->footer_text;
    $dinas->watermark_enabled = $request->has('watermark_enabled');
    $dinas->watermark_opacity = $request->watermark_opacity;
    $dinas->watermark_size = $request->watermark_size;

    if ($request->hasFile('logo')) {
      // Hapus logo lama jika ada
      if ($dinas->logo) {
        $oldLogo = str_replace('public/', '', $dinas->logo);
        Storage::disk('public')->delete($oldLogo);
      }
      $path = $request->file('logo')->store('logos', 'public');
      $dinas->logo = $path;
    }

    if ($request->hasFile('stempel_img')) {
      if ($dinas->stempel_img) {
        $oldStempel = str_replace('public/', '', $dinas->stempel_img);
        Storage::disk('public')->delete($oldStempel);
      }
      $dinas->stempel_img = $request->file('stempel_img')->store('stempels', 'public');
    }

    if ($request->hasFile('watermark_img')) {
      if ($dinas->watermark_img) {
        $oldWatermark = str_replace('public/', '', $dinas->watermark_img);
        Storage::disk('public')->delete($oldWatermark);
      }
      $dinas->watermark_img = $request->file('watermark_img')->store('watermarks', 'public');
    }

    $dinas->save();

    return back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
  }

  public function updateSecurity(Request $request)
  {
    $request->validate([
      'current_password' => 'required|current_password',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();
    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', 'Kata sandi berhasil diubah.');
  }

  public function clearCache()
  {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');

    return back()->with('success', 'Cache sistem berhasil dibersihkan.');
  }

  public function backupDb()
  {
    $host = env('DB_HOST', '127.0.0.1');
    $username = env('DB_USERNAME', 'root');
    $password = env('DB_PASSWORD', '');
    $database = env('DB_DATABASE');

    $filename = "backup-" . $database . "-" . date('Y-m-d-H-i-s') . ".sql";

    // Command for mysqldump
    // Using --column-statistics=0 for compatibility if needed, but standard should work
    $command = "mysqldump --user={$username} --password=\"{$password}\" --host={$host} {$database}";

    return response()->streamDownload(function () use ($command) {
      $handle = popen($command, 'r');
      while (!feof($handle)) {
        echo fread($handle, 1024);
      }
      pclose($handle);
    }, $filename, [
      'Content-Type' => 'application/x-sql',
    ]);
  }

  public function restoreDb(Request $request)
  {
    $request->validate([
      'db_file' => 'required|file',
    ]);

    if (!$request->file('db_file')->getClientOriginalExtension() == 'sql') {
      return back()->with('error', 'Format file tidak valid. Harap unggah file .sql');
    }

    try {
      $path = $request->file('db_file')->getRealPath();
      $sql = file_get_contents($path);

      // Clear database first or just run? 
      // Safe strategy: Run the SQL. Usually dumps contain DROP TABLE IF EXISTS.
      DB::unprepared($sql);

      return back()->with('success', 'Database berhasil dipulihkan dari file.');
    } catch (\Exception $e) {
      return back()->with('error', 'Terjadi kesalahan saat memulihkan database: ' . $e->getMessage());
    }
  }
}
