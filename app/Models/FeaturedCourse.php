<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'course_uuid',
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
    public function course()
    {
        return $this->hasOne(Course::class, 'uuid', 'course_uuid');
    }
}
