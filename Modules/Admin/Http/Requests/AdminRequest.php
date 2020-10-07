<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required', 
            'email' => 'required|email|unique:users',
            'address' =>'required', 
            'phone' => 'required|numeric|unique:users',             
            'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required|numeric',  
            'dob' => 'required|date_format:"Y-m-d',             
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
