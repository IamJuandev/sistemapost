<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Registrar Nueva Compra</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Ingresa los productos recibidos de un proveedor.</p>
        </div>
    </div>

    @if(session()->has('message'))
    <div class="mb-6 bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 rounded-lg shadow-md"
        role="alert">
        <p class="font-bold">Éxito</p>
        <p>{{ session('message') }}</p>
    </div>
    @endif

    @error('cart')
    <div class="mb-6 bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 rounded-lg shadow-md"
        role="alert">
        <p class="font-bold">Error</p>
        <p>{{ $message }}</p>
    </div>
    @enderror

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md">
                <h2
                    class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-4 mb-4">
                    <span class="text-sm font-bold text-white bg-indigo-600 rounded-full px-3 py-1 mr-2">1</span>
                    Detalles de la Factura
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-3">
                        <x-form.select 
                            name="selectedSupplier" 
                            label="Proveedor" 
                            :options="$suppliers->pluck('company_name', 'id')->toArray()" 
                            wire:model.live="selectedSupplier" 
                            required 
                            :placeholder="'Seleccionar Proveedor'"
                        />
                    </div>
                    <x-form.input 
                        name="invoice_prefix" 
                        label="Prefijo Factura" 
                        wire:model="invoice_prefix" 
                        placeholder="Ej: FV-"
                    />
                    <x-form.input 
                        name="invoice_number" 
                        label="Número Factura" 
                        wire:model="invoice_number" 
                    />
                    <x-form.input 
                        name="purchase_date" 
                        label="Fecha de Compra" 
                        type="date" 
                        wire:model="purchase_date" 
                    />
                    <x-form.select 
                        name="currency" 
                        label="Moneda" 
                        :options="['COP' => 'COP', 'USD' => 'USD', 'EUR' => 'EUR']" 
                        wire:model="currency" 
                    />
                    <div class="lg:col-span-2">
                        <x-form.input 
                            name="notes" 
                            label="Notas" 
                            wire:model="notes" 
                            placeholder="Notas adicionales"
                        />
                    </div>
                </div>
            </div>

            @if($selectedSupplier)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md">
                <h2
                    class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-4 mb-4">
                    <span class="text-sm font-bold text-white bg-indigo-600 rounded-full px-3 py-1 mr-2">2</span>
                    Agregar Productos a la Compra
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Producto</label>
                        <select wire:model="selectedProduct"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('selectedProduct') border-red-500 @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($products as $product) <option value="{{ $product->id }}">{{ $product->name }}
                            </option> @endforeach
                        </select>
                        @error('selectedProduct') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Cantidad</label>
                        <input type="number" wire:model="quantity" min="1"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('quantity') border-red-500 @enderror" />
                        @error('quantity') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Precio
                            Costo</label>
                        <input type="number" step="0.01" wire:model="unit_price" min="0"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('unit_price') border-red-500 @enderror" />
                        @error('unit_price') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Impuesto
                            %</label>
                        <input type="number" step="0.01" wire:model="tax_percent" min="0" max="100"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" />
                    </div>
                    <div class="flex items-end">
                        <button wire:click="addToCart"
                            class="w-full h-[46px] inline-flex items-center justify-center gap-2 px-4 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 transition-all">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-md text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">Selecciona un proveedor</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Elige un proveedor para poder agregar
                    productos a la compra.</p>
            </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="lg:sticky top-8 bg-white dark:bg-slate-800 rounded-xl shadow-md">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Resumen de la Compra</h2>
                </div>

                @if(!empty($cart))
                <div class="px-6 pb-6 border-t border-slate-200 dark:border-slate-700">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="py-2 text-left font-medium text-slate-500 dark:text-slate-300">Producto</th>
                                <th class="py-2 text-right font-medium text-slate-500 dark:text-slate-300">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($cart as $productId => $item)
                            <tr>
                                <td class="py-3">
                                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $item['name'] }}</p>
                                    <p class="text-slate-500 dark:text-slate-400">${{ number_format($item['unit_price'],
                                        2) }} x {{ $item['quantity'] }}</p>
                                </td>
                                <td class="py-3 text-right font-medium text-slate-800 dark:text-slate-100\">${{
                                    number_format($item['total_value'] ?? $item['total_line_amount'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="p-6 bg-slate-50 dark:bg-slate-900/70 rounded-b-xl border-t border-slate-200 dark:border-slate-700 space-y-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Subtotal:</span>
                            <span class="font-medium text-slate-800 dark:text-slate-100">${{ number_format($subtotal, 2)
                                }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Impuestos:</span>
                            <span class="font-medium text-slate-800 dark:text-slate-100">${{ number_format($tax_amount,
                                2) }}</span>
                        </div>
                        <div
                            class="flex justify-between text-lg font-bold pt-2 border-t border-slate-200 dark:border-slate-700">
                            <span class="text-slate-800 dark:text-slate-100">Total a Pagar:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">${{ number_format($total_with_tax, 2)
                                }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button wire:click="cancelPurchase"
                            class="w-full h-10 inline-flex items-center justify-center px-4 text-sm font-semibold text-slate-700 bg-slate-200 dark:bg-slate-700 dark:text-slate-100 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                            Cancelar
                        </button>
                        <button wire:click="completePurchase" wire:loading.attr="disabled"
                            class="w-full h-10 inline-flex items-center justify-center px-4 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 disabled:opacity-50 transition">
                            Registrar Compra
                        </button>
                    </div>
                </div>
                @else
                <div class="p-8 text-center border-t border-slate-200 dark:border-slate-700">
                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">La compra está vacía</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Agrega productos para continuar.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>