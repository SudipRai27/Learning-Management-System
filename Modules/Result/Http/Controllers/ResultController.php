<?php

namespace Modules\Result\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\CourseType;
use Modules\Enrollment\Entities\Enrollment;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Result\Entities\Result;
use Modules\User\Entities\UserRole;
use Auth;
use Session;

class ResultController extends Controller
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


    public function getListResult(Request $request) {
        if($request->has('session_id') && $request->has('course_type_id') && $request->has('course_id'))
        {
            $selected_session_id = $request->input('session_id'); 
            $selected_course_type_id = $request->input('course_type_id'); 
            $selected_course_id = $request->input('course_id');
            $enrolled_students = (new Enrollment)->getEnrolledStudentsFromCourseAndCourseType($selected_session_id, $selected_course_type_id, $selected_course_id);
        }

        $academic_session = (new AcademicSession)->getAcademicSession();
        $course_type = CourseType::select('id', 'course_type')->get();
        return view('result::list-result')
                ->with('academic_session', $academic_session)
                ->with('course_type', $course_type)
                ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
                ->with('selected_course_type_id', isset($selected_course_type_id) ? $selected_course_type_id : '')
                ->with('selected_course_id', isset($selected_course_id) ? $selected_course_id : '')
                ->with('enrolled_students', isset($enrolled_students) ? $enrolled_students : null);
        
    }


    public function getGenerateResult(Request $request)
    {
        if($request->has('session_id') && $request->has('course_type_id') && $request->has('course_id'))
        {
            $selected_session_id = $request->input('session_id'); 
            $selected_course_type_id = $request->input('course_type_id'); 
            $selected_course_id = $request->input('course_id');
            $enrolled_students = (new Enrollment)->getEnrolledStudentsFromCourseAndCourseType($selected_session_id, $selected_course_type_id, $selected_course_id);
        }

        $academic_session = (new AcademicSession)->getAcademicSession();
        $course_type = CourseType::select('id', 'course_type')->get();
        return view('result::generate-result')
                ->with('academic_session', $academic_session)
                ->with('course_type', $course_type)
                ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
                ->with('selected_course_type_id', isset($selected_course_type_id) ? $selected_course_type_id : '')
                ->with('selected_course_id', isset($selected_course_id) ? $selected_course_id : '')
                ->with('enrolled_students', isset($enrolled_students) ? $enrolled_students : null);
    }

    public function postGenerateResults(Request $request)
    {   
        try{                    
            $input = request()->all();
            (new Result)->generateResult($input);
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Results published successfully');
        }
        catch(Exception $e){            
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
        
    }

    public function postDeleteResult(Request $request)
    {
        try{                    
            $input = request()->all();
            (new Result)->deleteResult($input['session_id'], $input['student_id']);            
        }
        catch(Exception $e){            
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Result deleted successfully');
    }

    public function getViewResult($session_id, $student_id)
    {
        try{                    
            $input = request()->all();
            $result = (new Result)->getResult($session_id, $student_id);
            
        }
        catch(Exception $e){            
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
        return view('result::view-result')
                ->with('result', $result)
                ->with('student_id', $student_id);

    }

    public function getViewStudentGrades()
    {
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new \Modules\Student\Entities\Student)->getStudentId(Auth::id());            
            $academic_session = (new \Modules\Enrollment\Entities\Enrollment)->getStudentEnrollmentYears($student_id);
            $student_result = (new Result)->getStudentResultInEveryEnrolledYears($academic_session, $student_id);            
        }
        else
        {
            Session::flash('error-text', 'Please check your errors.');
            return redirect()->back()->with('error-msg', 'Only students are able to view grades');
        }
        return view('result::view-grades')->with('student_result', $student_result);
    }   
}
