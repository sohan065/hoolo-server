<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_uuid',
        'path',
    ];
    protected $hidden = [
        'id',
    ];
}
