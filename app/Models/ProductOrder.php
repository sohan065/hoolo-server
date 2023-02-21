<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'product_uuid',
        'merchant_uuid',
        'price',
        'quantity',
        'order_code',

    ];

    protected $attributes = [];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
     public function product()
    {
        return $this->hasOne(Product::class, 'uuid', 'product_uuid');
    }
     public function details()
    {
        return $this->hasOne(ProductDetails::class, 'product_uuid', 'product_uuid');
    }
     
}
