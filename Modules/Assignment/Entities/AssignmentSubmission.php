<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignmentSubmission extends Model
{
    protected $table = 'assignment_submission';

    protected $fillable = ['assignment_id', 'student_id'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
        return with(new Static)->getTable(); 
    }

    public static function getValidationRules($files) {
        $rules = [
            'assignment_id' => 'required'
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

    public static function getValidationMessages() {
        return [            
            'assignment_id.required' => 'The assignment id is required'
        ];
    }

    public function createUpdateSubmission($assignment_id, $student_id)
    {
        $query  = $this->where('assignment_id', $assignment_id)
                        ->where('student_id', $student_id)
                        ->first();
                        
        if($query)
        {
            $query->touch();
            return $query;
        }
        else
        {
            return $this->create([
                'assignment_id' => $assignment_id, 
                'student_id' => $student_id
            ]);
        }
    }

    public function getAssignmentSubmission($assignment_id, $student_id)
    {
        return $this->where('assignment_id', $assignment_id)
                     ->where('student_id', $student_id)
                     ->first();    
    }

    public function checkStudentSubmission($assignment_id, $student_id)
    {
        $submission = $this->getAssignmentSubmission($assignment_id, $student_id);
        if($submission)
        {      
            if(count((new \App\Resource)->getResource($submission->id,'assignment_submission')))
            {
                return 'submitted';
            }
            
        }
        return 'not-submitted';
    }

    public function getAssignmentSubmissionFiles($assignment_id, $student_id)
    {
        $resource_table = \App\Resource::getTableName();
        return \DB::table($this->table)
                    ->join($resource_table, $resource_table.'.resource_id','=',$this->table.'.id')
                    ->where($this->table.'.assignment_id','=',$assignment_id)
                    ->where($this->table.'.student_id', $student_id)
                    ->where($resource_table.'.resource_table', 'assignment_submission')
                    ->select($this->table.'.id as submission_id', $resource_table.'.id as resource_id', 'unique_id', 'filename', 's3_url', $this->table.'.updated_at')
                    ->get()
                    ->toArray();
    }

    public function getStudentSubmissionDetails($session_id, $subject_id, $assignment_id)
    {
        $students = (new \Modules\Enrollment\Entities\EnrollmentSubject)->getSubjectEnrolledStudents($session_id, $subject_id);
        
        $total_submissions = 0;
        $total_students = 0;
        $student_submission_details = [];
        

        foreach($students as $index => $std)
        {            
            $submission_files = $this->getAssignmentSubmissionFiles($assignment_id, $std->student_id);      
            $std->submission = $submission_files;
            if(count($submission_files))
            {
                $std->submission_id = $submission_files[0]->submission_id;    
                $std->updated_at = $submission_files[0]->updated_at;
                $total_submissions += 1;
            }
            $total_students += 1;
            
        }        
        $student_submission_details['total_submissions'] = isset($total_submissions) ? $total_submissions : '';
        $student_submission_details['total_students'] = isset($total_students) ? $total_students : '';
        $student_submission_details['submissions'] = $students;
        unset($students);

        return $student_submission_details;
    }

    public function getSingleSubmission($submission_id)
    {
        return $this->findorFail($submission_id); 
    }

    public function getIndividualSubmissionAndResources($submission_id)
    {
        $submission = $this->getSingleSubmission($submission_id); 
        if($submission)
        {
            $submission->resources = (new \App\Resource)
                                    ->getResource($submission_id, 'assignment_submission');
        }
        return $submission;
        
    }


    public function deleteSubmission($submission_id)
    {
        return $this->where('id', $submission_id)->delete();
    }

}
