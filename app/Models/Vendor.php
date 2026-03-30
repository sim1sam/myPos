<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_code',
        'name',
        'gstin',
        'mobile_no',
        'account_name',
        'account_no',
        'ifsc',
        'bank_name',
        'branch_name',
        'document_path',
        'gpay_phonepay_no',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
