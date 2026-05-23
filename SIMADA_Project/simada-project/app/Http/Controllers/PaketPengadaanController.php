<?php

namespace App\Http\Controllers;

use App\Models\PaketPengadaan;
use App\Models\Personil;
use App\Models\PenugasanPersonil;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PaketPengadaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();

        if (in_array($user->role, ['Admin', 'PA/KPA', 'PPK'])) {
            $pakets = PaketPengadaan::orderBy('nama_paket')->get();
        } else {
            // Pokja or Pejabat Pengadaan
            if ($user->personil_id) {
                $pakets = PaketPengadaan::whereHas('personils', function ($query) use ($user) {
                    $query->where('personil.personil_id', $user->personil_id);
                })->orderBy('nama_paket')->get();
            } else {
                $pakets = collect();
            }
        }

        return view('paket_pengadaan.index', compact('pakets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paket_pengadaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'metode_pengadaan' => 'nullable|string|max:255',
            'opd_pemilik' => 'nullable|string|max:255',
            'pagu_anggaran' => 'nullable|numeric',
            'realisasi_kontrak' => 'nullable|numeric',
            'waktu_pelaksanaan' => 'nullable|string|max:255',
            'sumber_data' => 'nullable|string|max:255',
            'id_referensi_eksternal' => 'nullable|string|max:255',
        ]);

        $paket = PaketPengadaan::create($validated);
        return redirect()->route('paket-pengadaan.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paket = PaketPengadaan::with('personils')->findOrFail($id);
        
        $personils = Personil::all()->map(function ($personil) {
            $active_assignments_count = PenugasanPersonil::where('personil_id', $personil->personil_id)
                ->where('tanggal_selesai_tugas', '>=', now()->format('Y-m-d'))
                ->whereHas('paketPengadaan', function($q) {
                    $q->where('status', '!=', 'Penetapan Pemenang');
                })
                ->count();
            $personil->sisaSkp = $personil->skp_limit - $active_assignments_count;
            return $personil;
        });

        return view('paket_pengadaan.show', compact('paket', 'personils'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $paket = PaketPengadaan::findOrFail($id);
        return view('paket_pengadaan.edit', compact('paket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $paket = PaketPengadaan::findOrFail($id);

        $validated = $request->validate([
            'nama_paket' => 'sometimes|required|string|max:255',
            'metode_pengadaan' => 'nullable|string|max:255',
            'opd_pemilik' => 'nullable|string|max:255',
            'pagu_anggaran' => 'nullable|numeric',
            'realisasi_kontrak' => 'nullable|numeric',
            'waktu_pelaksanaan' => 'nullable|string|max:255',
            'sumber_data' => 'nullable|string|max:255',
            'id_referensi_eksternal' => 'nullable|string|max:255',
        ]);

        $paket->update($validated);
        return redirect()->route('paket-pengadaan.index')->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $paket = PaketPengadaan::findOrFail($id);
        $paket->delete();
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Paket berhasil dihapus.']);
        }

        return redirect()->route('paket-pengadaan.index')->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * Assign a personil to this paket pengadaan.
     * Prevents overlapping schedules for the personil.
     */
    public function assignPersonil(Request $request, $paket_id): JsonResponse
    {
        $request->validate([
            'personil_id' => 'required|exists:personil,personil_id',
            'tanggal_mulai_tugas' => 'required|date',
            'tanggal_selesai_tugas' => 'required|date|after_or_equal:tanggal_mulai_tugas',
        ]);

        $paket = PaketPengadaan::findOrFail($paket_id);
        
        if ($paket->status === 'Penetapan Pemenang') {
            return response()->json([
                'message' => 'Akses Ditolak: Tidak dapat mengubah susunan Personil karena pemenang tender telah ditetapkan.',
                'errors' => [
                    'status' => ['Paket sudah dalam tahap akhir.']
                ]
            ], 422);
        }

        $personil_id = $request->personil_id;
        $mulai = $request->tanggal_mulai_tugas;
        $selesai = $request->tanggal_selesai_tugas;
        
        $personil = Personil::findOrFail($personil_id);
        
        // We want to make sure this assignment doesn't cause the personil to exceed their SKP limit DURING the assignment window.
        // First, check how many other assignments overlap with this date window.
        $concurrent_assignments_count = PenugasanPersonil::where('personil_id', $personil_id)
            ->where('tanggal_selesai_tugas', '>=', $mulai)
            ->where('tanggal_mulai_tugas', '<=', $selesai)
            ->whereHas('paketPengadaan', function($q) {
                $q->where('status', '!=', 'Penetapan Pemenang');
            })
            ->count();

        if ($concurrent_assignments_count >= $personil->skp_limit) {
            return response()->json([
                'message' => 'The selected personil has reached their SKP capacity (max ' . $personil->skp_limit . ') during the requested dates.',
                'errors' => [
                    'schedule' => ['Personil is already assigned to ' . $concurrent_assignments_count . ' tasks overlapping with these dates.']
                ]
            ], 422);
        }

        $paket->personils()->attach($personil_id, [
            'tanggal_mulai_tugas' => $mulai,
            'tanggal_selesai_tugas' => $selesai,
        ]);

        return response()->json(['message' => 'Personil successfully assigned to Paket Pengadaan.']);
    }

    public function removePersonil(Request $request, $paket_id, $personil_id)
    {
        $paket = PaketPengadaan::findOrFail($paket_id);
        if ($paket->status === 'Penetapan Pemenang') {
            return response()->json([
                'message' => 'Akses Ditolak: Tidak dapat mengubah susunan Personil karena pemenang tender telah ditetapkan.',
                'errors' => [
                    'status' => ['Paket sudah dalam tahap akhir.']
                ]
            ], 422);
        }

        $paket->personils()->detach($personil_id);
        return response()->json(['message' => 'Personil berhasil dihapus dari penugasan.']);
    }

    public function updatePersonil(Request $request, $paket_id, $personil_id): JsonResponse
    {
        $request->validate([
            'tanggal_mulai_tugas' => 'required|date',
            'tanggal_selesai_tugas' => 'required|date|after_or_equal:tanggal_mulai_tugas',
        ]);

        $paket = PaketPengadaan::findOrFail($paket_id);
        
        if ($paket->status === 'Penetapan Pemenang') {
            return response()->json([
                'message' => 'Akses Ditolak: Tidak dapat mengubah susunan Personil karena pemenang tender telah ditetapkan.',
                'errors' => [
                    'status' => ['Paket sudah dalam tahap akhir.']
                ]
            ], 422);
        }

        $mulai = $request->tanggal_mulai_tugas;
        $selesai = $request->tanggal_selesai_tugas;

        $personil = Personil::findOrFail($personil_id);

        $concurrent_assignments_count = PenugasanPersonil::where('personil_id', $personil_id)
            ->where('paket_id', '!=', $paket_id)
            ->where('tanggal_selesai_tugas', '>=', $mulai)
            ->where('tanggal_mulai_tugas', '<=', $selesai)
            ->whereHas('paketPengadaan', function($q) {
                $q->where('status', '!=', 'Penetapan Pemenang');
            })
            ->count();

        if ($concurrent_assignments_count >= $personil->skp_limit) {
            return response()->json([
                'message' => 'The selected personil has reached their SKP capacity (max ' . $personil->skp_limit . ') during the requested dates.',
                'errors' => [
                    'schedule' => ['Personil is already assigned to ' . $concurrent_assignments_count . ' tasks overlapping with these dates.']
                ]
            ], 422);
        }

        $paket->personils()->updateExistingPivot($personil_id, [
            'tanggal_mulai_tugas' => $mulai,
            'tanggal_selesai_tugas' => $selesai,
        ]);

        return response()->json(['message' => 'Assignment dates updated successfully.']);
    }

    /**
     * Submit a winner recommendation by Pokja/Pejabat Pengadaan.
     */
    public function ajukanReview(Request $request, $paket_id): RedirectResponse
    {
        $paket = PaketPengadaan::findOrFail($paket_id);
        
        // Ensure user is assigned to this package
        $user = auth()->user();
        if (in_array($user->role, ['Pokja', 'Pejabat Pengadaan'])) {
            $isAssigned = $paket->personils->contains('personil_id', $user->personil_id);
            if (!$isAssigned) {
                return redirect()->back()->with('error', 'Anda tidak ditugaskan pada paket ini.');
            }
        }
        
        $validated = $request->validate([
            'rekomendasi_pemenang' => 'required|string|max:255',
            'harga_penawaran' => 'required|numeric|min:0',
        ]);

        if ($paket->status === 'Persiapan') {
            $paket->update([
                'status' => 'Review',
                'rekomendasi_pemenang' => $validated['rekomendasi_pemenang'],
                'harga_penawaran' => $validated['harga_penawaran'],
            ]);
            return redirect()->back()->with('success', 'Rekomendasi Review berhasil diajukan.');
        }

        return redirect()->back()->with('error', 'Paket tidak dalam status Persiapan.');
    }

    /**
     * Finalize the winner selection by PPK.
     */
    public function tetapkanPemenang(Request $request, $paket_id): RedirectResponse
    {
        $paket = PaketPengadaan::findOrFail($paket_id);
        
        $validated = $request->validate([
            'pemenang_tender' => 'required|string|max:255',
            'realisasi_kontrak' => 'required|numeric|min:0',
        ]);

        if ($paket->status === 'Review') {
            $paket->update([
                'pemenang_tender' => $validated['pemenang_tender'],
                'realisasi_kontrak' => $validated['realisasi_kontrak'],
                'status' => 'Penetapan Pemenang'
            ]);
            return redirect()->back()->with('success', 'Pemenang berhasil ditetapkan.');
        }

        return redirect()->back()->with('error', 'Paket tidak dalam status Review.');
    }
}
