<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'post_uuid',
        'user_uuid',
        'parent_uuid',
        'comment',
        'attachment',
        'status',
    ];
    protected $attributes = [
        'status' => 0,
         'attachment'=>null,
         'post_uuid'=>null,
    ];
    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_uuid', 'user_uuid');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'user_uuid');
    }
    public function reply(){
        return $this->hasMany(PostComment::class,'parent_uuid','uuid');
    }
}
