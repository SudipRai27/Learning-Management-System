<?php

namespace Modules\Lecture\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Lecture extends Model
{
    protected $fillable = ['subject_id', 'lecture_name', 'lecture_description', 'sort_order', 'session_id'];

    protected $table = 'lectures';

    public static function getTableName()
    {
        return with(new Static)->getTable(); 
    }

    public static function getCreateValidationRules($files, $session_id,$subject_id, $sort_order)
    {   
        $rules = [
            
            'sort_order' => [ 
                'required', 
                'min:1', 
                'numeric',
                Rule::unique('lectures')
                        ->where(function ($query) use ($session_id, $subject_id, $sort_order) {
                            return $query->where('subject_id', $subject_id)
                                         ->where('session_id', $session_id)
                                         ->where('sort_order', $sort_order);
                        }), 
            ], 
            'subject_id' => 'required', 
            'lecture_name' => 'required', 
            'lecture_description' => 'required',             
            'session_id' => 'required', 
        ];

        if(count($files))
        {
            $files = count($files);
            foreach(range(0, $files) as $index) {
            $rules['files.' . $index] = 'mimes:pdf,doc,docx,pptx,txt|max:5000';
            }
        }
        return $rules;
    }

    public static function getUpdateValidationRules($files, $session_id,$subject_id, $sort_order, $lecture_id)
    { 
        $rules = [
            'sort_order' => [ 
                'required', 
                'min:1', 
                'numeric', 
                Rule::unique('lectures')
                        ->where(function ($query) use ($session_id, $subject_id, $sort_order, $lecture_id) {
                            return $query->where('subject_id', $subject_id)
                                         ->where('session_id', $session_id)
                                         ->where('sort_order', $sort_order)
                                         ->where('id', '!=', $lecture_id);
                        }), 
            ], 
            'subject_id' => 'required', 
            'lecture_name' =>    'required', 
            'lecture_description' => 'required', 
            'session_id' => 'required', 
        ];
        if(count($files))
        {
            $files = count($files);
            foreach(range(0, $files) as $index) {
            $rules['files.' . $index] = 'mimes:pdf,doc,docx,pptx,txt|max:5000';
            }
        }
        return $rules;
    }

    public static function getValidationMessages()
    {
        return [
            'subject_id.required' => 'Please specify the subject you want to create the lecture for', 
            'lecture_name.required' => 'Please specify the lecture name', 
            'lecture_description.required' => 'Please provide some description', 
            'sort_order.required' => 'The sorting order is required and must be unique according to the session and subject', 
            'session_id.required' => 'Please specify the session', 

        ];
    }

    public function createLecture($lecture_name, $lecture_description, $subject_id, $session_id, $sort_order)
    {	
		return Lecture::create([
			'lecture_name' => $lecture_name, 
			'lecture_description' => $lecture_description, 
			'subject_id' => $subject_id, 
			'session_id' => $session_id,
			'sort_order' => $sort_order
		]);
    }

    public function getLectureListFromSessionAndSubject($session_id, $subject_id)
    {
    	$lectures = Lecture::where('subject_id', $subject_id)
    					->where('session_id', $session_id)
    					->orderBy('sort_order')
                        ->select('id as lecture_id', 'lecture_name', 'lecture_description', 'sort_order')   					
                        ->orderBy('sort_order')
                        ->get()
    					->toArray();

        $lecture_resources = [];
        foreach($lectures as $index => $lecture)
        {
            $lecture['resources'] = (new \App\Resource)
                                    ->getResource($lecture['lecture_id'], 'lectures');
                                    
            $lecture_resources[] = $lecture;                               
        }
        return $lecture_resources;        
    }

    public function getIndividualLectureAndResources($lecture_id)
    {
        $lecture = Lecture::findorFail($lecture_id);
        if($lecture)
        {
            $lecture->resources = (new \App\Resource)
                                    ->getResource($lecture_id, 'lectures');
        }
        return $lecture;
    }


    public function updateLecture($lecture_id, $lecture_name, $lecture_description, $session_id, $subject_id, $sort_order)
    {
        return Lecture::where('id', $lecture_id)
                        ->update([
                            'lecture_name' => $lecture_name, 
                            'lecture_description' => $lecture_description, 
                            'subject_id' => $subject_id, 
                            'session_id' => $session_id, 
                            'sort_order' => $sort_order
                        ]);
    }

    public function getLectureOnly($lecture_id)
    {   
        return Lecture::where('id', $lecture_id)->first();
    }

    public function deleteLecture($lecture_id)
    {
        return Lecture::where('id', $lecture_id)->delete();
    }
}
