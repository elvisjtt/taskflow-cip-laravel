<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Categorías</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Organiza tus tareas por categorías</p>
        </div>
        <flux:button wire:click="openModal" icon="plus">
            Nueva Categoría
        </flux:button>
    </div>


    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar categorías..." icon="magnifying-glass"
            class="max-w-md" />
    </div>


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


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories as $category)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 rounded-full border border-gray-300"
                            style="background-color: {{ $category->color }}"></div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h3>
                            @if ($category->description)
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    {{ Str::limit($category->description, 50) }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $category->tasks_count }} {{ $category->tasks_count === 1 ? 'tarea' : 'tareas' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 justify-end pt-2">
                    <flux:button wire:click="editCategory({{ $category->id }})" variant="ghost" size="sm"
                        icon="pencil">
                        Editar
                    </flux:button>

                    <flux:button wire:click="deleteCategory({{ $category->id }})"
                        wire:confirm="¿Estás seguro de que quieres eliminar esta categoría?" variant="danger"
                        size="sm" icon="trash">
                        Eliminar
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-center py-8">
                        <flux:icon.folder class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay categorías</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando una nueva categoría.
                        </p>
                        <div class="mt-6">
                            <flux:button wire:click="openModal" icon="plus">
                                Nueva Categoría
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>


    @if ($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif


    <flux:modal wire:model="showModal" class="max-w-md">
        <form wire:submit="save" class="space-y-6">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $editingCategory ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $editingCategory ? 'Modifica los detalles de la categoría' : 'Crea una nueva categoría' }}
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Nombre *</flux:label>
                        <flux:input wire:model="name" placeholder="Nombre de la categoría" />
                        <flux:error name="name" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Color *</flux:label>
                        <flux:input type="color" wire:model="color" />
                        <flux:error name="color" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Descripción</flux:label>
                        <flux:textarea wire:model="description" placeholder="Descripción de la categoría"
                            rows="3" />
                        <flux:error name="description" />
                    </flux:field>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <flux:button wire:click="closeModal" variant="ghost">
                    Cancelar
                </flux:button>
                <flux:button type="submit" icon="check">
                    {{ $editingCategory ? 'Actualizar' : 'Crear' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
