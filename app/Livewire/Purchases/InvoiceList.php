<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    public function render()
    {
        $query = Purchase::with('supplier')
            ->whereNotNull('invoice_number') // Solo facturas que tienen número (provenientes de n8n)
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda
        if ($this->search) {
            $searchTerm = $this->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('supplier', function($subQuery) use ($searchTerm) {
                      $subQuery->where('company_name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Aplicar filtro de estado
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $invoices = $query->paginate(10);

        return view('livewire.purchases.invoice-list', [
            'invoices' => $invoices
        ]);
    }

    public function markAsPaid($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'completed']);
            session()->flash('message', 'Factura marcada como pagada exitosamente.');
        }
    }

    public function markAsProcessed($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'completed']);
            session()->flash('message', 'Factura marcada como procesada exitosamente.');
        }
    }

    public function viewInvoice($invoiceId)
    {
        return redirect()->route('purchases.show', ['purchase' => $invoiceId]);
    }
}
