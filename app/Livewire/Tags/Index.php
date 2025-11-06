<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingTag = null;
    
    // Form fields
    public $name = '';
    public $color = '#3B82F6';

    protected $rules = [
        'name' => 'required|string|max:255',
        'color' => 'required|string|max:7',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'color.required' => 'El color es obligatorio.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editTag($tagId)
    {
        $this->editingTag = Tag::findOrFail($tagId);
        $this->name = $this->editingTag->name;
        $this->color = $this->editingTag->color;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingTag = null;
        $this->name = '';
        $this->color = '#3B82F6';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'color' => $this->color,
        ];

        if ($this->editingTag) {
            $this->editingTag->update($data);
            $message = 'Etiqueta actualizada correctamente.';
        } else {
            Tag::create($data);
            $message = 'Etiqueta creada correctamente.';
        }

        session()->flash('message', $message);
        $this->closeModal();
    }

    public function deleteTag($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        
        // Verificar si tiene tareas asociadas
        if ($tag->tasks()->count() > 0) {
            session()->flash('error', 'No se puede eliminar la etiqueta porque tiene tareas asociadas.');
            return;
        }

        $tag->delete();
        session()->flash('message', 'Etiqueta eliminada correctamente.');
    }

    public function render()
    {
        $tags = Tag::withCount('tasks')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.tags.index', [
            'tags' => $tags,
        ]);
    }
}