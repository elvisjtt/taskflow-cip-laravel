<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', 'in:pendiente,en_progreso,completado'],
            'priority' => ['sometimes', 'required', 'in:bajo,medio,alto'],
            'due_date' => ['sometimes', 'nullable', 'date', 'after:today'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'selectedTags' => ['sometimes', 'array'],
            'selectedTags.*' => ['sometimes', 'exists:tags,id'],
        ];
    }
}
