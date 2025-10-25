<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'rate',
        'description',
        'type',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'type' => 'string',
    ];

    // Define tax types
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    // Define common tax codes in Colombia
    const CODE_IVA = '01';
    const CODE_ICA = '02';
    const CODE_IBUA = '03';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_taxes');
    }
}
