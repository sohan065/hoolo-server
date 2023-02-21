<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempWorkshopOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'workshop_uuid',
        'payment_id',
        'card_type',
        'card_no',
        'refund_ref_id',
        'bank_trans_id',
        'order_code',
        'quantity',
        'price',
        'payment_method',
    ];

    protected $attributes = [
        'quantity' => 1,
        'card_type' => null,
        'card_no' => null,
        'bank_trans_id' => null,
        'payment_id' => null,
        'payment_method' => null,
        'refund_ref_id' => null,
        'trx_id' => null,
        'trx_number' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'uuid', 'user_uuid');
    }
}
