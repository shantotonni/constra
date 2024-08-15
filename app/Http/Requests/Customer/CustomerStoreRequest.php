<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
           'first_name'=>'required',
           'last_name'=>'required',
           'email'=>'required',
           'phone'=>'required',
           'ages_of_children'=>'required',
           'ages_of_father'=>'required',
           'want_to_receive_email'=>'required',
           'customer_status'=>'required',
        ];
    }
}
