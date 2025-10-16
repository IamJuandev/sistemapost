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
        'tax_amount',
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
        'tax_amount' => 'decimal:2',
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
}
