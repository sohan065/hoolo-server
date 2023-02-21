<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'user_uuid',
        'country_uuid',
        'state_uuid',
        'city_uuid',
        'thana_uuid',
        'post_code_uuid',
        'address',
    ];

    protected $attributes = [
        'thana_uuid' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
     public function country()
    {
        return $this->hasOne(Country::class, 'uuid', 'country_uuid');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'uuid', 'state_uuid');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'uuid', 'city_uuid');
    }
    public function thana()
    {
        return $this->hasOne(Thana::class, 'uuid', 'thana_uuid');
    }
    public function postCode()
    {
        return $this->hasOne(PostCode::class, 'uuid', 'post_code_uuid');
    }  
    
    public function userInfo()
    {
        return $this->hasOne(User::class, 'uuid', 'user_uuid');
    }
}
