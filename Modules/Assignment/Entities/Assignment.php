<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Assignment extends Model
{
	protected $table = 'assignment';

	protected $fillable = ['title', 'description', 'session_id', 'subject_id', 'marks', 'submission_date', 'sort_order'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
    	return with(new Static)->getTable(); 
    }

    public static function getCreateValidationRules($files, $session_id, $subject_id, $sort_order) {

    	$rules = [
    		'sort_order' => [ 
                'required', 
                'min:1', 
                'numeric',
                Rule::unique('assignment')
                        ->where(function ($query) use ($session_id, $subject_id, $sort_order) {
                            return $query->where('subject_id', $subject_id)
                                         ->where('session_id', $session_id)
                                         ->where('sort_order', $sort_order);
                        }), 
            ], 
            'session_id' => 'required', 
            'subject_id' => 'required', 
            'title' => 'required', 
            'description' => 'required', 
            'marks' => 'required|min:1|numeric', 
            'submission_date' => 'required'
            
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

    public static function getUpdateValidationRules($files, $session_id, $subject_id, $sort_order, $assignment_id)
    {
    	$rules = [
            'sort_order' => [ 
                'required', 
                'min:1', 
                'numeric', 
                Rule::unique('assignment')
                        ->where(function ($query) use ($session_id, $subject_id, $sort_order, $assignment_id) {
                            return $query->where('subject_id', $subject_id)
                                         ->where('session_id', $session_id)
                                         ->where('sort_order', $sort_order)
                                         ->where('id', '!=', $assignment_id);
                        }), 
            ], 
            'session_id' => 'required', 
            'subject_id' => 'required', 
            'title' => 'required', 
            'description' => 'required', 
            'marks' => 'required|min:1|numeric', 
            'submission_date' => 'required'
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
            'subject_id.required' => 'Please specify the subject you want to create the assignment for', 
            'title.required' => 'Please specify the assingment title', 
            'description.required' => 'Please provide some description', 
            'sort_order.unique' => 'The sorting order is required and must be unique according to the session and subject', 
            'session_id.required' => 'Please specify the session', 
            'marks.required' => 'Please specify marks greater than zero', 
            'submission_date' => 'Please specify the submission date'
        ];
    }


    public function createAssignment($input)
    {
    	return $this->create([
    		'title' => $input['title'],  
    		'description' => $input['description'], 
    		'session_id' => $input['session_id'], 
    		'subject_id' => $input['subject_id'], 
    		'marks' => $input['marks'], 
    		'submission_date' => $input['submission_date'], 
    		'sort_order' => $input['sort_order']
    	]);
    }

    public function getAssignmentListFromSessionAndSubject($session_id, $subject_id)
    {
    	$assignments = $this->where('session_id', $session_id)
    						->where('subject_id', $subject_id)
    						->select('id as assignment_id', 'title', 'description', 'marks', 'submission_date', 'sort_order', 'session_id', 'subject_id')
    						->orderBy('sort_order')
    						->get()
    						->toArray(); 

    	$assignment_resources = [];
        foreach($assignments as $index => $assignment)
        {
            $assignment['resources'] = (new \App\Resource)
                                    ->getResource($assignment['assignment_id'], 'assignment');
                                    
            $assignment_resources[] = $assignment;                               
        }        
        return $assignment_resources;        					
    }

    public function getAssignmentSelectListOnly($session_id, $subject_id)
    {
        $assignments = $this->where('session_id', $session_id)
                            ->where('subject_id', $subject_id)
                            ->select('id', 'title')
                            ->orderBy('sort_order')
                            ->get();
        return $assignments;
    }

    public function getIndividualAssignmentAndResources($assignment_id)
    {
    	$assignment = Assignment::findOrFail($assignment_id);
    	if($assignment)
        {
            $assignment->resources = (new \App\Resource)
                                    ->getResource($assignment_id, 'assignment');
        }
        return $assignment;
    }

    public function getIndividualAssignmentOnly($assignment_id)
    {
    	return $this->where('id', $assignment_id)->first();
    }

    public function updateAssignment($assignment_id, $input)
    {
    	$formattedDate = (new \App\DayAndDateTime)->formatDateTime($input['submission_date']);
        $input['submission_date'] = $formattedDate;
       	return $this->where('id',$assignment_id)
    				->update([
			    		'title' => $input['title'],  
			    		'description' => $input['description'], 
			    		'session_id' => $input['session_id'], 
			    		'subject_id' => $input['subject_id'], 
			    		'marks' => $input['marks'], 
			    		'submission_date' => $input['submission_date'], 
			    		'sort_order' => $input['sort_order']
			    	]);
    }

    public function deleteAssignment($assignment_id)
    {
    	return $this->where('id', $assignment_id)->delete();
    }


}
