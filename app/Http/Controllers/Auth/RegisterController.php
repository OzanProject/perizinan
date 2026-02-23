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
        $lembagas = \App\Models\Lembaga::all();
        return view('auth.register', compact('lembagas'));
    }

    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jenjang' => ['required', 'string'],
        ];

        // Conditional validation based on lembaga_id value
        if ($request->lembaga_id === 'new') {
            $rules['nama_lembaga_baru'] = ['required', 'string', 'max:255'];
            $rules['npsn'] = ['nullable', 'string', 'max:8'];
        } else {
            $rules['lembaga_id'] = ['required', 'exists:lembagas,id'];
        }

        $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok. Pastikan kedua password sama persis.',
            'jenjang.required' => 'Pilih jenjang pendidikan terlebih dahulu.',
            'lembaga_id.required' => 'Pilih lembaga atau tambah lembaga baru.',
            'lembaga_id.exists' => 'Lembaga yang dipilih tidak valid.',
            'nama_lembaga_baru.required' => 'Nama lembaga baru wajib diisi.',
        ]);

        // Resolve lembaga: existing or create new
        if ($request->lembaga_id === 'new') {
            $dinas = \App\Models\Dinas::first();

            $lembaga = \App\Models\Lembaga::create([
                'nama_lembaga' => $request->nama_lembaga_baru,
                'npsn' => $request->npsn,
                'jenjang' => $request->jenjang,
                'dinas_id' => $dinas?->id,
                'alamat' => '-',
            ]);
        } else {
            $lembaga = \App\Models\Lembaga::findOrFail($request->lembaga_id);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lembaga_id' => $lembaga->id,
            'dinas_id' => $lembaga->dinas_id,
        ]);

        // Pastikan role 'admin_lembaga' ada, jika tidak buat
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin_lembaga']);
        $user->assignRole($role);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Selamat datang! Akun lembaga Anda berhasil didaftarkan.');
    }
}
