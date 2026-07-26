<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = ['customer_id','sale_id','paid','payment_date','payment_status','note'];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}

