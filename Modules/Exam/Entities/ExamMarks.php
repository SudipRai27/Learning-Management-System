<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;

class ExamMarks extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'subject_id', 'obtained_marks'];

    protected $table = 'exam_marks'; 

    protected $guarded = ['created_at', 'updated_at']; 

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static function getValidationRules($exam_full_marks) {
    	return [
    		'session_id' => 'required', 	 
    		'exam_id' => 'required', 
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
       		'exam_id.required' => 'Please select the exam you want to upload marks for', 
    		'subject_id.required' => 'Please specify the subject you want to upload the marks for', 
            'marks.*.required' => 'The obtained marks field is required', 
            'marks.*.regex' => 'The obtained marks can only have upto two decimal places',
            'marks.*.between' => 'The obtained marks must be between zero and full marks',
            'marks.*.numeric' => 'The obtained marks must be a number'
    		
    	];
    }

    public function getExamMarks($session_id, $exam_id, $subject_id)
    {
        $exam_table = \Modules\Exam\Entities\Exam::getTableName();
        $enrolled_subject_students = (new \Modules\Enrollment\Entities\EnrollmentSubject)->getSubjectEnrolledStudents($session_id, $subject_id);

        foreach($enrolled_subject_students as $index => $record)
        {
            $marks =
                     \DB::table($this->table)
                    ->join($exam_table, $exam_table.'.id', '=', $this->table.'.exam_id')
                    ->where($exam_table.'.session_id', $session_id)
                    ->where($this->table.'.exam_id', $exam_id)
                    ->where($this->table.'.subject_id', $subject_id)
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

    public function getExamMarksFromSessionSubjectAndStudentId($session_id, $subject_id, $student_id)
    {
        $exam_table = \Modules\Exam\Entities\Exam::getTableName();
        $subject_table = \Modules\Subject\Entities\Subject::getTableName();
        return \DB::table($this->table)
                ->join($exam_table, $exam_table.'.id','=',$this->table.'.exam_id')
                ->join($subject_table,$subject_table.'.id','=', $this->table.'.subject_id')
                ->where($exam_table.'.session_id', $session_id)
                ->where($this->table.'.student_id', $student_id)
                ->where($this->table.'.subject_id', $subject_id)
                ->select('exam_name', 'marks as full_marks', 'obtained_marks')
                ->get()
                ->toArray();
    }

    public function uploadExamMarks($input)
    {        
        foreach($input['marks'] as $student_id => $marks)
        {
            $this->updateOrCreate(
                [
                'exam_id' => $input['exam_id'], 
                'student_id' => $student_id, 
                'subject_id' => $input['subject_id'], 
                ],
                ['obtained_marks' => $marks]);
        }
        return;
    }

    public function updateSingleStudentExamMarks($input)
    {
        return $this->where('exam_id', $input['exam_id'])
                    ->where('student_id', $input['student_id'])
                    ->where('subject_id', $input['subject_id'])
                    ->update([
                        'obtained_marks' => $input['new_marks']
                    ]);
    }
}
    