<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'price',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
