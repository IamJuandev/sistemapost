{{--
Asegúrate de tener Alpine.js disponible en tu proyecto para las transiciones.
Si usas un starter kit de Laravel, ya debería estar incluido.
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
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Gestión de Productos</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Añade, edita y administra todo tu inventario.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Nuevo Producto
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">

        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar productos por nombre o código..."
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
                    {{ $showEditForm ? 'Editar Producto' : 'Crear Nuevo Producto' }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <x-form.input 
                            name="name" 
                            label="Nombre" 
                            wire:model="name" 
                            required 
                        />
                        <x-form.select 
                            name="category_id" 
                            label="Categoría" 
                            :options="$categories->pluck('name', 'id')->toArray()" 
                            wire:model="category_id" 
                            required 
                            :placeholder="'Seleccionar...'"
                        />
                        <x-form.select 
                            name="supplier_id" 
                            label="Proveedor" 
                            :options="$suppliers->pluck('company_name', 'id')->toArray()" 
                            wire:model="supplier_id" 
                            required 
                            :placeholder="'Seleccionar...'"
                        />
                        <x-form.input 
                            name="barcode" 
                            label="Código de Barras" 
                            wire:model="barcode" 
                            required 
                        />
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <x-form.input 
                                name="cost_price" 
                                label="Precio de Costo" 
                                type="number" 
                                step="0.01" 
                                wire:model.live="cost_price" 
                                required 
                            />
                            <x-form.input 
                                name="profit_margin" 
                                label="Margen (%)" 
                                type="number" 
                                step="0.01" 
                                wire:model.live="profit_margin" 
                                required 
                            />
                        </div>
                        <x-form.input 
                            name="tax_rate" 
                            label="Impuesto (%)" 
                            type="number" 
                            step="0.01" 
                            wire:model.live="tax_rate" 
                            required 
                        />
                        <x-form.input 
                            name="selling_price" 
                            label="Precio Venta (Calculado)" 
                            wire:model="selling_price" 
                            readonly 
                            disabled 
                        />
                        <x-form.input 
                            name="stock" 
                            label="Stock Inicial" 
                            type="number" 
                            wire:model="stock" 
                            required 
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center space-x-3">
                    <x-form.button 
                        type="button"
                        wire:click="{{ $showEditForm ? 'update' : 'store' }}"
                        variant="primary"
                    >
                        {{ $showEditForm ? 'Actualizar Producto' : 'Guardar Producto' }}
                    </x-form.button>
                    <x-form.button 
                        type="button"
                        wire:click="cancel"
                        variant="secondary"
                    >
                        Cancelar
                    </x-form.button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/70">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Producto</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Proveedor</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Precio Venta</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Stock</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-md object-cover"
                                        src="{{ $product->image_url ?: 'https://via.placeholder.com/150?text=Sin+Imagen' }}"
                                        alt="Imagen de {{ $product->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{
                                        $product->name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $product->category->name
                                        ?? 'Sin categoría' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{
                            $product->supplier->company_name ?? 'N/A' }}</td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100 font-semibold">
                            ${{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($product->stock > 10) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                @elseif($product->stock > 0) bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                @else bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 @endif">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-4">
                                <button wire:click="edit({{ $product->id }})"
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
                                <button wire:click="delete({{ $product->id }})"
                                    wire:confirm="¿Estás seguro de que deseas eliminar este producto?"
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
                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">No se
                                    encontraron productos</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Comienza por crear tu primer
                                    producto.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>