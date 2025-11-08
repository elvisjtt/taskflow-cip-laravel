<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pendiente,en_progreso,completado'],
            'priority' => ['required', 'in:bajo,medio,alto'],
            'due_date' => ['nullable', 'date', 'after:today'],
            'category_id' => ['required', 'exists:categories,id'],
            'selectedTags' => ['array'],
            'selectedTags.*' => ['exists:tags,id'],
        ];
    }
}
