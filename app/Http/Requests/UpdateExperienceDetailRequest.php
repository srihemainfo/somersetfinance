<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('experience_detail_edit');
    }

    public function rules()
    {
        return [
            'designation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'department' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'last_drawn_salary' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name_of_organisation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'taken_subjects' => [
                'string',
                'min:1',
                'max:1000',
                'nullable',
            ],
            'doj' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'dor' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'responsibilities' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
            'leaving_reason' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
            'address' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
        ];
    }
}
