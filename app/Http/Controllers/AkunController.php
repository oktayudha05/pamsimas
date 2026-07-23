<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $akuns = User::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->get();

        return view('akuns.index', compact('akuns', 'search'));
    }

    public function create()
    {
        return view('akuns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:users,username'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'role' => ['required', 'in:pengelola,petugas'],
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('akuns.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $akun)
    {
        return view('akuns.edit', compact('akun'));
    }

    public function update(Request $request, User $akun)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:users,username,' . $akun->id],
            'role' => ['required', 'in:pengelola,petugas'],
        ]);

        $akun->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => [Password::defaults(), 'confirmed'],
            ]);
            $akun->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('akuns.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $akun)
    {
        if ($akun->id === Auth::id()) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $akun->delete();
        return redirect()->route('akuns.index')->with('success', 'Akun berhasil dihapus.');
    }
}