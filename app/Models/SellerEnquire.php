<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SellerEnquire extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'customer_sell_vehicle_details';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['id','first_name','last_name','phone','email', 'post_code', 'brand', 'model','registration_no','year','millage','colour','fuel_type','transmission','service_history','service_book_history','last_service_date','last_service_millage','number_of_owner','car_modified','tyre_brand','tyre_condition','damage_report','outstanding_finance','desired_value','status'];
    protected $guared;


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }





}
