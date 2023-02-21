<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superadmin extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'username',
        'fullname',
        'email',
        'phone',
        'password',
    ];
    protected $attributes = [
        'email_verification_code' => null,
        'phone_verification_code' => null,
        'is_phone_verified' => 0,
        'is_email_verified' => 0,
        'status' => 1,
    ];
    protected $hidden = [
        'id',
        'password',
        'email_verification_code',
        'phone_verification_code',
    ];
}
