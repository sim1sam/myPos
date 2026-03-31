<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GstRate extends Model
{
    protected $fillable = [
        'hsn_sac',
        'description',
        'gst_type',
        'simple_rate',
    ];

    protected $casts = [
        'simple_rate' => 'decimal:2',
    ];

    public function slabs(): HasMany
    {
        return $this->hasMany(GstRateSlab::class);
    }
}
