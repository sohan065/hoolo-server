<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Category extends Model

{

    use HasFactory;



    protected $fillable = [

        'uuid',

        'name',
        'is_active',
        'status',

        'pcategory_uuid',

        'icon',

    ];

    protected $attributes = [
        'is_active' => 1,
        'status' => 0,

    ];

    protected $hidden = [

        'id',
        'created_at',
        'updated_at',

    ];

    public function  pcategory()

    {

        return $this->hasOne(PCategory::class, 'uuid', 'pcategory_uuid');
    }
}