<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class TagFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->unique->randomElement([
                'urgente',
                'importante',
                'fácil',
                'difícil',
                'rápido',
                'largo plazo',
                'revisión',
                'pendiente',
                'en progreso',
                'completado',
                'bug',
                'feature',
                'mejora',
                'documentación',
                'testing'
            ]),
            'color' => fake()->randomElement([
                '#DC2626',
                '#EA580C',
                '#D97706',
                '#65A30D',
                '#059669',
                '#0891B2',
                '#2563EB',
                '#7C3AED',
                '#C026D3',
                '#BE185D'
            ]),
        ];
    }
}
