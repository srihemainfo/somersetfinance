<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeGroupModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'grade_group';

    protected $guarded;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function course()
    {
        return $this->hasMany(CourseEnrollMaster::class, 'group_id', 'id');
    }
}
