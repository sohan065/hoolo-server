<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'country_uuid',
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

    public function country()
    {
        return $this->belongsTo(Country::class, 'uuid', 'country_uuid');
    }
    public function city()
    {
        return $this->hasMany(City::class, 'state_uuid', 'uuid');
    }
}
