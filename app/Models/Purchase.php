<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'invoice_purchases';

    protected $fillable = [
        'supplier_id',
        'supplier_nit',
        'invoice_prefix',
        'invoice_number',
        'supplier_name',
        'subtotal',
        'tax_amount',      // IVA
        'ibua_amount',     // Impuesto al consumo de bebidas y alimentos
        'icui_amount',     // Impuesto de industria y comercio
        'withholding_amount',
        'total_with_tax',
        'currency',
        'authorization_number',
        'authorization_expiration',
        'qr_url',
        'purchase_type',
        'purchase_date',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'due_date' => 'date',
        'authorization_expiration' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',      // IVA
        'ibua_amount' => 'decimal:2',     // Impuesto al consumo de bebidas y alimentos
        'icui_amount' => 'decimal:2',     // Impuesto de industria y comercio
        'withholding_amount' => 'decimal:2',
        'total_with_tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplierByNit(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_nit', 'nit');
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'invoice_number', 'invoice_number');
    }

    public function purchaseItemsByInvoice(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'invoice_number', 'invoice_number');
    }
    
    /**
     * Calcula los totales de la compra a partir de los ítems
     */
    public function calculateTotals(): array
    {
        $items = $this->purchaseItems;
        $subtotal = 0;
        $taxAmount = 0;      // IVA
        $ibuaAmount = 0;     // Impuesto al consumo de bebidas y alimentos
        $icuiAmount = 0;     // Impuesto de industria y comercio
        $totalWithTax = 0;
        
        foreach ($items as $item) {
            $itemSubtotal = ($item->unit_price * $item->quantity) - ($item->discount ?? 0);
            
            // Calcular los diferentes tipos de impuestos
            $itemTax = $itemSubtotal * ($item->tax_percent / 100);  // IVA
            $itemIbua = $item->ibua ?? 0;  // IBUA (ya calculado o valor fijo)
            $itemIcui = $item->icui ?? 0;  // ICUI (ya calculado o valor fijo)
            
            $subtotal += $itemSubtotal;
            $taxAmount += $itemTax;
            $ibuaAmount += $itemIbua;
            $icuiAmount += $itemIcui;
            $totalWithTax += $itemSubtotal + $itemTax + $itemIbua + $itemIcui;
        }
        
        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'ibua_amount' => $ibuaAmount,
            'icui_amount' => $icuiAmount,
            'total_with_tax' => $totalWithTax
        ];
    }
}