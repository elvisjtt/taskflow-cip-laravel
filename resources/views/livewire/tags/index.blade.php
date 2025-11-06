<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Etiquetas</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Organiza tus tareas por etiquetas</p>
        </div>
        <flux:button wire:click="openModal" icon="plus">
            Nueva Etiqueta
        </flux:button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar etiquetas..." icon="magnifying-glass"
            class="max-w-md" />
    </div>


    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <flux:text class="font-medium text-green-800">
                {{ session('message') }}
            </flux:text>
        </div>
    @endif


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($tags as $tag)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 rounded-full border border-gray-300"
                            style="background-color: {{ $tag->color }}"></div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $tag->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $tag->tasks_count }} {{ $tag->tasks_count === 1 ? 'tarea' : 'tareas' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <flux:button wire:click="editTag({{ $tag->id }})" variant="ghost" size="sm"
                            icon="pencil">
                            Editar
                        </flux:button>

                        <flux:button wire:click="deleteTag({{ $tag->id }})"
                            wire:confirm="¿Estás seguro de que quieres eliminar esta etiqueta?" variant="danger"
                            size="sm" icon="trash">
                            Eliminar
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-center py-8">
                        <flux:icon.tag class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay etiquetas</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando una nueva
                            etiqueta.</p>
                        <div class="mt-6">
                            <flux:button wire:click="openModal" variant="primary" icon="plus">
                                Nueva Etiqueta
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>


    @if ($tags->hasPages())
        <div class="mt-6">
            {{ $tags->links() }}
        </div>
    @endif


    <flux:modal wire:model="showModal" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ $editingTag ? 'Editar Etiqueta' : 'Nueva Etiqueta' }}
            </flux:heading>
        </div>

        <div class="space-y-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model="name" placeholder="Nombre de la etiqueta" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Color</flux:label>
                <flux:input wire:model="color" type="color" placeholder="#000000" />
                <flux:error name="color" />
            </flux:field>
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button wire:click="closeModal" variant="ghost">
                Cancelar
            </flux:button>

            <flux:button wire:click="save" variant="primary">
                {{ $editingTag ? 'Actualizar' : 'Crear' }} Etiqueta
            </flux:button>
        </div>
    </flux:modal>
</div>
