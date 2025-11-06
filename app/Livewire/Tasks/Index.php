<?php

namespace App\Livewire\Tasks;

use App\Models\Category;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedStatus = '';
    public $selectedPriority = '';

    public function render()
    {
        $query = Task::with(['category', 'user', 'tags', 'attachments']);


        $tasks = $query
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory && $this->selectedCategory !== '', function ($query) {
                $query->where('category_id', (int) $this->selectedCategory);
            })
            ->when($this->selectedStatus, function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when($this->selectedPriority, function ($query) {
                $query->where('priority', $this->selectedPriority);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::orderBy('name')
            ->distinct()
            ->get();
        // dd($tasks);
        return view(
            'livewire.tasks.index',
            [
                'tasks' => $tasks,
                'categories' => $categories,
            ]
        );
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->selectedStatus = '';
        $this->selectedPriority = '';
        $this->resetPage();
    }

    public function deleteTask($taskId)
    {

        $task = Task::findOrFail($taskId);

        if ($task->user_id !== auth()->id()) {
            return;
        }

        $task->delete();
    }
}
