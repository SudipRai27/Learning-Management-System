<?php

namespace Modules\Result\Entities;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['session_id', 'student_id', 'subject_id', 'assignment_marks', 'assignment_assessable_marks', 'exam_marks', 'exam_assessable_marks', 'total_assessable_marks', 'grade', 'total_obtained_marks'];

    protected $table = 'result';

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public function getGrade($marks)
    {
    	if(is_null($marks))
    	{
    		return 'N/A';
    	}
    	if($marks < 50)
    	{
    		return 'F';
    	}
    	else if($marks >=50 && $marks < 60) 
    	{
    		return 'P';
    	}
    	else if($marks >=60 && $marks < 70)
    	{
    		return 'C';
    	}
    	else if($marks >= 70 && $marks < 80)
    	{
    		return 'D'; 
    	}
    	else
    	{
    		return 'HD';
    	}
    }

    public function generateResult($input) 
    {
    	$student_details = [];
        foreach($input['studentEnrollmentId'] as $student_id => $enrollment_id)
        {
            $enrolled_subjects = (new \Modules\Enrollment\Entities\EnrollmentSubject)->getEnrolledSubjectFromEnrollmentID($enrollment_id[0]);
            
            $student_details[$student_id]['student_id'] = $student_id;
            // $student_details[$student_id]['enrolled_subjects'] = $enrolled_subjects;
            foreach($enrolled_subjects as $index => $subject)
            {
                $assignment_marks = (new \Modules\Assignment\Entities\AssignmentMarks)->getAssignmentMarksFromSessionSubjectAndStudentID($input['session_id'], $subject->subject_id, $student_id);                
                $exam_marks = (new \Modules\Exam\Entities\ExamMarks)->getExamMarksFromSessionSubjectAndStudentId($input['session_id'], $subject->subject_id, $student_id);
                $student_details[$student_id]['assessment_details'][$subject->subject_id]['assignment_details'][] = $assignment_marks;
                $student_details[$student_id]['assessment_details'][$subject->subject_id]['exam_details'][] = $exam_marks;
                $student_details[$student_id]['assessment_details'][$subject->subject_id]['subject_name'] = $subject->subject_name;
                unset($exam_marks);
                unset($assignment_marks);
                
            }
            unset($enrolled_subjects);                                    
        }
    
        foreach($student_details as $student_id => $record)
        {
            foreach($record['assessment_details'] as $sub_id => $assessment)
            {
                
                foreach($assessment['assignment_details'] as $index => $mark_details)
                {   
                    if(count($mark_details))
                    {
                        $assignment_obtained_marks = 0;
                        $assignment_assessable_marks = 0;
                        foreach ($mark_details as $p => $d) {
                            $assignment_obtained_marks = $assignment_obtained_marks + $d->obtained_marks;
                            $assignment_assessable_marks = $assignment_assessable_marks + $d->full_marks;
                        }                               
                        $student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks']= (float) number_format($assignment_obtained_marks, 2);
                        $student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks']= (float) number_format($assignment_assessable_marks, 2);
                    } 
                    else
                    {
                        $student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks']= NULL;
                        $student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks']= NULL;
                    }                                         
                }

                foreach($assessment['exam_details'] as $index => $mark_details)
                {   
                    if(count($mark_details))
                    {
                        $exam_obtained_marks = 0;
                        $exam_assessable_marks = 0;
                        foreach ($mark_details as $p => $d) {
                            $exam_obtained_marks = $exam_obtained_marks + $d->obtained_marks;
                            $exam_assessable_marks = $exam_assessable_marks + $d->full_marks;
                        }   
                        $student_details[$student_id]['assessment_details'][$sub_id]['exam_marks'] = 
                            (float) number_format($exam_obtained_marks, 2);
                        $student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks'] = 
                            (float) number_format($exam_assessable_marks, 2);
                    }     
                    else
                    {
                        $student_details[$student_id]['assessment_details'][$sub_id]['exam_marks'] = 
                            NULL;
                        $student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks'] = NULL;
                            
                    }                                   
                }        

                if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks']) && !is_null($student_details[$student_id]['assessment_details'][$sub_id]['exam_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_obtained_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks'] +
                    $student_details[$student_id]['assessment_details'][$sub_id]['exam_marks'];
                }
                else if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_obtained_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['assignment_marks'];   
                }
                else if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['exam_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_obtained_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['exam_marks'];   
                }
                else
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_obtained_marks'] = NULL;
                }

                if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks']) && !is_null($student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_assessable_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks'] +
                    $student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks'];
                }
                else if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_assessable_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['assignment_assessable_marks'];   
                }
                else if(!is_null($student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks']))
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_assessable_marks'] = 
                    $student_details[$student_id]['assessment_details'][$sub_id]['exam_assessable_marks'];   
                }
                else
                {
                    $student_details[$student_id]['assessment_details'][$sub_id]['total_assessable_marks'] = NULL;
                }

                $student_details[$student_id]['assessment_details'][$sub_id]['grade'] =  $this->getGrade($student_details[$student_id]['assessment_details'][$sub_id]['total_obtained_marks']);                
            }            
        }
        
        $this->saveResultsInDatabase($student_details, $input['session_id']);
        return;
    }



    public function saveResultsInDatabase($student_exam_details, $session_id)
    {                   
        try {
            \DB::beginTransaction();  
            foreach($student_exam_details as $index => $record)
            {                
                foreach($record['assessment_details'] as $subject_id =>$details)
                {
                    $result = Result::updateOrCreate(
                        [
                            'session_id' => $session_id, 
                            'student_id' => $record['student_id'], 
                            'subject_id' => $subject_id
                        ], 
                        [
                            'assignment_marks' => isset($details['assignment_marks']) ? $details['assignment_marks'] : NULL, 
                            'assignment_assessable_marks' => isset($details['assignment_assessable_marks']) ? $details['assignment_assessable_marks'] : NULL,
                            'exam_marks' => isset($details['exam_marks']) ? $details['exam_marks']: NULL,
                            'exam_assessable_marks' => isset($details['exam_assessable_marks']) ? $details['exam_assessable_marks'] : NULL,
                            'total_obtained_marks' => isset($details['total_obtained_marks']) ? $details['total_obtained_marks'] : NULL,
                            'total_assessable_marks' => isset($details['total_assessable_marks']) ? $details['total_assessable_marks'] : NULL,
                            'grade' => $details['grade']
                        ]
                    );
                    \Modules\Result\Entities\ResultDetail::updateOrCreate(
                        [
                            'result_id' => $result->id
                        ], 
                        [
                            'assignment_details' => json_encode($details['assignment_details']), 
                            'exam_details' => json_encode($details['exam_details'])
                        ]

                    );
                }
                
            } 
            \DB::commit();

        }
        catch(\Exception $e) {
            \DB::rollback(); 
            return redirect()->back()->with('error-msg',$e->getMessage());                    
        }
        return;
    }

    public function checkResultPublished($student_id, $session_id)
    {
        return Result::where('session_id', $session_id)
                      ->where('student_id', $student_id)
                      ->get();
    }

    public function deleteResult($session_id, $student_id)
    {
        return $this->where('session_id', $session_id)
                    ->where('student_id', $student_id)
                    ->delete();
    }

    public function getResult($session_id, $student_id)
    {
        $result_detail_table = \Modules\Result\Entities\ResultDetail::getTableName();
        $subject_table = \Modules\Subject\Entities\Subject::getTableName();        
        
        $result = \DB::table($this->table)
                        ->join($result_detail_table, $result_detail_table.'.result_id','=',$this->table.'.id')
                        ->join($subject_table, $subject_table.'.id','=',$this->table.'.subject_id')
                        
                        ->where($this->table.'.session_id', $session_id)
                        ->where($this->table.'.student_id', $student_id)
                        ->select($subject_table.'.subject_name', $this->table.'.assignment_marks', $this->table.'.assignment_assessable_marks', $this->table.'.exam_marks', $this->table.'.exam_assessable_marks', $this->table.'.total_assessable_marks', $this->table.'.total_obtained_marks', $this->table.'.grade', $result_detail_table.'.assignment_details', $result_detail_table.'.exam_details',$subject_table.'.id as subject_id')
                        ->get()
                        ->toArray();
        return $result;
                
    }

    public function getStudentResultInEveryEnrolledYears($academic_session, $student_id)
    {
        $results = [];
        foreach($academic_session as $index => $session)
        {
            $results[$session->id]['session_name'] = $session->session_name;
            $results[$session->id]['session_id'] = $session->id;
            $results[$session->id]['results'] = $this->getResult($session->id, $student_id);
        }

        return $results;
    }
}
