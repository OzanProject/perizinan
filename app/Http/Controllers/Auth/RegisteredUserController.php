<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lembaga_id' => ['nullable', 'exists:lembagas,id'],
            'nama_lembaga_baru' => ['required_if:lembaga_id,new', 'nullable', 'string', 'max:255'],
            'jenjang' => ['required', 'string', 'in:TK,SD,SMP,SMA,SMK,PKBM,LKP'],
            'npsn' => ['required_if:lembaga_id,new', 'nullable', 'string', 'size:8'],
        ]);

        $lembagaId = $request->lembaga_id;
        $dinas = \App\Models\Dinas::first(); // Default to first dinas

        // Auto create lembaga if not exists
        if ($lembagaId === 'new') {
            $lembaga = \App\Models\Lembaga::create([
                'dinas_id' => $dinas->id,
                'nama_lembaga' => $request->nama_lembaga_baru,
                'jenjang' => $request->jenjang,
                'npsn' => $request->npsn,
                'alamat' => '-',
            ]);
            $lembagaId = $lembaga->id;
        } else {
            // Get existing lembaga to get its dinas_id
            $existingLembaga = \App\Models\Lembaga::find($lembagaId);
            if ($existingLembaga) {
                $dinas = $existingLembaga->dinas;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lembaga_id' => $lembagaId,
            'dinas_id' => $dinas->id, // Assign dinas_id to user
            'is_active' => true,
        ]);

        $user->assignRole('admin_lembaga');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
