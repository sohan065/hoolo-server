<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'instructor_uuid',
        'dp_category_uuid',
        'title',
        'type',
        'slot',
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

    public function instructor()
    {
        return $this->hasOne(InstructorInfo::class, 'instructor_uuid', 'instructor_uuid');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'instructor_uuid');
    }

    public function dpCategory()
    {
        return $this->hasOne(DpCategory::class, 'uuid', 'dp_category_uuid');
    }
    public function details()
    {
        return $this->hasOne(CourseDetail::class, 'course_uuid', 'uuid');
    }
    public function order()
    {
        return $this->hasMany(CourseOrder::class, 'course_uuid', 'uuid');
    }
    public function session()
    {
        return $this->hasOne(CourseSession::class, 'course_uuid', 'uuid');
    }
    public function featuredCourse()
    {
        return $this->belongsTo(FeaturedCourse::class, 'course_uuid', 'uuid');
    }
}
