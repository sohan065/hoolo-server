<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GCategoryCampaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'g_category_uuid',
        'title',
        'cover',
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
    protected $cast = [
        'g_category_uuid' => 'json',
    ];
}
