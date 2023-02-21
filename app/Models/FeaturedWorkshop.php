<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedWorkshop extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'workshop_uuid',
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
    public function workshop()
    {
        return $this->hasOne(WorkShop::class, 'uuid', 'workshop_uuid');
    }
}
