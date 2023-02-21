<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopOrder extends Model
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
        'status',
    ];

    protected $attributes = [
        'status' => 0,
        'quantity' => 1,
        'payment_id' => null,
        'card_type' => null,
        'card_no' => null,
        'bank_trans_id' => null,
        'payment_with' => null,
        'trx_id' => null,
        'trx_number' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function course()
    {
        return $this->hasOne(WorkShop::class, 'uuid', 'workshop_uuid');
    }
}
