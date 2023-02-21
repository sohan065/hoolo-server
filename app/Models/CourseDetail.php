<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'course_uuid',
        'price',
        'discount',
        'discount_duration',
        'discount_type',
        'language',
        'level',
        'summary',
        'cover',
    ];
    protected $attributes = [
        'discount' => null,
        'discount_duration' => null,
        'discount_type' => null,
        'price' => 0,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function cover(){
        return $this->hasOne(CourseGallery::class,'uuid','cover');
    }
    
}
