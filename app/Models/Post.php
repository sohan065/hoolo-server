<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'merchant_uuid',
        'product_uuid',
        'file_uuid',
        'title',
        'type',
        'views',
        'share',
        'featured',
        'is_active',
        'status',
    ];
    protected $attributes = [
        'featured' => 0,
        'share' => 0,
        'is_active' => 0,
        'status' => 0,
        'views' => 0,
    ];
    protected $hidden = [
        'id',
        'status',
        'product_uuid',
        'file_uuid',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'product_uuid' => 'array',
        'file_uuid' => 'array',
    ];
    public function author()
    {
        return $this->hasOne(Merchant::class, 'uuid', 'merchant_uuid');
    }

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    public function product()
    {
        return $this->belongsToJson(Product::class, 'product_uuid');
    }
     public function gallery()
    {
        return $this->belongsToJson(PostGallery::class,'file_uuid');
    }
    public function like()
    {
        return $this->hasOne(PostLike::class, 'post_uuid', 'uuid');
    }
    public function comment()
    {
        return $this->hasMany(PostComment::class, 'post_uuid', 'uuid');
    }
}
