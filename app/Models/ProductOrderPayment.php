<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'order_code',
        'payment_method',
        'trx_number',
        'trx_id',
        'payment_id',
        'payment_with',
        'card_type',
        'card_no',
        'bank_tran_id',
        'status',
    ];

    protected $attributes = [
        'status' => 0,
        'trx_number' => null,
        'trx_id' => null,
        'payment_id' => null,
        'card_type' => null,
        'card_no' => null,
        'bank_tran_id' => null,
        'payment_with' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    
    public function orders(){
        return $this->hasMany(ProductOrder::class,'order_code','order_code');
    }
    public function orderDetails(){
        return $this->hasOne(ProductOrderDetail::class,'order_code','order_code');
    }
}
