{{--
Recomendación: Mueve el fondo a tu layout principal (e.g., app.blade.php)
para que se aplique a todas las páginas.

<body class="bg-slate-50 dark:bg-slate-900">
    --}}
    <div class="bg-slate-50 dark:bg-slate-900">
        {{-- @include('partials.nav') --}}

        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Panel de Control</h1>
                    <p class="mt-1 text-slate-500 dark:text-slate-300">Resumen general de tu sistema de inventario.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('sales.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nueva Venta
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Productos -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Productos</p>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalProducts }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                </div>
                <!-- Clientes -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Clientes</p>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalCustomers }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-yellow-600 dark:text-yellow-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M12 12.375a3.375 3.375 0 100-6.75 3.375 3.375 0 000 6.75z" />
                        </svg>
                    </div>
                </div>
                <!-- Proveedores -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Proveedores</p>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalSuppliers }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-purple-600 dark:text-purple-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v.958m12.026 11.177a48.529 48.529 0 01-12.026 0z" />
                        </svg>
                    </div>
                </div>
                <!-- Ventas -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Ventas</p>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalSales }}</p>
                        <p class="text-sm font-semibold text-green-600 dark:text-green-300 mt-2">
                            ${{ number_format($salesThisMonth, 2) }}
                            <span class="font-normal text-slate-500 dark:text-slate-300">este mes</span>
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-green-600 dark:text-green-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-3.75-.625m3.75.625V3.375" />
                        </svg>
                    </div>
                </div>
                <!-- Compras -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Compras</p>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalPurchases }}</p>
                        <p class="text-sm font-semibold text-red-600 dark:text-red-300 mt-2">
                            ${{ number_format($purchasesThisMonth, 2) }}
                            <span class="font-normal text-slate-500 dark:text-slate-300">este mes</span>
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-red-600 dark:text-red-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-md">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Ventas Recientes</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($recentSales as $sale)
                        <div
                            class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold">
                                    {{-- Iniciales del Cliente --}}
                                    {{ strtoupper(substr($sale->customer->first_name, 0, 1)) }}{{
                                    strtoupper(substr($sale->customer->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{
                                        $sale->customer->first_name }} {{ $sale->customer->last_name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-300">{{
                                        $sale->sale_date->format('d/m/Y h:i A') }}</p>
                                </div>
                            </div>
                            <p class="text-lg font-semibold text-slate-800 dark:text-slate-100">${{
                                number_format($sale->total_amount, 2) }}</p>
                        </div>
                        @empty
                        <div class="p-6 text-center text-slate-500 dark:text-slate-300">
                            <p>No hay ventas recientes para mostrar.</p>
                        </div>
                        @endforelse
                    </div>
                    @if($recentSales->count() > 0)
                    <div
                        class="p-4 bg-slate-50 dark:bg-slate-900 rounded-b-xl border-t border-slate-200 dark:border-slate-700 text-center">
                        <a href="#"
                            class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Ver todas
                            las ventas</a>
                    </div>
                    @endif
                </div>

                <div class="flex flex-col gap-8">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Productos con Bajo
                                Stock</h3>
                        </div>
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($lowStockProducts as $product)
                            <li
                                class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                                <span class="font-medium text-slate-700 dark:text-slate-100">{{ $product->name }}</span>
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full
                                    {{ $product->stock == 0 ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300' }}">
                                    Stock: {{ $product->stock }}
                                </span>
                            </li>
                            @empty
                            <li class="p-6 text-center text-slate-500 dark:text-slate-300">
                                <p>¡Todo en orden! No hay productos con bajo stock.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Gestión Rápida</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                            <a href="{{ route('products.index') }}"
                                class="text-center font-semibold text-slate-600 dark:text-slate-200 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 p-4 rounded-lg transition-colors">
                                Gestionar Productos
                            </a>
                            <a href="{{ route('suppliers.index') }}"
                                class="text-center font-semibold text-slate-600 dark:text-slate-200 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 p-4 rounded-lg transition-colors">
                                Gestionar Proveedores
                            </a>
                            <a href="{{ route('customers.index') }}"
                                class="text-center font-semibold text-slate-600 dark:text-slate-200 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 p-4 rounded-lg transition-colors">
                                Gestionar Clientes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>