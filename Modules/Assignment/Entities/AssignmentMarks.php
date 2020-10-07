<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignmentMarks extends Model
{
	protected $table = 'assignment_marks';

	protected $fillable = ['assignment_id', 'student_id', 'obtained_marks'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
    	return with(new Static)->getTable(); 
    }

    public static function getValidationRules($exam_full_marks) {
        return [
            'session_id' => 'required',      
            'assignment_id' => 'required', 
            'subject_id' => 'required', 
            'marks.*' => 
            [   
                'required', 
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'between:0,'.$exam_full_marks
            ]            
        ];
    }

    public static function getValidationMessages() {
        return [
            'session_id.required' => 'Please select the session you want to upload the exam marks for.', 
            'assignment_id.required' => 'Please select the exam you want to upload marks for', 
            'subject_id.required' => 'Please specify the subject you want to upload the marks for', 
            'marks.*.required' => 'The obtained marks field is required', 
            'marks.*.regex' => 'The obtained marks can only have upto two decimal places',
            'marks.*.between' => 'The obtained marks must be between zero and full marks',
            'marks.*.numeric' => 'The obtained marks must be a number'
            
        ];
    }

    public function getAssignmentMarks($session_id, $subject_id, $assignment_id)
    {
        $assignment_table = \Modules\Assignment\Entities\Assignment::getTableName();
        $enrolled_subject_students = (new \Modules\Enrollment\Entities\EnrollmentSubject)->getSubjectEnrolledStudents($session_id, $subject_id);
        foreach($enrolled_subject_students as $index => $record)
        {
            $marks =
                     \DB::table($this->table)
                    ->join($assignment_table, $assignment_table.'.id', '=', $this->table.'.assignment_id')
                    ->where($assignment_table.'.session_id', $session_id)
                    ->where($assignment_table.'.subject_id', $subject_id)
                    ->where($this->table.'.assignment_id', $assignment_id)
                    ->where($this->table.'.student_id', $record->student_id)
                    ->select($this->table.'.obtained_marks')
                    ->first();

            if($marks)
            {
                $record->obtained_marks = $marks->obtained_marks;
            }
            else
            {
                $record->obtained_marks = '';
            }
                    
        }

        return $enrolled_subject_students;    
    }

    public function getAssignmentMarksFromSessionSubjectAndStudentID($session_id, $subject_id, $student_id)
    {
        $assignment_table = \Modules\Assignment\Entities\Assignment::getTableName();
        $subject_table = \Modules\Subject\Entities\Subject::getTableName();
        return \DB::table($this->table)   
                ->join($assignment_table, $assignment_table.'.id', '=', $this->table.'.assignment_id')
                ->join($subject_table, $subject_table.'.id', '=', $assignment_table.'.subject_id')
                ->where($assignment_table.'.session_id', $session_id)
                ->where($assignment_table.'.subject_id', $subject_id)
                ->where($this->table.'.student_id', $student_id)
                ->select('assignment_id', 'obtained_marks','title','marks as full_marks')
                ->get()
                ->toArray();
    }

    public function uploadAssignmentMarks($input)
    {       
        foreach($input['marks'] as $student_id => $marks)
        {
            $this->updateOrCreate(
                [
                'assignment_id' => $input['assignment_id'], 
                'student_id' => $student_id, 
                 
                ],
                ['obtained_marks' => $marks]);
        }
        return;
    }

    public function updateSingleStudentAssignmentMarks($input)
    {
        return $this->where('assignment_id', $input['assignment_id'])
                    ->where('student_id', $input['student_id'])                   
                    ->update([
                        'obtained_marks' => $input['new_marks']
                    ]);
    }

    public function getIndividualStudentAssignmentMarks($assignment_id, $student_id)
    {
        return $this->where('assignment_id', $assignment_id)
                    ->where('student_id', $student_id)
                    ->first();                     
    }
}
