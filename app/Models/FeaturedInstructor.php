<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedInstructor extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'instructor_uuid',
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
}
