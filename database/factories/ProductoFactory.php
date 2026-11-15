<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => 'Vela '.fake()->word(),
            'descripcion' => fake()->sentence(12),
            'precio' => fake()->numberBetween(5000, 30000),
            'edad' => fake()->randomElement(Producto::EDADES),
            'intereses' => ['bienestar', 'hogar'],
            'ubicacion' => 'Santiago, Chile',
        ];
    }
}
