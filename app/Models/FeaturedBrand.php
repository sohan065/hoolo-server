<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedBrand extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'brand_uuid',
        'status',
    ];
    protected $attributes = [
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];
    public function brand()
    {
        return $this->hasOne(Brand::class, 'uuid', 'brand_uuid');
    }
}
