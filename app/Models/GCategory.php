<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'status',
        'is_active',
        'icon',
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
    public function pcategory()
    {
        return $this->hasMany(PCategory::class, 'gcategory_uuid', 'uuid');
    }
}
