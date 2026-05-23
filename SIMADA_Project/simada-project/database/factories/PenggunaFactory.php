<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Personil;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengguna>
 */
class PenggunaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'password_hash' => Hash::make('password123'),
            'role' => fake()->randomElement(['Admin', 'PA/KPA', 'PPK', 'Pokja', 'Pejabat Pengadaan']),
            'status_aktif' => true,
            'personil_id' => Personil::factory(),
        ];
    }
}
