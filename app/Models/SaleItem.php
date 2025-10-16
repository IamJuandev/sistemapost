<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;
    
    protected $table = 'invoice_sale_items';

    protected $fillable = [
        'sale_id',
        'product_id',
        'line_id',
        'description',
        'product_code',
        'unit_code',
        'quantity',
        'selling_price',
        'line_extension_amount',
        'tax_percent',
        'tax_amount',
        'total_line_amount',
        'subtotal',
    ];

    protected $casts = [
        'line_id' => 'integer',
        'quantity' => 'integer',
        'selling_price' => 'decimal:2',
        'line_extension_amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_line_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}