<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class PostLike extends Model

{

    use HasFactory;

    protected $fillable = [

        'uuid',

        'post_uuid',

        'user_uuid',

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

    protected $casts = [

        'user_uuid' => 'array',

    ];
    public function post(){
        return $this->hasOne(Post::class,'uuid','post_uuid');
    }

}

