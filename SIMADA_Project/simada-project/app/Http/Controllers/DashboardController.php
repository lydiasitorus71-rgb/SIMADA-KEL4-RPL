<?php

namespace App\Http\Controllers;

use App\Models\PaketPengadaan;
use App\Models\Personil;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $totalPaket = PaketPengadaan::count();
        $totalPaguLokal = PaketPengadaan::sum('pagu_anggaran');
        $totalPersonil = Personil::count();

        $assignedPakets = collect();
        if (in_array($user->role, ['Pokja', 'Pejabat Pengadaan']) && $user->personil_id) {
            $assignedPakets = PaketPengadaan::whereHas('personils', function ($query) use ($user) {
                $query->where('personil.personil_id', $user->personil_id)
                      ->where('tanggal_selesai_tugas', '>=', now()->format('Y-m-d'));
            })->with(['personils' => function ($query) use ($user) {
                $query->where('personil.personil_id', $user->personil_id);
            }])->get();
        }

        return view('dashboard', compact('totalPaket', 'totalPaguLokal', 'totalPersonil', 'assignedPakets'));
    }
}
