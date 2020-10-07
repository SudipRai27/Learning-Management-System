<?php

namespace Modules\Enrollment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Enrollment\Entities\Enrollment;
use Modules\Enrollment\Entities\EnrollmentSubject;
use Modules\Course\Entities\CourseType;
use Modules\Student\Entities\Student;
use Modules\User\Entities\UserRole;
use Session;
use Auth;


class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $current_user_role;

    public function __construct() {

        $this->middleware(function ($request, $next) {
            $this->current_user_role = UserRole::getCurrentUserRole(Auth::id());
        return $next($request);
        });       
    }

    public function getCreateEnrollment()
    {
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
        }
        $academic_session = (new AcademicSession)->getAcademicSession();
        return view('enrollment::create-enrollment')
        ->with('academic_session', $academic_session)
        ->with('secondary_student_id', isset($student_id) ? $student_id : '')
        ->with('current_academic_session', isset($current_academic_session) ? $current_academic_session : null);
    }

    public function getListEnrollment(Request $request)
    {
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            $academic_session = (new \Modules\Enrollment\Entities\Enrollment)->getStudentEnrollmentYears($student_id);
        }  
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
        }

        if($request->has('session_id') && $request->has('course_type_id') && $request->has('course_id'))
        {
            
            $enrollment_records = \DB::table('enrollment')                                    
                                    ->join('student', 'student.id','=','enrollment.student_id')
                                    ->join('academic_session', 'academic_session.id','=','enrollment.session_id')
                                    ->join('users','users.id','=','student.user_id')
                                    ->join('courses', 'courses.id','=','enrollment.course_id')
                                    ->join('course_type', 'course_type.id','=','enrollment.course_type_id')
                                    ->where('enrollment.session_id', $request->input('session_id'));

            if($request->input('course_type_id') != 0)
            {
                $enrollment_records->where('enrollment.course_type_id', $request->input('course_type_id'));
            }
            if($request->input('course_id') != 0)
            {
                $enrollment_records->where('enrollment.course_id', $request->input('course_id'));
            }                                                                         
                $enrollment_records = $enrollment_records ->select('enrollment.*', 'users.name', 
                                                'student.student_id as uniqueStudentID', 'academic_session.session_name', 'course_title', 'course_type')
                                    ->orderBy('enrollment.created_at', 'DESC')
                                    ->get();
                            
                                
            $enrollment_subject_records = (new EnrollmentSubject)->getFilteredEnrollmentSubjectRecords($enrollment_records);
            
            
            return view('enrollment::list-enrollment')
            ->with('academic_session', AcademicSession::all())
            ->with('course_type', CourseType::all())
            ->with('enrollment_subject_records', $enrollment_subject_records)
            ->with('selected_session_id', $request->has('session_id') ? $request->input('session_id') : '')
            ->with('selected_course_type_id', $request->has('course_type_id') ? $request->input('course_type_id') : '')
            ->with('selected_course_id', $request->has('course_id') ? $request->input('course_id') : '')
            ->with('secondary_student_id', isset($student_id) ? $student_id : '');;                              
        }
        
        return view('enrollment::list-enrollment')
            ->with('academic_session', $academic_session)
            ->with('course_type', CourseType::all())
            ->with('secondary_student_id', isset($student_id) ? $student_id : '');
    }

    public function postCreateEnrollment(Request $request)
    {
        try{
            \DB::beginTransaction();            
            $input = request()->all();             
            //Make sure that enrollment table has only one enrollment per session for each student
            $hasEnrollment = Enrollment::checkCurrentStudentEnrollment($input);            
            if($hasEnrollment)
            {
                if($hasEnrollment->course_id != $input['course_id'] || $hasEnrollment->course_type_id != $input['course_type_id'])
                {
                    $student = Student::getStudent($input['student_id']);
                    Session::flash('warning-text', 'Please check your errors');              
                    return redirect()->back()->with('error-msg', $student->name . ' has already enrolled subjects for the course type "'. $hasEnrollment->course_type .'"" and course "'. $hasEnrollment->course_title. '" for this session. Please remove the enrollment for this course and try again.');
                }
                $enrollment_id = $hasEnrollment->id;
            }
            else
            {                
                $enrollment_id = Enrollment::createEnrollment($input);                      
            }    
            EnrollmentSubject::createEnrollmentSubjects($input, $enrollment_id);                   
            \DB::commit();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Enrollment Created Successfully');                           
        } catch(Exception $e){
            \DB::rollback(); 
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
    }

    public function postDeleteEnrollment($id)
    {
        try
        {   
            Enrollment::deleteEnrollment($id); 
            Session::flash('info-text', 'Hurray, Operation Completed'); 
            return redirect()->back()->with('success-msg', 'Deleted Successfully')
                                     ->withInput();

        }catch(\Exception $e)
        {
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()->with('error-msg', $e->getMessage())
                                     ->withInput();
        }
    }
}
