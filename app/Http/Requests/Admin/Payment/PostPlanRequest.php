<?php

namespace App\Http\Requests\Admin\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PostPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'                     => 'required',
            'test'                      => 'required',
            'description'               => 'required',
            'status'                    => 'required|in:ACTIVE,INACTIVE',
            'plan_for'                  => 'required|in:EMPLOYER,COACH'
        ];
    }
}
