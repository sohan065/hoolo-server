<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'gcategory_uuid',
        'name',
        'icon',
        'status',
        'is_active',

    ];
    protected $attributes = [
         'status' => 0,
        'is_active' => 1,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function  gcategory()
    {
        return $this->hasOne(GCategory::class, 'uuid', 'gcategory_uuid');
    }
     public function  category()
    {
        return $this->hasMany(Category::class, 'pcategory_uuid', 'uuid');
    }
}
