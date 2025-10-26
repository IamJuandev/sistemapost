<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Contracts\View\View;

class InvoiceView extends Component
{
    public Sale $invoice;
    
    public function mount(Sale $sale)
    {
        $this->invoice = $sale->load(['customer', 'saleItems']);
    }

    public function render(): View
    {
        return view('livewire.sales.invoice-view');
    }
    
    public function markAsPaid($invoiceId)
    {
        $invoice = Sale::find($invoiceId);
        if ($invoice) {
            // Si la factura estaba pendiente, reducir la deuda del cliente
            if ($invoice->status === 'pending' && $invoice->customer) {
                $new_debt = $invoice->customer->current_debt - $invoice->total_amount;
                // Asegurar que la deuda no sea negativa
                $new_debt = max(0, $new_debt);
                $invoice->customer->update(['current_debt' => $new_debt]);
            }
            
            $invoice->update(['status' => 'completed']);
            
            // Reducir el stock de los productos vendidos (si no se había hecho antes)
            foreach ($invoice->saleItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
            
            $this->invoice->refresh();
            session()->flash('message', 'Factura marcada como pagada exitosamente.');
        }
    }

    public function markAsCancelled($invoiceId)
    {
        $invoice = Sale::find($invoiceId);
        if ($invoice) {
            // Devolver los productos al inventario independientemente del estado previo
            foreach ($invoice->saleItems as $item) {
                // Actualizar el stock del producto
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
            
            // Si la factura estaba pendiente, reducir la deuda que se había registrado
            if ($invoice->status === 'pending' && $invoice->customer) {
                $new_debt = $invoice->customer->current_debt - $invoice->total_amount;
                // Asegurar que la deuda no sea negativa
                $new_debt = max(0, $new_debt);
                $invoice->customer->update(['current_debt' => $new_debt]);
            }
            
            $invoice->update(['status' => 'cancelled']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura cancelada exitosamente.');
        }
    }
}