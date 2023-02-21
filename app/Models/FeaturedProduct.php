<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'product_uuid',
        'status',
    ];
    protected $attributes = [
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'status'
    ];

    public function  product()
    {
        return $this->hasOne(Product::class, 'uuid', 'product_uuid');
    }
}
