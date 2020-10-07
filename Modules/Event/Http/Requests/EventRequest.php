<?php

namespace Modules\Event\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        $rules = [
            'event_title' => 'required|max:255', 
            'description' => 'required', 
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',            
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',            
            'location' => 'required|max:255', 
            'event_for' => 'required',            
        ];
        
        $input = request()->all();
        $files = isset($input['featured_image']) ? $input['featured_image'] : [];        
        if(count($files))
        {                        
            foreach(range(0, count($files)) as $index) 
            {
                $rules['featured_image.' . $index] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            }
        }

        return $rules;
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
