<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Subject\Entities\Subject;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentMarks;
use Modules\User\Entities\UserRole;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Entities\AssignTeacher;
use Auth;
use Session;

class AssignmentMarksController extends Controller
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


    public function getListAssignmentMarks(Request $request)
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

        if($request->has('session_id') && $request->has('subject_id') && $request->has('assignment_id'))
        {

            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');   
            $selected_assignment_id = $request->input('assignment_id');     
            if($selected_session_id && $selected_subject_id && $selected_assignment_id)
            {       
                $student_assignment_marks = (new AssignmentMarks)->getAssignmentMarks($selected_session_id, $selected_subject_id, $selected_assignment_id);
                $assignment_full_marks = (new Assignment)->getIndividualAssignmentOnly($selected_assignment_id)->marks;                 
            }
        }    

        return view('assignment::list-assignment-marks')
            ->with('subjects', isset($subjects) ? $subjects : [])
            ->with('academic_session', isset($academic_session) ? $academic_session : [])
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('selected_assignment_id', isset($selected_assignment_id) ? $selected_assignment_id : '')
            ->with('student_assignment_marks', isset($student_assignment_marks) ? $student_assignment_marks : null)
            ->with('assignment_full_marks', isset($assignment_full_marks) ? $assignment_full_marks : '
                ')
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');        
        
    }

    public function getUploadAssignmentMarks(Request $request)
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
                
        if($request->has('session_id') && $request->has('subject_id') && $request->has('assignment_id'))
        {

            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');   
            $selected_assignment_id = $request->input('assignment_id');     
            if($selected_session_id && $selected_subject_id && $selected_assignment_id)
            {       
                $student_assignment_marks = (new AssignmentMarks)->getAssignmentMarks($selected_session_id, $selected_subject_id, $selected_assignment_id);
                $assignment_full_marks = (new Assignment)->getIndividualAssignmentOnly($selected_assignment_id)->marks;                 
            }            
            
        }
        
        return view('assignment::upload-assignment-marks')
            ->with('subjects', isset($subjects) ? $subjects : [])
            ->with('academic_session', isset($academic_session) ? $academic_session : [])
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('selected_assignment_id', isset($selected_assignment_id) ? $selected_assignment_id : '')
            ->with('student_assignment_marks', isset($student_assignment_marks) ? $student_assignment_marks : null)
            ->with('assignment_full_marks', isset($assignment_full_marks) ? $assignment_full_marks : '
                ')
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : ''); 
    }

    public function postUploadAssignmentMarks(Request $request)
    {        
        $input = request()->all(); 

        $request->validate(AssignmentMarks::getValidationRules($input['assignment_full_marks']), AssignmentMarks::getValidationMessages());
        try {
            (new AssignmentMarks)->uploadAssignmentMarks($input);

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

