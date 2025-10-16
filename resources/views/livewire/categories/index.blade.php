{{--
Asegúrate de tener Alpine.js disponible en tu proyecto para las transiciones.
--}}
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">

    @if(session()->has('message'))
    <div class="mb-6 bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 rounded-lg shadow-md"
        role="alert">
        <p class="font-bold">Éxito</p>
        <p>{{ session('message') }}</p>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Gestión de Categorías</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Organiza tus productos en grupos lógicos.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Nueva Categoría
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">

        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar categorías por nombre..."
                    class="w-full pl-10 pr-4 py-2.5 text-base border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <div x-data="{}" x-show="$wire.showCreateForm || $wire.showEditForm" x-transition
            class="border-b border-slate-200 dark:border-slate-700" style="display: none;">
            <div class="p-6 bg-slate-50 dark:bg-slate-900/70">
                <h2 class="text-xl font-semibold mb-6 text-slate-800 dark:text-slate-100">
                    {{ $showEditForm ? 'Editar Categoría' : 'Crear Nueva Categoría' }}
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Nombre</label>
                        <input type="text" wire:model="name"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Descripción</label>
                        <textarea wire:model="description" rows="3"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('description') border-red-500 @enderror"></textarea>
                        @error('description') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center space-x-3">
                    <button wire:click="{{ $showEditForm ? 'update' : 'store' }}"
                        class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        {{ $showEditForm ? 'Actualizar Categoría' : 'Guardar Categoría' }}
                    </button>
                    <button wire:click="cancel"
                        class="px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-100 font-semibold rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/70">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Categoría</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Productos</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Fecha de Creación</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $category->name }}
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">{{
                                Str::limit($category->description, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{
                            $category->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-4">
                                <button wire:click="edit({{ $category->id }})"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition"
                                    title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $category->id }})"
                                    wire:confirm="¿Estás seguro? Esta acción no se puede deshacer."
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition"
                                    title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">No se
                                    encontraron categorías</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Comienza por crear tu primera
                                    categoría.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>