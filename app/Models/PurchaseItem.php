<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;
    
    protected $table = 'invoice_purchase_items';

    protected $fillable = [
        'purchase_id',
        'invoice_number',
        'product_id',
        'line_id',
        'description',
        'product_code',
        'unit_code',
        'quantity',
        'unit_price',
        'item_subtotal',
        'line_extension_amount',
        'discount',
        'tax_percent',
        'tax_amount',
        'ibua',
        'icui',
        'total_line_amount',
        'total_value',
    ];

    protected $casts = [
        'line_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'item_subtotal' => 'decimal:2',
        'line_extension_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'ibua' => 'decimal:2',
        'icui' => 'decimal:2',
        'total_line_amount' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'invoice_number', 'invoice_number');
    }

    public function purchaseByInvoiceNumber(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'invoice_number', 'invoice_number');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}