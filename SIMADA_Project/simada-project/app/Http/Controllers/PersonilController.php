<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use Illuminate\Http\Request;

class PersonilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personils = Personil::orderBy('nama_lengkap')->get();
        return view('personil.index', compact('personils'));
    }

    public function create()
    {
        return view('personil.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'detail_skp' => 'nullable|string',
            'skp_limit' => 'nullable|integer|min:1',
        ]);

        Personil::create($validated);
        return redirect()->route('personil.index')->with('success', 'Personil berhasil ditambahkan.');
    }

    public function show($id)
    {
        $personil = Personil::findOrFail($id);
        return view('personil.show', compact('personil'));
    }

    public function edit($id)
    {
        $personil = Personil::findOrFail($id);
        return view('personil.edit', compact('personil'));
    }

    public function update(Request $request, $id)
    {
        $personil = Personil::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'detail_skp' => 'nullable|string',
            'skp_limit' => 'nullable|integer|min:1',
        ]);

        $personil->update($validated);
        return redirect()->route('personil.index')->with('success', 'Personil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $personil = Personil::findOrFail($id);
        $personil->delete();
        return redirect()->route('personil.index')->with('success', 'Personil berhasil dihapus.');
    }
}
