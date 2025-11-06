<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ $isEditing ? 'Editar Tarea' : 'Nueva Tarea' }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $isEditing ? 'Modifica los detalles de la tarea' : 'Crea una nueva tarea' }}
            </p>
        </div>
        <flux:button href="{{ route('tasks.index') }}" variant="ghost" icon="arrow-left" wire:navigate>
            Volver
        </flux:button>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Title -->
            <div>
                <flux:field>
                    <flux:label>Título *</flux:label>
                    <flux:input wire:model.live="title" placeholder="Ingresa el título de la tarea" />
                    <flux:error name="title" />
                </flux:field>
            </div>

            <!-- Description -->
            <div>
                <flux:field>
                    <flux:label>Descripción</flux:label>
                    <flux:textarea wire:model="description" placeholder="Describe la tarea..." rows="4" />
                    <flux:error name="description" />
                </flux:field>
            </div>

            <!-- Category and Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Categoría *</flux:label>
                        <flux:select wire:model="category_id" placeholder="Selecciona una categoría">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="category_id" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Prioridad *</flux:label>
                        <flux:select wire:model="priority">
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </flux:select>
                        <flux:error name="priority" />
                    </flux:field>
                </div>
            </div>

            <!-- Status and Due Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Estado *</flux:label>
                        <flux:select wire:model="status">
                            <option value="pending">Pendiente</option>
                            <option value="in_progress">En Progreso</option>
                            <option value="completed">Completada</option>
                            <option value="cancelled">Cancelada</option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Fecha de Vencimiento</flux:label>
                        <flux:input type="date" wire:model="due_date" />
                        <flux:error name="due_date" />
                    </flux:field>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <flux:field>
                    <flux:label>Etiquetas</flux:label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                        @foreach ($tags as $tag)
                            <label
                                class="flex items-center space-x-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $tag->color }}">
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <flux:error name="selectedTags" />
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Adjuntos</flux:label>
                    <div class="space-y-3">
                        <input type="file" wire:model="newAttachments" multiple
                            class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                        <p class="text-xs text-gray-500 dark:text-gray-400">Formatos permitidos: imágenes, PDF,
                            documentos. Máx. 20MB por archivo.</p>
                        <flux:error name="newAttachments.*" />
                    </div>

                    @if ($isEditing && $task && $task->attachments && $task->attachments->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Adjuntos existentes</h4>
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($task->attachments as $attachment)
                                    <li class="py-2 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <flux:icon.paper-clip class="w-4 h-4 text-gray-400" />
                                            <div>
                                                <a href="{{ Storage::disk('public')->url($attachment->path) }}"
                                                    target="_blank"
                                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $attachment->original_name ?? $attachment->filename }}
                                                </a>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $attachment->mime_type }} ·
                                                    {{ number_format(($attachment->size ?? 0) / 1024, 0) }} KB</div>
                                            </div>
                                        </div>
                                        <flux:button wire:click="removeAttachment({{ $attachment->id }})"
                                            variant="danger" size="xs" icon="trash">
                                            Eliminar
                                        </flux:button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </flux:field>
            </div>
            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <flux:button href="{{ route('tasks.index') }}" variant="ghost" wire:navigate>
                    Cancelar
                </flux:button>
                <flux:button type="submit" icon="check">
                    {{ $isEditing ? 'Actualizar' : 'Crear' }} Tarea
                </flux:button>
            </div>
        </form>
    </div>
</div>
