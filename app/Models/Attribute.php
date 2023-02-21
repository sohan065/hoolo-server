<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'requested',
    ];

    protected $attributes = [
        'status' => 0,
    ];

    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
        'requested',
    ];
}
