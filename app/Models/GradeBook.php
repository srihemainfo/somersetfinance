<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeBook extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'grade_book';

    protected $fillable = [
        'user_name_id',
        'regulation',
        'academic_year',
        'exam_date',
        'course',
        'published_date',
        'result_type',
        'semester',
        'subject',
        'grade',
        'result',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRegulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
    }

    public function getSubject()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    public function getCourse()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }

    public function getGrade()
    {
        return $this->belongsTo(GradeMaster::class, 'grade');
    }

}
