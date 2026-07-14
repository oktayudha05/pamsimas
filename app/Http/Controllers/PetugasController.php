<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PetugasController extends Controller
{
    /**
     * Display a listing of petugas.
     */
    public function index()
    {
        $petugases = User::where('role', 'petugas')
            ->orderBy('nama')
            ->get();

        return view('petugases.index', compact('petugases'));
    }

    /**
     * Show the form for creating a new petugas.
     */
    public function create()
    {
        return view('petugases.create');
    }

    /**
     * Store a newly created petugas in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:users,username'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()->route('petugases.index')->with('success', 'Akun petugas berhasil dibuat.');
    }

    /**
     * Show the form for editing petugas.
     */
    public function edit(User $petugase)
    {
        return view('petugases.edit', ['petugas' => $petugase]);
    }

    /**
     * Update the specified petugas in storage.
     */
    public function update(Request $request, User $petugase)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:users,username,' . $petugase->id],
        ]);

        $petugase->update([
            'nama' => $request->nama,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => [Password::defaults(), 'confirmed'],
            ]);
            $petugase->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('petugases.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Remove the specified petugas from storage.
     */
    public function destroy(User $petugase)
    {
        // Prevent self-deletion
        if ($petugase->id === Auth::id()) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $petugase->delete();

        return redirect()->route('petugases.index')->with('success', 'Akun petugas berhasil dihapus.');
    }
}
