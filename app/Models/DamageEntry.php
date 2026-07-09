<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'product_name',
        'hsn_sac',
        'qty',
        'price',
        'reason',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'price' => 'decimal:2',
    ];
}
