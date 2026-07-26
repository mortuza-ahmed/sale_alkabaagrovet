<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'sale_center',
        'district',
        'thana',
        'area',
    ];

    public function payment_transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
