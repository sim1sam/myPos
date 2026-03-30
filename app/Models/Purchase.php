<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'customer_id',
        'vendor_id',
        'vendor_name',
        'invoice_no',
        'invoice_date',
        'product_name',
        'hsn_sac',
        'price',
        'qty',
        'total_amount',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
