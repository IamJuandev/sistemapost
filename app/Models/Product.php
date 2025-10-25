<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'barcode',
        'cost_price',
        'tax_rate',
        'profit_margin',
        'selling_price',
        'stock',
        'image_url',
        'category_id',
        'supplier_id',
        'unit_md',
        'quantity_for_unit',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'unit_md' => 'string',
        'quantity_for_unit' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'product_taxes')
                    ->withPivot('rate_override')
                    ->withTimestamps();
    }
}