<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'course_uuid',
        'session_title',
        'schedule',
        'details',
    ];
    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $cast = [
        'session_title' => 'array',
        'details' => 'array',
        'schedule' => 'array',
    ];
}
