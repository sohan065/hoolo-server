<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_uuid',
        'attributes_uuid',
        // 'variant_stock',
        'values',
        'images',
        'prices',
    ];
    protected $attributes = [
        'images' => null,
        'prices' => null,
        'values' => 0,
    ];
    protected $casts = [
        'images' => 'array',
        'prices' => 'array',
        'values' => 'array',
    ];
    protected $hidden = [
        'id',
        'images',
        'product_uuid',
        'attributes_uuid',
        'created_at',
        'updated_at',
    ];
    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'uuid', 'attributes_uuid');
    }

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    public function gallery()
    {
        return $this->belongsToJson(ProductGallery::class, 'images');
    }
}
