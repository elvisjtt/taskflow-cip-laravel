<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'status' => fake()->randomElement(['pendiente', 'en_progreso', 'completado']),
            'priority' => fake()->randomElement(['bajo', 'medio', 'alto']),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+3 months'),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completado',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'en_progreso',
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn(array $attributes) => [
            'priority' => 'alto',
        ]);
    }
}
