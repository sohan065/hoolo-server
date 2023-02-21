<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'merchant_uuid',
        'path',
        'type',
        'status',
    ];
    protected $attributes = [
        'status' => 0,

    ];
    protected $hidden = [
        'id',
    ];
}
