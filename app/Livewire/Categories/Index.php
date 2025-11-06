<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingCategory = null;
    
    // Form fields
    public $name = '';
    public $color = '#3B82F6';
    public $description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'color' => 'required|string|max:7',
        'description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'color.required' => 'El color es obligatorio.',
        'description.max' => 'La descripción no puede tener más de 500 caracteres.',
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

    public function editCategory($categoryId)
    {
        $this->editingCategory = Category::findOrFail($categoryId);
        $this->name = $this->editingCategory->name;
        $this->color = $this->editingCategory->color;
        $this->description = $this->editingCategory->description;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingCategory = null;
        $this->name = '';
        $this->color = '#3B82F6';
        $this->description = '';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
        ];

        if ($this->editingCategory) {
            $this->editingCategory->update($data);
            $message = 'Categoría actualizada correctamente.';
        } else {
            Category::create($data);
            $message = 'Categoría creada correctamente.';
        }

        session()->flash('message', $message);
        $this->closeModal();
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        // Verificar si tiene tareas asociadas
        if ($category->tasks()->count() > 0) {
            session()->flash('error', 'No se puede eliminar la categoría porque tiene tareas asociadas.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Categoría eliminada correctamente.');
    }

    public function render()
    {
        $categories = Category::withCount('tasks')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}