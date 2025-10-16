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
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Gestión de Proveedores</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Administra tus contactos y socios comerciales.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Nuevo Proveedor
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">

        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por compañía, agente o NIT..."
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
                    {{ $showEditForm ? 'Editar Proveedor' : 'Crear Nuevo Proveedor' }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Nombre de
                                la Compañía</label>
                            <input type="text" wire:model="company_name"
                                class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('company_name') border-red-500 @enderror">
                            @error('company_name') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                                }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Nombre del
                                Agente</label>
                            <input type="text" wire:model="agent_name"
                                class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('agent_name') border-red-500 @enderror">
                            @error('agent_name') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                                }}</span> @enderror
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Teléfono</label>
                            <input type="text" wire:model="phone"
                                class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('phone') border-red-500 @enderror">
                            @error('phone') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                                }}</span> @enderror
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Email</label>
                            <input type="email" wire:model="email"
                                class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                            @error('email') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                                }}</span> @enderror
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">NIT</label>
                        <input type="text" wire:model="nit"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('nit') border-red-500 @enderror">
                        @error('nit') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Días de
                            Visita</label>
                        <div class="flex flex-wrap gap-x-6 gap-y-4">
                            @foreach(['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday'
                            => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $value
                            => $day)
                            <label class="flex items-center space-x-2 text-slate-700 dark:text-slate-200">
                                <input type="checkbox" value="{{ $value }}" wire:model="selectedDays"
                                    class="rounded border-slate-400 dark:border-slate-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-slate-700 dark:checked:bg-indigo-500">
                                <span>{{ $day }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('selectedDays') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center space-x-3">
                    <button wire:click="{{ $showEditForm ? 'update' : 'store' }}"
                        class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        {{ $showEditForm ? 'Actualizar Proveedor' : 'Guardar Proveedor' }}
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
                            Proveedor</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Contacto</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            NIT</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Días de Visita</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 rounded-full flex items-center justify-center">
                                    <span class="font-bold text-sm">{{ strtoupper(substr($supplier->company_name, 0, 2))
                                        }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{
                                        $supplier->company_name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $supplier->agent_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-slate-100">{{ $supplier->phone }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $supplier->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{
                            $supplier->nit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <div class="flex flex-wrap gap-1">
                                @if($supplier->visit_days)
                                @foreach($supplier->visit_days as $day)
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold text-sky-800 dark:text-sky-300 bg-sky-100 dark:bg-sky-900/60 rounded-full">
                                    {{ substr(ucfirst($day), 0, 3) }}
                                </span>
                                @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-4">
                                <button wire:click="edit({{ $supplier->id }})"
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
                                <button wire:click="delete({{ $supplier->id }})"
                                    wire:confirm="¿Estás seguro de que deseas eliminar este proveedor?"
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
                        <td colspan="5">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2"
                                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">No se
                                    encontraron proveedores</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Comienza por crear tu primer
                                    proveedor.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($suppliers->hasPages())
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>