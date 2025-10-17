{{-- 
Asegúrate de tener Alpine.js disponible en tu proyecto para el menú de acciones.
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
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Facturas de Ventas</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Consulta y gestiona el estado de las facturas de ventas.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md">

        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por número de factura o cliente..."
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
            <div>
                <select wire:model.live="statusFilter"
                    class="w-full md:w-auto h-[46px] block rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    <option value="all">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="completed">Pagadas</option>
                    <option value="cancelled">Canceladas</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/70">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Factura</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Fechas</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Total</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            Estado</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{
                                $invoice->invoice_prefix }}{{ $invoice->invoice_number }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $invoice->customer->first_name }}
                                {{ $invoice->customer->last_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-600 dark:text-slate-300">Venta: {{
                                $invoice->created_at->format('d/m/Y') }}</div>
                            <div
                                class="text-sm {{ $invoice->due_date && $invoice->due_date->isPast() && $invoice->status == 'pending' ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 dark:text-slate-400' }}">
                                Vence: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800 dark:text-slate-100">
                            ${{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($invoice->status === 'completed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                @elseif($invoice->status === 'cancelled') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                @else bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="viewInvoice({{ $invoice->id }})"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                    title="Ver Detalles">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-data="{ open: false }" @click.away="open = false" class="inline-block text-left">
                                    <button @click="open = !open"
                                        class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200"
                                        title="Más acciones">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition
                                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 dark:ring-slate-700 z-10"
                                        style="display:none;">
                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                            @if($invoice->status === 'pending')
                                            <button wire:click="markAsPaid({{ $invoice->id }})"
                                                class="flex items-center w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700"
                                                role="menuitem">
                                                Marcar como Pagada
                                            </button>
                                            @endif
                                            <button wire:click="markAsCancelled({{ $invoice->id }})"
                                                class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700"
                                                role="menuitem">
                                                Cancelar Factura
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">No se
                                    encontraron facturas</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Prueba con un filtro
                                    diferente o crea una nueva venta.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>