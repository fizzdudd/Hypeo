<?php

namespace Database\Factories;

use App\Models\Campana;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campana>
 */
class CampanaFactory extends Factory
{
    public function definition(): array
    {
        $piezas = [
            'eslogan' => fake()->sentence(5),
            'publicacion' => fake()->paragraph(),
            'hashtags' => ['#velas', '#hechoenchile'],
            'cta' => 'Escríbenos por DM',
            'idea_imagen' => fake()->sentence(8),
        ];

        return [
            'producto_id' => Producto::factory(),
            'red' => fake()->randomElement(array_keys(Campana::REDES)),
            'tono' => fake()->randomElement(array_keys(Campana::TONOS)),
            ...$piezas,
            'texto_ia' => $piezas,
            'editada' => false,
        ];
    }
}
