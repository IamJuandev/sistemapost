<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Customer;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    public function render()
    {
        $query = Sale::with('customer')
            ->whereNotNull('invoice_number') // Solo facturas que tienen número
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda
        if ($this->search) {
            $searchTerm = $this->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('customer', function($subQuery) use ($searchTerm) {
                      $subQuery->where('first_name', 'like', "%{$searchTerm}%")
                               ->orWhere('last_name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Aplicar filtro de estado
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $invoices = $query->paginate(10);

        return view('livewire.sales.invoice-list', [
            'invoices' => $invoices
        ]);
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
            session()->flash('message', 'Factura cancelada exitosamente.');
        }
    }

    public function viewInvoice($invoiceId)
    {
        return redirect()->route('sales.show', ['sale' => $invoiceId]);
    }
}