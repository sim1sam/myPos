<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'address',
        'city',
        'pin_code',
        'state',
        'email',
        'mobile',
        'gstin',
    ];
}
