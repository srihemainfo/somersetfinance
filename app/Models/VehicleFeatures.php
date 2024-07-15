<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFeatures extends Model
{
    use HasFactory;


    protected $table = 'vehicle_features';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];



    protected $gurded ;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function SellVehicleDetails()
    {
        return $this->belongsToMany(SellVehicleDetails::class,'sell_vehicle_detail_vehicle_feature','sell_vehicle_detail_id','vehicle_feature_id');
    }

//     public function sellVehicleDetails()
// {
//     return $this->belongsToMany(SellVehicleDetail::class, 'sell_vehicle_detail_vehicle_feature', 'vehicle_feature_id', 'sell_vehicle_detail_id');
// }


    // public function setTitleAttribute($value)
    // {
    //     $this->attributes['title'] = json_encode($value);
    // }

    // /**
    //  * Get the Titleegories
    //  *
    //  */
    // public function getTitleAttribute($value)
    // {
    //     return $this->attributes['title'] = json_decode($value);
    // }


}
