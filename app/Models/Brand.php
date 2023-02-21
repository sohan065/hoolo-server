<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'status',
        'icon',
    ];

    protected $attributes = [
        'status' => 0,
        'requested' => 0,
    ];

    protected $hidden = [
        'id',
        'status',
        'requested',
        'created_at',
        'updated_at',
    ];
}
