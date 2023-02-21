<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'city_uuid',
        'name',
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

    public function city()
    {
        return $this->belongsTo(City::class, 'uuid', 'city_uuid');
    }

    public function postcode()
    {
        return $this->hasMany(PostCode::class, 'thana_uuid', 'uuid');
    }
}
