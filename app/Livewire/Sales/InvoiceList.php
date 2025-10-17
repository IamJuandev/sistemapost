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
            $invoice->update(['status' => 'completed']);
            session()->flash('message', 'Factura marcada como pagada exitosamente.');
        }
    }

    public function markAsCancelled($invoiceId)
    {
        $invoice = Sale::find($invoiceId);
        if ($invoice) {
            // Si la factura ya está completada, devolver los productos al inventario
            if ($invoice->status === 'completed') {
                foreach ($invoice->saleItems as $item) {
                    // Actualizar el stock del producto
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }
            
            $invoice->update(['status' => 'cancelled']);
            session()->flash('message', 'Factura cancelada exitosamente. Los productos han sido devueltos al inventario.');
        }
    }

    public function viewInvoice($invoiceId)
    {
        return redirect()->route('sales.show', ['sale' => $invoiceId]);
    }
}