<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personil>
 */
class PersonilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name,
            'nip' => $this->faker->numerify('##################'),
            'detail_skp' => $this->faker->paragraph,
            'skp_limit' => $this->faker->numberBetween(1, 10),
        ];
    }
}
