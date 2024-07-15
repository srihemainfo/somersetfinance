<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Brand extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'brands';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['brand','created_at','updated_at','deleted_at'];

    public function Model()
    {
        return $this->hasMany(Models::class, 'brand_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sellVehicleDetails()
    {
        return $this->hasMany(SellVehicleDetails::class, 'brand');
    }




}
