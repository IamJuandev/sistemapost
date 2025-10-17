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
            $invoice->update(['status' => 'completed']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura marcada como pagada exitosamente.');
        }
    }

    public function markAsCancelled($invoiceId)
    {
        $invoice = Sale::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'cancelled']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura cancelada exitosamente.');
        }
    }
}