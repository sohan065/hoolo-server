<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
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
        return $this->hasMany(State::class, 'country_uuid', 'uuid');
    }
}
