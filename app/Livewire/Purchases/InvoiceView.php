<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;

class InvoiceView extends Component
{
    public Purchase $invoice;
    
    public function mount(Purchase $purchase)
    {
        $this->invoice = $purchase->load(['supplier', 'purchaseItems']);
    }

    public function render(): View
    {
        return view('livewire.purchases.invoice-view');
    }
    
    public function markAsPaid($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'completed']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura marcada como pagada exitosamente.');
        }
    }

    public function markAsCancelled($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'cancelled']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura cancelada exitosamente.');
        }
    }
}
