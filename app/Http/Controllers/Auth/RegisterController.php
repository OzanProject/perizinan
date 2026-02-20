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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'lembaga_id' => ['required', 'exists:lembagas,id'],
        ]);

        $lembaga = \App\Models\Lembaga::findOrFail($request->lembaga_id);

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
