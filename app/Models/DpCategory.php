<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'icon',
        'status',
        'is_active',
    ];
    protected $attributes = [
        'status' => 0,
        'is_active' => 1,
        'icon'=>null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
