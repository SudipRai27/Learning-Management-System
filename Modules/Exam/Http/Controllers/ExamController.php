<?php

namespace Modules\Exam\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Exam\Entities\Exam;
use Modules\Subject\Entities\Subject;
use Modules\User\Entities\UserRole;
use Modules\Teacher\Entities\Teacher;
use Session;
use Auth;

class ExamController extends Controller
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


    public function getExamList(Request $request)
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
        } 
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            
        }

        if($request->has('session_id'))
        {
            $selected_session_id = $request->input('session_id'); 
            if($selected_session_id)
            {
                $exam_list = (new Exam)->getExamsFromSession($selected_session_id);
            }
        }        
        return view('exam::list-exam')
                ->with('academic_session', $academic_session)
                ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
                ->with('exam_list', isset($exam_list) ? $exam_list : null);
    }

    public function getCreateExam()
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
        } 
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            
        }
        return view('exam::create-exam')->with('academic_session', $academic_session);
                                        
    }

    public function postCreateExam(Request $request)
    {
        $request->validate(Exam::getValidationRules(), Exam::getValidationMessages());
        try {
            $input = request()->all();    
            (new Exam)->createExam($input);

        } catch(\Exception $e)
        {
            \DB::rollback();
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();
        }
        
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Exam created successfully');
    }

    public function getEditExam($exam_id)
    {
        try {
            if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
            {                 
                $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());                            
                $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
            }
            else
            {   
                $academic_session = (new AcademicSession)->getAcademicSession();          
                
            }                     
            $exam = (new Exam)->getIndividualExam($exam_id);
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();   
        }
        return view('exam::edit-exam')->with('exam', $exam)
                                      ->with('academic_session', $academic_session);
    }

    public function postUpdateExam(Request $request, $exam_id)
    {   
        $request->validate(Exam::getValidationRules(), Exam::getValidationMessages());
        try {
            (new Exam)->updateExam($exam_id, request()->all());
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();   
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg','Exam updated successfully');
    }

    public function postDeleteExam($exam_id)
    {
        try {
            (new Exam)->deleteExam($exam_id);
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();   
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg','Exam deleted successfully');
    }
}
