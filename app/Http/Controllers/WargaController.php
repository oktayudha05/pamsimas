<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wargas = Warga::orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        return view('wargas.index', compact('wargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer', 'min:1'],
            'rw' => ['required', 'integer', 'min:1'],
            'nomor_meteran' => ['required', 'string', 'max:255', 'unique:wargas,nomor_meteran'],
        ]);

        Warga::create($request->only('nama', 'rt', 'rw', 'nomor_meteran'));

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a warga.
     */
    public function edit(Warga $warga)
    {
        return view('wargas.edit', compact('warga'));
    }

    /**
     * Update the specified warga in storage.
     */
    public function update(Request $request, Warga $warga)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'integer', 'min:1'],
            'rw' => ['required', 'integer', 'min:1'],
            'nomor_meteran' => ['required', 'string', 'max:255', 'unique:wargas,nomor_meteran,' . $warga->id],
        ]);

        $warga->update($request->only('nama', 'rt', 'rw', 'nomor_meteran'));

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    /**
     * Remove the specified warga from storage.
     */
    public function destroy(Warga $warga)
    {
        $warga->delete();

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
