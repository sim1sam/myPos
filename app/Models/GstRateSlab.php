<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GstRateSlab extends Model
{
    protected $fillable = [
        'gst_rate_id',
        'min_amount',
        'max_amount',
        'rate',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'rate' => 'decimal:2',
    ];

    public function gstRate(): BelongsTo
    {
        return $this->belongsTo(GstRate::class);
    }
}
