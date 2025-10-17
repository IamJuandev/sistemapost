<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Punto de Venta</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Crea una nueva transacción de venta.</p>
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
                    Información del Cliente
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.select name="selectedCustomer" label="Cliente"
                        :options="$customers->mapWithKeys(fn($customer) => [$customer->id => $customer->first_name . ' ' . $customer->last_name])->toArray()"
                        wire:model.live="selectedCustomer" :placeholder="'Seleccionar Cliente'" />
                    <x-form.input name="discount" label="Descuento Global (%)" type="number" step="0.01" min="0"
                        max="100" wire:model.live="discount" />
                </div>
            </div>

            @if($selectedCustomer)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md">
                <h2
                    class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-4 mb-4">
                    <span class="text-sm font-bold text-white bg-indigo-600 rounded-full px-3 py-1 mr-2">2</span>
                    Agregar Productos
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-3 relative">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Producto</label>
                        <input type="text" wire:model.live.debounce.300ms="searchProduct"
                            placeholder="Buscar por nombre o código..."
                            class="w-full pl-10 pr-4 py-2.5 text-base border-slate-300 dark:border-slate-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            autocomplete="off">
                        <div class="absolute inset-y-0 left-0 top-6 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        @if($searchResults->count() > 0)
                        <div
                            class="absolute z-20 mt-1 w-full bg-white dark:bg-slate-800 shadow-lg rounded-md max-h-60 overflow-auto border border-slate-200 dark:border-slate-600">
                            @foreach($searchResults as $product)
                            <div wire:click="selectProduct({{ $product->id }})"
                                class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-slate-800 dark:text-slate-100">{{ $product->name }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">Código: {{ $product->barcode
                                        }}</div>
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">Stock: <span
                                        class="font-bold text-slate-700 dark:text-slate-200">{{ $product->stock
                                        }}</span></div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @error('searchProduct') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Cantidad</label>
                        <input type="number" wire:model="quantity" min="1"
                            class="block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 @error('quantity') border-red-500 @enderror" />
                        @error('quantity') <span class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message
                            }}</span> @enderror
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
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">Selecciona un cliente</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Elige un cliente para poder agregar productos
                    a la venta.</p>
            </div>
            @endif

        </div>

        <div class="lg:col-span-1">
            <div class="lg:sticky top-8 bg-white dark:bg-slate-800 rounded-xl shadow-md">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Resumen de la Venta</h2>
                </div>

                @if(!empty($cart))
                <div class="p-6 border-t border-slate-200 dark:border-slate-700 max-h-[40vh] overflow-y-auto space-y-4">
                    @foreach($cart as $productId => $item)
                    <div class="flex items-center space-x-4">
                        {{-- INICIO DE LA SECCIÓN MODIFICADA --}}
                        <div class="flex-shrink-0">
                            @if(!empty($item['image_url']))
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                class="w-16 h-16 rounded-lg object-cover">
                            @else
                            <div
                                class="w-16 h-16 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <span class="text-xl font-bold text-slate-500 dark:text-slate-300">
                                    {{ strtoupper(substr($item['name'], 0, 2)) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        {{-- FIN DE LA SECCIÓN MODIFICADA --}}
                        <div class="flex-grow">
                            <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $item['name'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">${{
                                number_format($item['selling_price'], 2) }} x {{ $item['quantity'] }}</p>
                            <input type="number" wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                                value="{{ $item['quantity'] }}" min="1"
                                class="w-20 mt-1 px-2 py-1 text-sm border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring-indigo-200 dark:bg-slate-700 dark:text-slate-100">
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900 dark:text-slate-100">${{
                                number_format($item['subtotal'], 2) }}</p>
                            <button wire:click="removeFromCart({{ $productId }})"
                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 mt-1"
                                title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div
                    class="p-6 bg-slate-50 dark:bg-slate-900/70 rounded-b-xl border-t border-slate-200 dark:border-slate-700 space-y-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Subtotal:</span>
                            <span class="font-medium text-slate-800 dark:text-slate-100">${{ number_format($total, 2)
                                }}</span>
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Descuento ({{ $discount }}%):</span>
                            <span class="font-medium text-red-600 dark:text-red-400">-${{ number_format($total *
                                ($discount / 100), 2) }}</span>
                        </div>
                        @endif
                        <div
                            class="flex justify-between text-lg font-bold pt-2 border-t border-slate-200 dark:border-slate-700">
                            <span class="text-slate-800 dark:text-slate-100">Total a Pagar:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">${{ number_format($totalWithDiscount, 2)
                                }}</span>
                        </div>
                    </div>

                    <x-form.select name="payment_method" label="Forma de Pago" :options="[
                        'cash' => 'Efectivo',
                        'credit_card' => 'Tarjeta de Crédito',
                        'debit_card' => 'Tarjeta de Débito',
                        'bank_transfer' => 'Transferencia'
                    ]" wire:model="payment_method" required />
                    @if ($payment_method === 'cash')
                    <div class="mt-4">
                        <x-form.input name="cashReceived" label="Efectivo Recibido" type="number" step="any"
                            wire:model.live="cashReceived" placeholder="0.00" />
                    </div>

                    @if(!is_null($cashReceived) && $cashReceived !== '')
                    <div class="mt-4 p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-slate-800 dark:text-slate-100">Cambio:</span>
                            <span class="text-green-600 dark:text-green-400">${{ number_format($change, 2) }}</span>
                        </div>
                    </div>
                    @endif
                    @endif
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button wire:click="cancelSale"
                            class="w-full h-11 inline-flex items-center justify-center px-4 text-sm font-semibold text-slate-700 bg-slate-200 dark:bg-slate-700 dark:text-slate-100 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                            Cancelar
                        </button>
                        <button wire:click="completeSale" wire:loading.attr="disabled"
                            class="w-full h-11 inline-flex items-center justify-center px-4 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 disabled:opacity-50 transition">
                            Finalizar Venta
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
                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">El carrito está vacío</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Agrega productos para continuar.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>