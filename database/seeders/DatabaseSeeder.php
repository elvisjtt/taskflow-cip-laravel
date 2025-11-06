<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $adminUser = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@taskflow.com',
        ]);

        // Crear usuario de prueba
        $testUser = User::factory()->create([
            'name' => 'Usuario Test',
            'email' => 'test@example.com',
        ]);

        // Crear usuarios adicionales
        $users = User::factory(2)->create();
        $allUsers = collect([$adminUser, $testUser])->merge($users);

        // Crear categorías
        $categories = Category::factory(6)->create();

        // Crear tags
        $tags = Tag::factory(5)->create();

        // Crear tareas para cada usuario
        $allUsers->each(function ($user) use ($categories, $tags) {
            $userTasks = Task::factory(rand(3, 8))
                ->for($user)
                ->for($categories->random())
                ->create();

            // Asignar tags aleatorios a las tareas
            $userTasks->each(function ($task) use ($tags) {
                $task->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

            // Crear algunos archivos adjuntos para algunas tareas
            $userTasks->random(rand(1, 3))->each(function ($task) {
                TaskAttachment::factory(rand(1, 2))->for($task)->create();
            });
        });
    }
}
