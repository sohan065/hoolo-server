<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'path',
        'type',
        'status',

    ];

    protected $attributes = [
        'status' => 0,
    ];

    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];
}
