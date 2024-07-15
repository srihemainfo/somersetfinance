<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetails extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'purchase_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['purchase_title','purchase_price','sell_vehicle_details_id','created_at','updated_at','deleted_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function SellvehicleDetails()
    {
        return $this->belongsTo(SellVehicleDetails::class, 'sell_vehicle_details_id');
    }

    // public function sellVehicleDetails()
    // {
    //     return $this->hasMany(PurchaseDetails::class, 'model');
    // }



    // public function category(){
    //     return $this->belongsTo(Category::class);
    // }

}
