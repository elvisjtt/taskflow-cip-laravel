<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique->randomElement([
                'Trabajo',
                'Personal',
                'Estudios',
                'Hogar',
                'Salud',
                'Finanzas',
                'Proyectos',
                'Reuniones',
                'Compras',
                'Viajes'
            ]),
            'color' => fake()->randomElement([
                '#3B82F6',
                '#EF4444',
                '#10B981',
                '#F59E0B',
                '#8B5CF6',
                '#EC4899',
                '#06B6D4',
                '#84CC16',
                '#F97316',
                '#6366F1'
            ]),
            'description' => fake()->sentence(10),
        ];
    }
}
