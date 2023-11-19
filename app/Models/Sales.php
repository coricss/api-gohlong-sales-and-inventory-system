<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'cashier_name',
        'transaction_id',
        'customer_name',
        'product_id',
        'is_discounted',
        'quantity',
        'subtotal',
        'payment',
        'change'
    ];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }

}
