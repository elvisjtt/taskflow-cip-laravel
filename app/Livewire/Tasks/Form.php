<?php

namespace App\Livewire\Tasks;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskAttachment;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

use Throwable;

class Form extends Component
{

    use WithFileUploads;

    public $task;

    public $title = '';
    public $description = '';
    public $status = 'pendiente';
    public $priority = 'medio';
    public $due_date = '';
    public $category_id = '';
    public $selectedTags = [];

    public $newAttachments = [];

    public $isEditing = false;

    protected $rules = [
        'title' => 'required|string|max:10',
        'description' => 'nullable|string',
        'status' => 'required|in:pendiente,en_progreso,completado',
        'priority' => 'required|in:bajo,medio,alto',
        'due_date' => 'nullable|date|after:today',
        'category_id' => 'required|exists:categories,id',
        'selectedTags' => 'array',
        'selectedTags.*' => 'exists:tags,id',
        'newAttachments' => 'array',
        'newAttachments.*' => 'file|max:20480|mimes:jpg,jpeg,png,pdf',
    ];

    protected $messages = [
        'title.required' => 'El título es obligatorio.',
        'title.max' => 'El título no puede tener más de 10 caracteres.',
        'status.required' => 'El estado es obligatorio.',
        'status.in' => 'El estado seleccionado no es válido.',
        'priority.required' => 'La prioridad es obligatoria.',
        'priority.in' => 'La prioridad seleccionada no es válida.',
        'due_date.after' => 'La fecha de vencimiento debe ser posterior a hoy.',
        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría seleccionada no existe.',
        'selectedTags.*.exists' => 'Una de las etiquetas seleccionadas no existe.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {

        $this->validate();
        DB::beginTransaction();
        try {

            $taskData = [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
                'priority' => $this->priority,
                'due_date' => $this->due_date ?: null,
                'category_id' => $this->category_id,
                'user_id' => auth()->id(),
            ];

            if ($this->isEditing) {
                $this->task->update($taskData);
                $message = 'Tarea actualizada correctamente.';
            } else {
                $this->task = Task::create($taskData);
                $message = 'Tarea creada correctamente.';
            }

            $this->task->tags()->sync($this->selectedTags);
            // dd($this->newAttachments);
            if (!empty($this->newAttachments)) {
                foreach ($this->newAttachments as $uploadFile) {

                    $storedPath = $uploadFile->store('task/' . $this->task->id, 'public');

                    TaskAttachment::create([
                        'filename' => pathinfo($storedPath, PATHINFO_BASENAME),
                        'original_name' => $uploadFile->getClientOriginalName(),
                        'mime_type' => $uploadFile->getMimeType(),
                        'size' => $uploadFile->getSize(),
                        'path' => $storedPath,
                        'task_id' => $this->task->id,
                    ]);
                }
            }

            $this->task->load('attachments');
            session()->flash('message', $message);

            DB::commit();
            return redirect()->route('tasks.index');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', "Ocurrio un error al guardar la tarea");
            Log::error('Error al guardar tarea', [
                'error' =>  $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function mount($taskId = null)
    {
        if ($taskId) {
            $this->task = Task::findOrFail($taskId);
            $this->isEditing = true;

            $this->title = $this->task->title;
            $this->description = $this->task->description;
            $this->status = $this->task->status;
            $this->priority = $this->task->priority;
            $this->due_date = $this->task->due_date?->format('Y-m-d');
            $this->category_id = $this->task->category_id;
            $this->selectedTags = $this->task->tags->pluck('id')->toArray();
        }
    }


    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('livewire.tasks.form', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
