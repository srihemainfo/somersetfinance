<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPeriodAllocate extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'students_period_allocation';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'class',
        'batch',
        'period',
        'subject',
        'student',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'student');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'class');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

}
