<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LoanType extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'loan_types';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['title','created_at','updated_at','deleted_at'];

    public function documents()
    {
        return $this->hasMany(DocumentType::class);
    }

    // public function Model()
    // {
    //     return $this->hasMany(Models::class, 'brand_id');
    // }

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    // public function sellVehicleDetails()
    // {
    //     return $this->hasMany(SellVehicleDetails::class, 'brand');
    // }

}
