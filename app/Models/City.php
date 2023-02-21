<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'state_uuid',
        'name',
    ];
    protected $attributes = [
        'status' => 1,
    ];
    protected $hidden = [
              'id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'uuid', 'state_uuid');
    }
    public function thana()
    {
        return $this->hasMany(Thana::class, 'city_uuid', 'uuid');
    }
    public function postcode()
    {
        return $this->hasMany(PostCode::class, 'city_uuid', 'uuid');
    }
}
