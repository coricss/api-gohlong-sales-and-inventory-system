<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'model_size',
        'brand_id',
        'category_id',
        'stocks',
        'price',
        'discount'
    ];

    public function brands()
    {
        return $this->belongsTo(Brands::class);
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }
}
