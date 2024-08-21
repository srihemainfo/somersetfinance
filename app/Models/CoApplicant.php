<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoApplicant extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'co_applicants';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'last_name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function enroll_master_number()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_number_id');
    }
    public function class_room()
    {
        return $this->belongsTo(ClassRoom::class, 'enroll_master_number_id');
    }
}
