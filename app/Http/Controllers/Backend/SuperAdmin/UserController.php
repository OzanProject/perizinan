<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('dinas_id', Auth::user()->dinas_id);

        if ($request->filled('role') && $request->role != 'Semua Role') {
            $query->role($request->role);
        }

        if ($request->filled('status') && $request->status != 'Semua Status') {
            $query->where('is_active', $request->status == 'Aktif');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->leftJoin('model_has_roles', function ($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.model_type', '=', User::class);
        })
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*')
            ->orderByRaw("CASE WHEN roles.name = 'super_admin' THEN 0 ELSE 1 END")
            ->orderBy('users.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $roles = Role::whereIn('name', ['super_admin', 'verifikator', 'head_of_dept', 'admin_lembaga'])->get();

        // Filter lembaga yang belum memiliki user dengan role admin_lembaga
        $lembagas = \App\Models\Lembaga::where('dinas_id', Auth::user()->dinas_id)
            ->whereDoesntHave('users', function ($q) {
                $q->role('admin_lembaga');
            })
            ->orderBy('nama_lembaga')
            ->get();

        return view('backend.super_admin.users.index', compact('users', 'roles', 'lembagas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nip' => 'nullable|string|max:50',
            'role' => 'required|exists:roles,name',
            'lembaga_id' => 'nullable|exists:lembagas,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nip' => $request->nip,
            'dinas_id' => Auth::user()->dinas_id,
            'lembaga_id' => $request->role == 'admin_lembaga' ? $request->lembaga_id : null,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nip' => 'nullable|string|max:50',
            'role' => 'required|exists:roles,name',
            'lembaga_id' => 'nullable|exists:lembagas,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'lembaga_id' => $request->role == 'admin_lembaga' ? $request->lembaga_id : null,
            'is_active' => $request->has('is_active'),
        ]);

        $user->syncRoles($request->role);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string|min:8']);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil direset.');
    }
}
