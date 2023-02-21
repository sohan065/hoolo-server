<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProductOrder extends Model
{
    use HasFactory;
   protected $fillable = [
        'uuid',
        'user_uuid',
        'product_uuid',
        'merchant_uuid',
        'payment_id',
        'card_type',
        'card_no',
        'refund_ref_id',
        'bank_tran_id',
        'order_code',
        'quantity',
        'price',
        'payment_method',
        'address_uuid',
    ];

    protected $attributes = [
        'card_type' => null,
        'card_no' => null,
        'bank_tran_id' => null,
        'payment_id' => null,
        'payment_method' => null,
        'refund_ref_id' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
