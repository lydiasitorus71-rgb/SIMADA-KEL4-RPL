<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketPengadaan extends Model
{
    use HasFactory;

    protected $table = 'paket_pengadaan';
    protected $primaryKey = 'paket_id';

    protected $fillable = [
        'nama_paket',
        'metode_pengadaan',
        'opd_pemilik',
        'pagu_anggaran',
        'realisasi_kontrak',
        'waktu_pelaksanaan',
        'sumber_data',
        'id_referensi_eksternal',
        'status',
        'pemenang_tender',
        'rekomendasi_pemenang',
        'harga_penawaran',
    ];

    public function personils()
    {
        return $this->belongsToMany(Personil::class, 'penugasan_personil', 'paket_id', 'personil_id')
                    ->withPivot('penugasan_id', 'tanggal_mulai_tugas', 'tanggal_selesai_tugas')
                    ->withTimestamps();
    }
}
