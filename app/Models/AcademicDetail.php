<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicDetail extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'academic_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'enroll_master_number_id',
        'register_number',
        'emis_number',
        'roll_no',
        'admitted_mode',
        'admitted_course',
        'admitted_category',
        'scholarship_type',
        'scholarship_amt',
        'from_gov_fee',
        'batch',
        'user_name_id',
        'from_gov_fee',
        'created_at',
        'updated_at',
        'deleted_at',
        'student_id',
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
