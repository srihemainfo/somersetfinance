<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolsCourse extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'tools_courses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'department_id',
        'degree_type_id',
        'group_id',
        'edu_board_id',
        'medium_id',
        'name',
        'short_form',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department_id');
    }

    public function degree()
    {
        return $this->belongsTo(ToolsDegreeType::class, 'degree_type_id');
    }
    public function eduBoard()
    {
        return $this->belongsTo(EducationBoard::class, 'edu_board_id');
    }
    public function medium()
    {
        return $this->belongsTo(MediumofStudied::class, 'medium_id');
    }
    public function group()
    {
        return $this->belongsTo(GradeGroupModel::class, 'group_id');
    }
}
