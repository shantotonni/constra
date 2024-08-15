<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreeEvaluationRequest extends FormRequest
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
            'name'=>'required',
            'email'=>'required',
            'country_residence'=>'required',
            'city'=>'required',
            'country_citizenship'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'phone'=>'required',
            'marital_status'=>'required',
            'dependant_children'=>'required',
            'english_read'=>'required',
            'english_write'=>'required',
            'english_speak'=>'required',
            'english_listen'=>'required',
            'french_read'=>'required',
            'french_write'=>'required',
            'french_speak'=>'required',
            'french_listen'=>'required',
            'post_secondary_education'=>'required',
            'total_year_of_education'=>'required',
            'work_experience'=>'required',
            'image'=>'required',
        ];
    }
}
