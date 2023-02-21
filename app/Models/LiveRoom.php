<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'creator_uuid',
        'room_id',
        'course_uuid',
        'type',
    ];
    protected $attributes = [
        'ended' => 0,
    ];
    protected $hidden = [
        'id'
    ];
}
