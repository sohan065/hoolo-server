<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'order_code',
        'address',
        'post_code',
        'thana',
        'city',
        'state',
        'country',
        'shipping_cost',
        'phone',
        'name',
        'order_status',
        'delivery_status',

    ];

    protected $attributes = [
        'order_status'=>0,
        'delivery_status'=>0,
        'shipping_cost'=>0,
        'thana' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function payment()
    {
        return $this->hasOne(ProductOrderPayment::class, 'order_code', 'order_code');
    }
    public function order()
    {
        return $this->hasMany(ProductOrder::class, 'order_code', 'order_code');
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'uuid', 'country');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'uuid', 'state');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'uuid', 'city');
    }
    public function thana()
    {
        return $this->hasOne(Thana::class, 'uuid', 'thana');
    }
    public function postCode()
    {
        return $this->hasOne(PostCode::class, 'uuid', 'post_code');
    }
}
