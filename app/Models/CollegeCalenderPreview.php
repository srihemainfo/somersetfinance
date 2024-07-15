<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollegeCalenderPreview extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'college_calenders_preview';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected  $gurded;

    // protected $fillable = [
    //     'start_date',
    //     'end_date',
    //     'date',
    //     'dayorder',
    //     'bookmark',
    //     'academic_year',
    //     'batch',
    //     'college_calender_id',
    //     'created_at',
    //     'updated_at',
    //     'deleted_at',
    // ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function collegeCalender(){
        return $this->belongsTo(CollegeCalender::class, 'college_calender_id') ;
    }
}
