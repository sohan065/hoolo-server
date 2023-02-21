<?php

/**
 * type: 0 for user, 1 for course creator, 2 for influencer
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'phone',
        'email',
        'email_verification_code',
        'otp',
        'type',
    ];
    protected $attributes = [
        'email' => null,
        'email_verification_code' => null,
        'otp' => null,
        'is_verified' => 0,
        'is_active' => 0,
        'is_banned' => 0,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'otp',
        'email_verification_code',
        'status',
        // 'is_verified',
        // 'is_active',
        // 'is_banned',
        'created_at',
        'updated_at',
    ];
    public function instructor()
    {
        return $this->hasOne(InstructorInfo::class, 'instructor_uuid', 'uuid');
    }
    public function instructorDetail()
    {
        return $this->hasOne(IntstructorDetail::class, 'instructor_uuid', 'uuid');
    }
        public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_uuid', 'uuid');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'uuid');
    }
}
