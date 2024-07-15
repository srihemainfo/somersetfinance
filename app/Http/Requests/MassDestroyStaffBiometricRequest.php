<?php

namespace App\Http\Requests;

use App\Models\StaffBiometric;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyStaffBiometricRequest extends FormRequest
{
    // public function authorize()
    // {
    //     abort_if(Gate::denies('leave_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return true;
    // }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:staff_biometrics,id',
        ];
    }
}
