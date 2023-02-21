<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredApp extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'app_name',
        'domain',
        'email',
        'phone',
        'platform',
        'app_key',
    ];
    protected $attributes = [
        'domain' => null,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
    ];
}
