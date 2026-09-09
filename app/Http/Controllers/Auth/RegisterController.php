<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $dinas = Dinas::first();
        if (!$dinas) {
            abort(404, 'Sistem belum dikonfigurasi (Dinas tidak ditemukan).');
        }

        $jenjangs = \App\Models\Jenjang::where('is_active', true)->orderBy('nama')->get();

        return view('auth.register', compact('dinas', 'jenjangs'));
    }

    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jenjang' => ['required', 'string'],
            'nama_lembaga' => ['required', 'string', 'max:255'],
            'npsn' => ['required', 'string', 'max:8'], // User wants NPSN as a field, let's make it required or nullable? The prompt says "sama npsn begitu", usually NPSN is required. Let's make it required.
        ];

        $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'jenjang.required' => 'Pilih jenjang pendidikan terlebih dahulu.',
            'nama_lembaga.required' => 'Nama lembaga wajib diisi.',
            'npsn.required' => 'NPSN wajib diisi.',
        ]);

        $dinas = \App\Models\Dinas::first();

        $lembaga = \App\Models\Lembaga::create([
            'nama_lembaga' => $request->nama_lembaga,
            'npsn' => $request->npsn,
            'jenjang' => $request->jenjang,
            'dinas_id' => $dinas?->id,
            'alamat' => '-',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lembaga_id' => $lembaga->id,
            'dinas_id' => $lembaga->dinas_id,
            'is_active' => false,
        ]);

        // Pastikan role 'admin_lembaga' ada, jika tidak buat
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin_lembaga']);
        $user->assignRole($role);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan Super Admin.');
    }
}
