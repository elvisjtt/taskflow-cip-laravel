<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;


class TaskAttachmentFactory extends Factory
{

    public function definition(): array
    {
        $extensions = ['pdf', 'doc', 'docx', 'jpg', 'png', 'txt', 'xlsx'];
        $extension = fake()->randomElement($extensions);
        $filename = fake()->uuid() . '.' . $extension;
        $originalName = fake()->words(2, true) . '.' . $extension;

        return [
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $this->getMimeType($extension),
            'size' => fake()->numberBetween(1024, 5242880), // 1KB to 5MB
            'path' => 'attachments/' . $filename,
            'task_id' => Task::factory(),
        ];
    }

    private function getMimeType(string $extension): string
    {
        return match ($extension) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'txt' => 'text/plain',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }
}
