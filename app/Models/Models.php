<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Models extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'models';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['model','brand_id','created_at','updated_at','deleted_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function Brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function sellVehicleDetails()
    {
        return $this->hasMany(SellVehicleDetails::class, 'model');
    }



    // public function category(){
    //     return $this->belongsTo(Category::class);
    // }

}
