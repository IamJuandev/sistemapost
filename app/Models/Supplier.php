<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'agent_name',
        'phone',
        'email',
        'visit_days',
        'nit',
        'dv',
        'address',
        'city',
        'department',
        'country',
        'postal_code',
        'tax_responsibilities',
    ];

    protected $casts = [
        'visit_days' => 'array',
        'tax_responsibilities' => 'array',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}