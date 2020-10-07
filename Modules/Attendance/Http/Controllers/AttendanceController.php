<?php

namespace Modules\Attendance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\AcademicSession\Entities\AcademicSessionSettings;
use Modules\Subject\Entities\Subject;
use Modules\Classes\Entities\Classes;
use Modules\TimeTable\Entities\TimeTable;
use Modules\Attendance\Entities\Attendance;
use Modules\User\Entities\UserRole;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Entities\AssignTeacher;
use Modules\Student\Entities\Student;
use Modules\Enrollment\Entities\EnrollmentSubject;
use Session;
use Auth;

class AttendanceController extends Controller
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


    public function getListAttendance(Request $request) 
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            if($request->has('session_id'))
            {
                $selected_session_id = $request->input('session_id');                
                $teacher_classes = (new Classes)->getTeacherClasses($selected_session_id, $teacher_id);
            }
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
        }
        else if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            if($request->has('session_id'))
            {
                $selected_session_id = $request->input('session_id');                
                $enrolled_subjects = (new EnrollmentSubject)
                ->getEnrolledSubjectsWithName($request->input('session_id'), $student_id);            
                $student_classes = (new Timetable)->getStudentSubjectTimeTable($enrolled_subjects,$request->input('session_id'), $student_id);
            }

            $academic_session = (new \Modules\Enrollment\Entities\Enrollment)->getStudentEnrollmentYears($student_id);
        }

        else {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
        }

        if($request->has('session_id') && $request->has('subject_id'))
        {   
            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');
            $class_list = $this->getClassViewData($selected_session_id, $selected_subject_id);
        }
        
        $subjects = Subject::getAllSubjects()->get();
        return view('attendance::list-attendance')
                ->with('academic_session', $academic_session)
                ->with('subjects', $subjects)
                ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
                ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
                ->with('class_list', isset($class_list) ? $class_list : null)                
                ->with('teacher_classes', isset($teacher_classes) ? $teacher_classes : null)
                ->with('student_classes', isset($student_classes) ? $student_classes: null)
                ->with('student_id', isset($student_id) ? $student_id : null);
    }

    public function getCreateAttendance(Request $request)
    {
        $academic_session = (new AcademicSession)->getAcademicSessionAccordingtoUserRoles($this->current_user_role[0]->role_name);

        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {               
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());            
            $selected_session_id = $request->input('session_id');
            if($request->has('session_id'))
            {
                $access = 
                $this->allowAttendanceForSession($selected_session_id, 'can_update_attendance');     
                $teacher_classes = (new Classes)->getTeacherClasses($selected_session_id, $teacher_id); 
            }
        }
        else if($this->current_user_role[0]->role_name == 'admin')
        {
            $subjects = Subject::getAllSubjects()->get();
            if($request->has('session_id') && $request->has('subject_id'))
            {   
                $selected_session_id = $request->input('session_id');
                $selected_subject_id = $request->input('subject_id');
                $class_list = $this->getClassViewData($selected_session_id, $selected_subject_id);
            }

        }
        else
        {
            return redirect()->route('user-home')->with('error-msg', 'Cant access the page that you were trying');
        }
        
        $attendance_weeks = (new Attendance)->getAttendanceWeeks();
        return view('attendance::create-attendance')
        ->with('current_academic_session', isset($current_academic_session) ? $current_academic_session : null)
        ->with('subjects', isset($subjects) ? $subjects: null)
        ->with('class_list', isset($class_list) ? $class_list : null)
        ->with('selected_subject_id',  isset($selected_subject_id) ? $selected_subject_id : null)
        ->with('selected_session_id',  isset($selected_session_id) ? $selected_session_id : null)        
        ->with('attendance_weeks', $attendance_weeks)        
        ->with('teacher_classes', isset($teacher_classes) ? $teacher_classes : null)
        ->with('academic_session', $academic_session)
        ->with('teacher_id', isset($teacher_id) ? $teacher_id : '')
        ->with('access', isset($access) ? $access : '');
    }

    public function allowAttendanceForSession($session_id, $access_type)
    {
        $access = (new AcademicSessionSettings)->checkAccess($session_id, $access_type);
        return $access;
    }

    public function getClassViewData($session_id, $subject_id)
    {        
        $class_list = (new Classes)->getClassesFromSessionAndSubject($session_id, $subject_id, 'all'); 
        return $class_list; 
    }

    public function getUpdateAttendance($session_id, $subject_id, $class_id, $week_id)
    {   
        $students = (new TimeTable)->getStudentsTimeTableFromSubjectSessionAndClass($session_id, $subject_id, $class_id);
        $subject_name = (new Subject)->getSubjectNameFromId($subject_id);
        $week_name = Attendance::getWeekNameFromWeekID($week_id);
        return view('attendance::update-attendance')->with('students', $students)
                                        ->with('class_id', $class_id)
                                        ->with('week_id', $week_id)
                                        ->with('subject_name', $subject_name)
                                        ->with('week_name', $week_name);
    }

    public function postUpdateAttendance(Request $request, $class_id)
    {
        $request->validate(Attendance::getValidationRules(), Attendance::getcustomValidationMessages());
        try{
            $input = request()->all();
            $attendance_to_update = $this->getAttendanceToUpdateRecords($input, $class_id);
            $attendance = new Attendance;
            $current_attendance_ids = $attendance->getCurrentAttendanceStudentIds( $input['class_id'], $input['week_id']);
            $attendance->createOrUpdateAttendance($attendance_to_update, $current_attendance_ids);

        } catch(\Exception $e)
        {
            Session::flash('error-text', 'Please check the error-msg'); 
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successfull'); 
        return redirect()->back()->with('success-msg', 'Attendance Updated Successfull');
        
    }

    public function getAttendanceToUpdateRecords($input, $class_id)
    {
        $attendance_to_update = [];
        foreach($input['remarks'] as $index => $remark)
        {
            if(array_key_exists($index, $input['attendance']))
            {
                $attendance_to_update['student_id'][] = $index;
                $attendance_to_update['remarks'][] = $remark;
                $attendance_to_update['week_id'] = $input['week_id'];
                $attendance_to_update['class_id'] = $input['class_id'];
            }
        }

        return $attendance_to_update;
    }

    public function getViewAttendance($session_id, $subject_id, $class_id)
    {
        $students = (new TimeTable)->getStudentsTimeTableFromSubjectSessionAndClass($session_id, $subject_id, $class_id);
        $student_attendance = (new Attendance)->getStudentAttendance($students, $class_id);
        $attendance_weeks = Attendance::getAttendanceWeeks();
        $subject_name = (new Subject)->getSubjectNameFromId($subject_id);
        $session_name = (new AcademicSession)->getSessionNameFromID($session_id);
        $class = (new Classes)->getIndividualClass($class_id);
        return view('attendance::view-attendance')
                    ->with('attendance_weeks', $attendance_weeks)
                    ->with('student_attendance', $student_attendance)
                    ->with('subject_name', $subject_name)
                    ->with('session_name', $session_name)
                    ->with('class', $class);
    }
}
