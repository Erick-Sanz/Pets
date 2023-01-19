<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'descripcion' => $this->faker->name,
            'precio' => $this->faker->randomNumber(2),
            'raza' => $this->faker->name,
            'edad' => $this->faker->randomNumber(2),         
        ];
    }
}
