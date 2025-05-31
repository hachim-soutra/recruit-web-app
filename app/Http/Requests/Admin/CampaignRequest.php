<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
            'object' => 'required',
            'dateEnvoi' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'selectedRecipients' => 'required|array|min:1',
            'selectedJobs' => 'required|array|min:1',
            'title' => 'required',
            'description' => 'required',
        ];
    }
}
