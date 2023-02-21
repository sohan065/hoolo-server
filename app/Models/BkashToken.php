<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'token',
    ];

    protected $attributes = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
