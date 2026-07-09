<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningStockItem extends Model
{
    protected $fillable = [
        'opening_stock_entry_id',
        'product_name',
        'hsn_sac',
        'price',
        'qty',
        'total_amount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(OpeningStockEntry::class, 'opening_stock_entry_id');
    }
}
