<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Mis Tareas</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Gestiona y organiza tus tareas</p>
        </div>
        <flux:button href="{{ route('tasks.create') }}" icon="plus" wire:navigate>
            Nueva Tarea
        </flux:button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="space-y-4">
            <!-- Search -->
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar tareas..." icon="magnifying-glass"
                class="max-w-md" />

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Categoría</flux:label>
                        <flux:select wire:model.live="selectedCategory" placeholder="Todas las categorías">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Estado</flux:label>
                        <flux:select wire:model.live="selectedStatus" placeholder="Todos los estados">
                            <option value="pendiente">Pendiente</option>
                            <option value="en_progreso">En Progreso</option>
                            <option value="completado">Completada</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Prioridad</flux:label>
                        <flux:select wire:model.live="selectedPriority" placeholder="Todas las prioridades">
                            <option value="bajo">Baja</option>
                            <option value="medio">Media</option>
                            <option value="alto">Alta</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div class="flex items-end">
                    <flux:button wire:click="clearFilters" variant="ghost" icon="x-mark" class="w-full">
                        Limpiar Filtros
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <flux:text class="font-medium text-green-800">
                {{ session('message') }}
            </flux:text>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <flux:text class="font-medium text-red-800">
                {{ session('error') }}
            </flux:text>
        </div>
    @endif


    <!-- Tasks Grid -->
    @if ($tasks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($tasks as $task)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-4">
                        <!-- Task Header -->
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $task->title }}</h3>
                            @if ($task->priority)
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($task->priority === 'alto') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($task->priority === 'medio') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        @if ($task->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ Str::limit($task->description, 100) }}
                            </p>
                        @endif

                        @if ($task->user->name)
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-bold">
                                {{ $task->user->name }}
                            </p>
                        @endif

                        <!-- Metadata -->
                        <div class="space-y-2">
                            <!-- Category -->
                            @if ($task->category)
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full border border-gray-300"
                                        style="background-color: {{ $task->category->color }}"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $task->category->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Due Date -->
                            @if ($task->due_date)
                                <div class="flex items-center space-x-2">
                                    <flux:icon.calendar class="w-3 h-3 text-gray-400" />
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif

                            <!-- Tags -->
                            @if ($task->tags->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <flux:icon.tag class="w-3 h-3 text-gray-400" />
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($task->tags->take(2) as $tag)
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach

                                        @if ($task->tags->count() > 2)
                                            <span
                                                class="px-2 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded text-xs">
                                                +{{ $task->tags->count() - 2 }} más
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($task->attachments && $task->attachments->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <flux:icon.paper-clip class="w-3 h-3" />
                                    <span class="text-xs text-gray-400">{{ $task->attachments->count() }}
                                        archivos</span>
                                </div>
                            @endif

                            <!-- Status -->
                            <div
                                class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($task->status === 'completado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($task->status === 'en_progreso') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                    @if ($task->status === 'completado')
                                        Completada
                                    @elseif($task->status === 'en_progreso')
                                        En Progreso
                                    @else
                                        Pendiente
                                    @endif
                                </span>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <flux:button href="{{ route('tasks.edit', $task) }}" variant="ghost" size="sm"
                                        icon="pencil" wire:navigate>
                                        Editar
                                    </flux:button>

                                    <flux:button wire:click="deleteTask({{ $task->id }})"
                                        wire:confirm="¿Estás seguro de que quieres eliminar esta tarea?"
                                        variant="danger" size="sm" icon="trash">
                                        Eliminar
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-center py-8">
                <flux:icon.clipboard-document-list class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay tareas</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($search || $selectedCategory || $selectedStatus || $selectedPriority)
                        No se encontraron tareas que coincidan con los filtros seleccionados.
                    @else
                        Comienza creando tu primera tarea.
                    @endif
                </p>
                <div class="mt-6">
                    @if ($search || $selectedCategory || $selectedStatus || $selectedPriority)
                        <flux:button wire:click="clearFilters" icon="x-mark">
                            Limpiar Filtros
                        </flux:button>
                    @else
                        <flux:button href="{{ route('tasks.create') }}" icon="plus" wire:navigate>
                            Nueva Tarea
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
