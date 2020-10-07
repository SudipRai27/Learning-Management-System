<?php

namespace Modules\Exam\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Subject\Entities\Subject;
use Modules\Exam\Entities\Exam;
use Modules\Exam\Entities\ExamMarks;
use Modules\Enrollment\Entities\EnrollmentSubject;
use Modules\User\Entities\UserRole;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Entities\AssignTeacher;
use Session;
use Auth;

class ExamMarksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public $current_user_role;

    public function __construct() {

        $this->middleware(function ($request, $next) {
            $this->current_user_role = UserRole::getCurrentUserRole(Auth::id());
        return $next($request);
        });       
    }

    public function getExamMarksList(Request $request)
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $subjects = [];
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
     
        } 
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            $subjects = Subject::getAllSubjects()->get(); 
        }

    	if($request->has('session_id') && $request->has('exam_id') && $request->has('subject_id'))
        {

            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');   
            $selected_exam_id = $request->input('exam_id');     
            if($selected_session_id && $selected_subject_id)
            {       
            	$student_exam_marks = (new ExamMarks)->getExamMarks($selected_session_id, $selected_exam_id, $selected_subject_id);             	
            }
            if($selected_exam_id)
            {
            	$exam_full_marks = (new Exam)->getIndividualExamFullMarks($selected_exam_id);

            }
            
        }
    	
    	return view('exam::list-exam-marks')
    		->with('academic_session', $academic_session)
            ->with('subjects', $subjects)
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('selected_exam_id', isset($selected_exam_id) ? $selected_exam_id : '')
            ->with('student_exam_marks', isset($student_exam_marks) ? $student_exam_marks : null)
            ->with('exam_full_marks', isset($exam_full_marks) ? $exam_full_marks : '
            	')
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');

    }

    public function getUploadExamMarks(Request $request)
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $subjects = [];
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
     
        } 
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            $subjects = Subject::getAllSubjects()->get(); 
        }
        
    	if($request->has('session_id') && $request->has('exam_id') && $request->has('subject_id'))
        {

            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');   
            $selected_exam_id = $request->input('exam_id');     
            if($selected_session_id && $selected_subject_id)
            {       
            	$student_exam_marks = (new ExamMarks)->getExamMarks($selected_session_id, $selected_exam_id, $selected_subject_id);             	
            }
            if($selected_exam_id)
            {
            	$exam_full_marks = (new Exam)->getIndividualExamFullMarks($selected_exam_id);

            }
            
        }    	
        
    	return view('exam::upload-exam-marks')
    		->with('academic_session', $academic_session)
            ->with('subjects', $subjects)
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('selected_exam_id', isset($selected_exam_id) ? $selected_exam_id : '')
            ->with('student_exam_marks', isset($student_exam_marks) ? $student_exam_marks : null)
            ->with('exam_full_marks', isset($exam_full_marks) ? $exam_full_marks : '
            	')
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');
    }

    public function postUploadExamMarks(Request $request)
    {
    	$input = request()->all(); 
    	$request->validate(ExamMarks::getValidationRules($input['exam_full_marks']), ExamMarks::getValidationMessages());
    	try {
    		(new ExamMarks)->uploadExamMarks($input);

    	} catch (\Exception $e)
    	{
    		Session::flash('error-text', 'Operation Unsuccessful'); 
    		return redirect()->back()
    						->with('error-msg', $e->getMessage())
    						->withInput(); 
    	}

    	Session::flash('info-text', 'Operation Successful'); 
    	return redirect()->back()->with('success-msg', 'Marks uploaded successfully'); 	
    }
}

