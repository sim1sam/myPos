<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'logo_path',
        'qr_code_path',
        'company_name',
        'company_email',
        'mobile_number',
        'company_gstin',
        'address',
        'city',
        'pin',
        'state',
        'account_holder_name',
        'account_number',
        'bank_name',
        'branch',
        'ifsc_code',
        'company_pan',
        'declaration',
        'footer_text',
    ];
}
