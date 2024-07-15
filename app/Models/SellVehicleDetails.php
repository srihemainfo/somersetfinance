<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellVehicleDetails extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'sell_vehicle_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [

            'brand',
            'event_slug',
            'model',
            'year',
            'country',
            'city',
            'transmission',
            'new_old',
            'engine_cc',
            'fuel_type',
            'colour',
            'image',
            'customer_name',
            'display_date',
            'customer_number',
            'vehicle_price',
            'content',
            'status',
            'shord_order',
            'registration_no',
            'millage',
            'ref_id',
            'meta_title',
            'meta_keyword',
            'meta_description'

    ] ;



    protected $gurded ;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function SellVehicleFeatures()
    {
        return $this->belongsToMany(VehicleFeatures::class,'sell_vehicle_detail_vehicle_feature','sell_vehicle_detail_id','vehicle_feature_id');
    }

    public function brandd()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }


    // public function Brand()
    // {
    //     return $this->belongsTo(Brand::class);
    // }

    public function ModelV()
    {
        return $this->belongsTo(Models::class,'model','id');
    }

    public function PurchaseDetails()
    {
        return $this->hasMany(PurchaseDetails::class, 'sell_vehicle_details_id');
    }

    public function EventImages()
    {
        return $this->hasMany(EventImage::class, 'event_id');
    }



//     public function vehicleFeatures()
// {
//     return $this->belongsToMany(VehicleFeature::class, 'sell_vehicle_detail_vehicle_feature', 'sell_vehicle_detail_id', 'vehicle_feature_id');
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
