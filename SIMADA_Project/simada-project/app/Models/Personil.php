<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personil extends Model
{
    use HasFactory;

    protected $table = 'personil';
    protected $primaryKey = 'personil_id';

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'detail_skp',
        'skp_limit',
    ];

    public function paketPengadaans()
    {
        return $this->belongsToMany(PaketPengadaan::class, 'penugasan_personil', 'personil_id', 'paket_id')
                    ->withPivot('penugasan_id', 'tanggal_mulai_tugas', 'tanggal_selesai_tugas')
                    ->withTimestamps();
    }
}
