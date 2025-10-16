<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $fillable = [
        'company_name',
        'trade_name',
        'nit',
        'dv',
        'address',
        'city',
        'department',
        'country',
        'postal_code',
        'phone',
        'email',
        'economic_activity_code',
        'regime_type',
        'tax_responsibilities',
        'software_id',
        'software_pin',
        'test_set_id',
        'environment',
        'logo_url'
    ];

    protected $casts = [
        'tax_responsibilities' => 'array',
    ];
}
