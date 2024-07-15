<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomDetail extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'customer_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'address1',
        'remark'
    ];


    public function Application()
    {

        return $this->hasOne(Application::class);
    }

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }
    // public function user_name()
    // {
    //     return $this->belongsTo(User::class, 'user_name_id');
    // }

    // public function enroll_master_number()
    // {
    //     return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_number_id');
    // }
    // public function class_room()
    // {
    //     return $this->belongsTo(ClassRoom::class, 'enroll_master_number_id');
    // }
}
