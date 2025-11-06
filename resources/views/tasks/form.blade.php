<x-layouts.app :title="__('Tareas')">
    <livewire:tasks.form :taskId="isset($task) ? $task->id : null" />
</x-layouts.app>
