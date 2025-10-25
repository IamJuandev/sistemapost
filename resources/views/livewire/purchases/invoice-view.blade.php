<div class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    {{-- @include('partials.nav') --}}
    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Detalle de Factura</h1>
                <p class="mt-1 text-slate-500 dark:text-slate-300">Revisa la información de la compra registrada.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                <a href="{{ route('purchases.invoices') }}"
                    class="h-10 inline-flex items-center justify-center px-4 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-600">
                    Volver
                </a>
                @if($invoice->status === 'pending')
                <button wire:click="markAsCancelled({{ $invoice->id }})"
                    class="h-10 w-10 inline-flex items-center justify-center text-red-600 bg-white border border-slate-300 rounded-lg hover:bg-red-50 hover:text-red-700 transition dark:bg-slate-700 dark:border-slate-600 dark:text-red-400 dark:hover:bg-red-900/50 dark:hover:text-red-300"
                    title="Cancelar Factura">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <button wire:click="markAsPaid({{ $invoice->id }})"
                    class="h-10 inline-flex items-center justify-center gap-2 px-4 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 transition">
                    <span>Marcar como Pagada</span>
                </button>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700">
            <header class="p-6 md:p-8 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Tu
                        Empresa</h2>
                    <p class="text-slate-500 dark:text-slate-400">Dirección de tu empresa, Ciudad</p>
                </div>
                <div class="text-right">
                    <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 uppercase">Factura</h1>
                    <p class="text-slate-500 dark:text-slate-400 font-mono">{{ $invoice->invoice_prefix }}{{
                        $invoice->invoice_number }}</p>
                </div>
            </header>

            <div
                class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-y border-slate-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase mb-2">Proveedor</p>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $invoice->supplier->company_name
                        ?? 'N/A' }}</h3>
                    <p class="text-slate-600 dark:text-slate-300">{{ $invoice->supplier->nit ?? 'N/A' }}</p>
                    <p class="text-slate-600 dark:text-slate-300">{{ $invoice->supplier->email ?? 'N/A' }}</p>
                    <p class="text-slate-600 dark:text-slate-300">{{ $invoice->supplier->phone ?? 'N/A' }}</p>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2">
                        <span class="font-semibold text-slate-600 dark:text-slate-300">Fecha de Factura:</span>
                        <span class="text-slate-800 dark:text-slate-100 text-right">{{ $invoice->purchase_date ?
                            $invoice->purchase_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="grid grid-cols-2">
                        <span class="font-semibold text-slate-600 dark:text-slate-300">Fecha de Vencimiento:</span>
                        <span class="text-slate-800 dark:text-slate-100 text-right">{{ $invoice->due_date ?
                            $invoice->due_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="grid grid-cols-2 items-center">
                        <span class="font-semibold text-slate-600 dark:text-slate-300">Estado:</span>
                        <div class="text-right">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($invoice->status === 'completed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                @elseif($invoice->status === 'cancelled') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                @else bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <table class="w-full text-left">
                    <thead class="border-b border-slate-300 dark:border-slate-700">
                        <tr>
                            <th class="py-3 text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase">Producto
                            </th>
                            <th
                                class="py-3 text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">
                                Cantidad</th>
                            <th
                                class="py-3 text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase text-right">
                                Precio Unit.</th>
                            <th
                                class="py-3 text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase text-right">
                                Descuento</th>
                            <th
                                class="py-3 text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase text-right">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($invoice->purchaseItems as $item)
                        <tr>
                            <td class="py-4 font-medium text-slate-800 dark:text-slate-100">
                                <div>{{ $item->product->name ?? $item->description }}</div>
                                @if($item->product_code)
                                <div class="text-xs text-slate-500 dark:text-slate-400">Código: {{ $item->product_code
                                    }}</div>
                                @endif
                            </td>
                            <td class="py-4 text-slate-600 dark:text-slate-300 text-center">{{ $item->quantity }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-300 text-right">${{
                                number_format($item->unit_price ?? 0, 2) }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-300 text-right">${{
                                number_format($item->discount ?? 0, 2) }}</td>
                            <td class="py-4 font-semibold text-slate-800 dark:text-slate-100 text-right">${{
                                number_format($item->total_value ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 dark:text-slate-400">No hay productos
                                en esta factura.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="p-6 md:p-8 bg-slate-50 dark:bg-slate-900/70 rounded-b-xl border-t border-slate-200 dark:border-slate-700">
                <div class="w-full md:w-1/2 md:ml-auto space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-300">Subtotal:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">${{
                            number_format($invoice->subtotal ?? 0, 2) }}</span>
                    </div>
                    @if($invoice->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-300">IVA:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">${{
                            number_format($invoice->tax_amount ?? 0, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->ibua_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-300">IBUA:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">${{
                            number_format($invoice->ibua_amount ?? 0, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->icui_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-300">ICUI:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">${{
                            number_format($invoice->icui_amount ?? 0, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->withholding_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-300">Retención:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">${{
                            number_format($invoice->withholding_amount ?? 0, 2) }}</span>
                    </div>
                    @endif
                    <div
                        class="flex justify-between text-lg font-bold pt-2 mt-2 border-t border-slate-300 dark:border-slate-600">
                        <span class="text-slate-800 dark:text-slate-100">Total:</span>
                        <span class="text-indigo-600 dark:text-indigo-400">${{ number_format($invoice->total_amount ??
                            0, 2) }}</span>
                    </div>
                </div>
                @if($invoice->notes)
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="font-semibold text-slate-700 dark:text-slate-200 mb-1">Notas:</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $invoice->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>