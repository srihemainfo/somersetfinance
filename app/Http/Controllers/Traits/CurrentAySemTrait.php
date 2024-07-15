<?php

namespace App\Http\Controllers\Traits;

use App\Models\AcademicYear;
use App\Models\CourseEnrollMaster;
use App\Models\SubjectAllotment;
use App\Models\Semester;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Session;

trait CurrentAySemTrait
{
    public function getCurrent_Ay_Sem()
    {

        $getAys = ClassRoom::whereIn('grade_id', function ($query) {
            $query->select('course')
                ->from('subject_allotment')
                ->whereIn('course', SubjectAllotment::whereIn('academic_year', AcademicYear::where(['status' => 1])->pluck('id'))
                    ->select('course')
                    ->distinct()
                    ->pluck('course'));
        })->pluck('id');
       Session::put('currentClasses', $getAys);
        return true;
    }
}
