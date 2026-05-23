<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::orderBy('username')->get();
        return view('pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        $personils = \App\Models\Personil::orderBy('nama_lengkap')->get();
        return view('pengguna.create', compact('personils'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:pengguna,username',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['Admin', 'PA/KPA', 'PPK', 'Pokja', 'Pejabat Pengadaan'])],
            'status_aktif' => 'required|boolean',
            'personil_id' => 'nullable|exists:personil,personil_id',
        ]);

        Pengguna::create([
            'username' => $request->username,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
            'personil_id' => $request->personil_id,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(Pengguna $pengguna)
    {
        return view('pengguna.show', compact('pengguna'));
    }

    public function edit(Pengguna $pengguna)
    {
        $personils = \App\Models\Personil::orderBy('nama_lengkap')->get();
        return view('pengguna.edit', compact('pengguna', 'personils'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('pengguna')->ignore($pengguna->user_id, 'user_id')],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(['Admin', 'PA/KPA', 'PPK', 'Pokja', 'Pejabat Pengadaan'])],
            'status_aktif' => 'required|boolean',
            'personil_id' => 'nullable|exists:personil,personil_id',
        ]);

        $data = [
            'username' => $request->username,
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
            'personil_id' => $request->personil_id,
        ];

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
