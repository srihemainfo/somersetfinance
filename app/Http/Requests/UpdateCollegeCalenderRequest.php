<?php

namespace App\Http\Requests;

use App\Models\CollegeCalender;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCollegeCalenderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('school_calender_edit');
    }

    public function rules()
    {
        return [
            'type' => [
                'string',
                'nullable',
            ],
            'academic_year' => [
                'string',
                'nullable',
            ],
            'shift' => [
                'string',
                'nullable',
            ],
            'semester_type' => [
                'string',
                'nullable',
            ],
            'from_date' => [
                'string',
                'nullable',
            ],
            'to_date' => [
                'string',
                'nullable',
            ],
        ];
    }
}
