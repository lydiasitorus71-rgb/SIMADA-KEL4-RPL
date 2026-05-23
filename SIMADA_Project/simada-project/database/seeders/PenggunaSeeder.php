<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Personil;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['username' => 'admin', 'role' => 'Admin', 'nama' => 'Administrator SIMADA'],
            ['username' => 'pa_kpa_user', 'role' => 'PA/KPA', 'nama' => 'Pengguna Anggaran / Kuasa Pengguna Anggaran'],
            ['username' => 'ppk_user', 'role' => 'PPK', 'nama' => 'Pejabat Pembuat Komitmen'],
            ['username' => 'pokja_user', 'role' => 'Pokja', 'nama' => 'Anggota Pokja Pemilihan'],
            ['username' => 'pejabat_pengadaan', 'role' => 'Pejabat Pengadaan', 'nama' => 'Pejabat Pengadaan'],
        ];

        foreach ($roles as $roleData) {
            $personil = Personil::factory()->create([
                'nama_lengkap' => $roleData['nama'],
            ]);

            Pengguna::create([
                'username' => $roleData['username'],
                'password_hash' => Hash::make('password123'),
                'role' => $roleData['role'],
                'status_aktif' => true,
                'personil_id' => $personil->personil_id,
            ]);
        }

        Pengguna::factory(10)->create();
    }
}
