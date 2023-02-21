<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'course_uuid',
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
    
    public function course(){
        return $this->hasOne(Course::class,'uuid','course_uuid');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'uuid', 'user_uuid');
    }
}
