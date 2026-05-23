<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanPersonil extends Model
{
    use HasFactory;

    protected $table = 'penugasan_personil';
    protected $primaryKey = 'penugasan_id';

    protected $fillable = [
        'paket_id',
        'personil_id',
        'tanggal_mulai_tugas',
        'tanggal_selesai_tugas',
    ];

    public function paketPengadaan()
    {
        return $this->belongsTo(PaketPengadaan::class, 'paket_id', 'paket_id');
    }

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }
}
