<?php

namespace App\Http\Controllers;

use App\Models\PaketPengadaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakDokumenController extends Controller
{
    public function unduhBeritaAcara($id, $jenis)
    {
        $paket = PaketPengadaan::with('personils')->findOrFail($id);
        
        $pdf = Pdf::loadView('cetak.berita_acara', compact('paket', 'jenis'));
        
        $filename = 'Berita_Acara_' . ucfirst($jenis) . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $paket->nama_paket) . '.pdf';
        
        return $pdf->download($filename);
    }
}
