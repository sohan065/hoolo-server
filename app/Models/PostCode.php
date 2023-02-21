<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'thana_uuid',
        'city_uuid',
        'name',
    ];
    protected $attributes = [
        'thana_uuid' => null,
        'city_uuid' => null,
        'status' => 1,
    ];
    protected $hidden = [
               'id',
        'status',
        'created_at',
        'updated_at',
    ];
    public function thana()
    {
        return $this->belongsTo(Thana::class, 'uuid', 'thana_uuid');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'uuid', 'city_uuid');
    }
}
