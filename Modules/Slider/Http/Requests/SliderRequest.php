<?php

namespace Modules\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $input = request()->all();
        if($input['action'] == 'create')
        {
            $sort_order = 'required|unique:slider';
            $image = 'required|mimes:jpeg,png,jpg,gif,svg|max:5048';
           
        }
        else
        {
            $sort_order = 'required|unique:slider,sort_order,'.$input['slider_id'];
            $image = 'mimes:jpeg,png,jpg,gif,svg|max:5048'; 
        }

        return [
                'title' => 'required|max:255', 
                'description' => 'required', 
                'sort_order' => $sort_order,
                'featured_image.0' => $image,
                'is_active' => 'required'
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
