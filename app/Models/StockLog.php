<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = ['supplier_id','customer_id','purchase_id','sale_id','product_id','quantity','bonus','bonus_facility','date','type'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
