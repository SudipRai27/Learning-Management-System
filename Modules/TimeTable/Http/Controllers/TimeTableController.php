<?php

namespace Modules\TimeTable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\AcademicSession\Entities\AcademicSessionSettings;
use Modules\Student\Entities\Student;
use Modules\User\Entities\UserRole;
use Modules\Enrollment\Entities\EnrollmentSubject;
use Modules\Classes\Entities\Classes;
use Modules\Subject\Entities\Subject;
use Modules\TimeTable\Entities\TimeTable;
use Session;
use Auth;


class TimeTableController extends Controller
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


    public function getListTimeTable(Request $request)
    {
        $academic_session = (new AcademicSession)->getAcademicSessionAccordingtoUserRoles($this->current_user_role[0]->role_name);
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            if($request->has('session_id'))
            {                
                $enrolled_subjects = (new EnrollmentSubject)
                ->getEnrolledSubjectsWithName($request->input('session_id'), $student_id);            
                $student_subject_timetable = (new Timetable)->getStudentSubjectTimeTable($enrolled_subjects,$request->input('session_id'), $student_id);
                $selected_session_id = $request->input('session_id');
            }
        }
        else
        {
            if($request->has('session_id') && $request->has('student_id'))
            {
                $enrolled_subjects = (new EnrollmentSubject)
                ->getEnrolledSubjectsWithName($request->input('session_id'), $request->student_id);            
                $student_subject_timetable = (new Timetable)->getStudentSubjectTimeTable($enrolled_subjects,$request->input('session_id'), $request->input('student_id'));
                $selected_session_id = $request->input('session_id');
                $selected_student_id = $request->input('student_id');
            }   
    
        }

        return view('timetable::list-timetable')
        ->with('academic_session', $academic_session)
        ->with('student_subject_timetable',isset($student_subject_timetable) ? $student_subject_timetable : null)
        ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id: null)
        ->with('selected_student_id', isset($selected_student_id) ? $selected_student_id : null);
    }   

    public function getCreateTimeTable(Request $request)
    {       
        $academic_session = (new AcademicSession)->getAcademicSessionAccordingtoUserRoles($this->current_user_role[0]->role_name);

        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
        }
        if($request->has('session_id') && $request->has('student_id'))
        {
            $input = request()->all();           
            $selected_student_id = $request->input('student_id');
            $selected_session_id = $request->input('session_id');

            if($this->current_user_role[0]->role_name == 'student')
            {
                $access = $this->allowTimeTableForSession($selected_session_id, 'can_update_timetable');
            }

            if($input['session_id'] == 0 || $input['student_id'] == 0)
            {                   
                $enrolled_subjects = null;
            }
            else
            {
                $enrolled_subjects = (new EnrollmentSubject)->getEnrolledSubjectsWithName($input['session_id'], $input['student_id']);
            }
        }

        return view('timetable::create-timetable')                
        ->with('selected_student_id', isset($selected_student_id) ? $selected_student_id : '')
        ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
        ->with('enrolled_subjects', isset($enrolled_subjects) ? $enrolled_subjects : null)
        ->with('academic_session', $academic_session)
        ->with('student_id', isset($student_id) ? $student_id : '')
        ->with('access', isset($access) ? $access : '');
    }

    public function allowTimeTableForSession($session_id, $access_type)
    {
        $access = (new AcademicSessionSettings)->checkAccess($session_id, $access_type);
        return $access;
    }
    
    public function getUpdateTimeTable($session_id, $subject_id, $student_id)
    {        
        $lecture_classes = (new Classes)->getClassesFromSessionAndSubject($session_id, $subject_id, 'lecture');
        $lab_classes = (new Classes)->getClassesFromSessionAndSubject($session_id, $subject_id, 'lab');
        $subject = (new Subject)->getIndividualSubject($subject_id);
        return view('timetable::update-timetable')                
                ->with('student_id', $student_id)
                ->with('subject', $subject)
                ->with('session_id', $session_id)
                ->with('lecture_classes', $lecture_classes)
                ->with('lab_classes', $lab_classes);
    }

    public function postUpdateTimeTable(Request $request)
    {
        $request->validate(TimeTable::getValidationRules(), TimeTable::getcustomValidationMessages());
        try {
        //student cannot be in 2 classes of same type such as lecture or lab of same subject in same session
        $input = request()->all();
        $timetable = new TimeTable;

        $subject_student_lecture_timetable = $timetable->getIndividualSubjectClassTimeTable($input['session_id'],  $input['student_id'], $input['subject_id'],'lecture');
        $subject_student_lab_timetable = $timetable->getIndividualSubjectClassTimeTable($input['session_id'],  $input['student_id'], $input['subject_id'], 'lab');

        
        (new TimeTable)->createUpdateTimeTable($input['lecture_class_id'], $input['student_id'], $subject_student_lecture_timetable);
        (new TimeTable)->createUpdateTimeTable($input['lab_class_id'], $input['student_id'], $subject_student_lab_timetable);
                    
        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccesful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Timetable updated successfully');

    }

    public function postDeleteTimeTable($timetable_id)
    {
        try {
            (new Timetable)->deleteTimeTable($timetable_id);
        }catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccesful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Timetable updated successfully');
    }
}
