<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpeningStockEntry extends Model
{
    protected $fillable = [
        'vendor_id',
        'vendor_name',
        'reference_no',
        'entry_date',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OpeningStockItem::class);
    }
}
