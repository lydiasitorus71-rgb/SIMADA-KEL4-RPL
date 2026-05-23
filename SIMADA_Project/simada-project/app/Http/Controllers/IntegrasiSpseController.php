<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketPengadaan;
use Illuminate\Support\Facades\Http;

class IntegrasiSpseController extends Controller
{
    public function index()
    {
        // To avoid deadlocking the local single-threaded dev server and avoid corrupting the global request context,
        // we fake the HTTP request so that Http::withToken() returns the mock data directly without a network call.
        $mockData = json_decode(file_get_contents(database_path('data/spse_paket.json')), true);
        Http::fake([
            url('/api/v1/spse/paket-tender') => Http::response($mockData, 200)
        ]);

        $response = Http::withToken('TOKEN-SIMADA-KEBUMEN-2026')->get(url('/api/v1/spse/paket-tender'));
        $paketSpse = $response->json() ?? [];

        return view('integrasi.spse', compact('paketSpse'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_paket' => 'required|string',
            'nama_paket' => 'required|string',
            'pagu' => 'required|numeric',
            'metode_pengadaan' => 'required|string',
            'opd_pemilik' => 'nullable|string',
            'waktu_pelaksanaan' => 'nullable|string',
        ]);

        // Check if package already imported
        $exists = PaketPengadaan::where('id_referensi_eksternal', $request->id_paket)->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Paket ini sudah di-import sebelumnya.');
        }

        PaketPengadaan::create([
            'nama_paket' => $request->nama_paket,
            'metode_pengadaan' => $request->metode_pengadaan,
            'pagu_anggaran' => $request->pagu,
            'opd_pemilik' => $request->opd_pemilik,
            'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
            'sumber_data' => 'SPSE API',
            'id_referensi_eksternal' => $request->id_paket,
        ]);

        return redirect()->route('integrasi-spse.index')->with('success', 'Paket berhasil di-import ke SIMADA.');
    }
}
