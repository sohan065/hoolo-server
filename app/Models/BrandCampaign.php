<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BrandCampaign extends Model

{

    use HasFactory;



    protected $fillable = [

        'uuid',

        'brand_uuid',

        'title',

        'cover',

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

    protected $cast = [

        'brand_uuid' => 'array',

    ];
}

// 