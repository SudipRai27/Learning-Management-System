<?php

namespace Modules\Classes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\DayAndDateTime;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Subject\Entities\Subject;
use Modules\Classes\Entities\Classes;
use Modules\Teacher\Entities\AssignTeacher;
use Modules\Room\Entities\Room;
use Session;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getListClass(Request $request)
    {
        if($request->has('session_id'))
        {
            $selected_session_id = $request->input('session_id');                  
            if($request->has('subject_id'))
            {
                $selected_subject_id = $request->input('subject_id');
                $class_list =  (new Classes)->getClassList($selected_session_id, $selected_subject_id);       
            }
            else
            {
                $class_list =  (new Classes)->getClassList($selected_session_id); 
            }
        }
        $academic_session = AcademicSession::all();  
        $subjects = Subject::getAllSubjects()->get(); 
        
        return view('classes::list-class')->with('academic_session', $academic_session)
                                          ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : 0)
                                          ->with('class_list', isset($class_list) ? $class_list : '')
                                          ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
                                          ->with('subjects', $subjects);
    }

    public function getCreateClass() 
    {
        $days = ( new DayAndDateTime)->getDays();                
        $academic_session = (new AcademicSession)->getAcademicSession();        
        $subjects = Subject::getAllSubjects()->get();

        return view('classes::create-class')
                ->with('days', $days)                
                ->with('subjects', $subjects)
                ->with('academic_session',$academic_session);
    }

    public function postCreateClass(Request $request)
    {        
        $request->validate(Classes::getValidationRules());
        try{
            $input = request()->all();            
            $classes = new Classes;
            $isRunning = $classes->checkClassesRunning($input['session_id'], $input['room_id'], $input['day_id'], $input['start_time'], $input['end_time'],'create');
            if($isRunning)
            {
                Session::flash('error-text', 'Operation Not Completed');
                return redirect()->back()->with('error-msg', 'Class is already running at the selected time period in the selected room at the selected day')->withInput();
            }
            $classes->createClass($input); 

        }catch(\Exception $e){
            Session::flash('error-text', 'Operation Not Completed');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Completed');
        return redirect()->back()->with('success-msg', 'Class created successfully');
    }

    public function postDeleteClass(Request $request, $id)
    {
        try {
            (new Classes)->deleteClass($id);

        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Not Completed');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Completed');
        return redirect()->back()->with('success-msg', 'Class deleted successfully');
    }

    public function getEditClasses($id)
    {
        $class = (new Classes)->getIndividualClass($id);
        $assigned_teachers = 
        (new AssignTeacher)->getTeachersAssignedFromClassTypeAndSubject($class->session_id, $class->subject_id, $class->type);

        $days = ( new DayAndDateTime)->getDays();        
        $academic_session = (new AcademicSession)->getAcademicSession();
        $subjects = Subject::getAllSubjects()->get();
        if($class->type == "lecture")
        {
            $rooms = Room::getRoomsByType("lecture_room");
        }
        else
        {
            $rooms = Room::getRoomsByType("lab_room");
        }

        return view('classes::edit-class')->with('assigned_teachers', $assigned_teachers)
                                            ->with('class', $class)
                                            ->with('days', $days)
                                            ->with('academic_session', $academic_session)
                                            ->with('subjects', $subjects)
                                            ->with('rooms', $rooms);
    }

    public function postUpdateClass(Request $request, $class_id)
    {
        $request->validate(Classes::getValidationRules());
        try {
            $classes = new Classes;                
            $input = request()->all();
            $isRunning = $classes->checkClassesRunning($input['session_id'], $input['room_id'], $input['day_id'], $input['start_time'], $input['end_time'], 'update', $class_id);
            if($isRunning)
            {
                Session::flash('error-text', 'Operation Not Completed');
                return redirect()->back()->with('error-msg', 'Class is already running at the selected time period in the selected room at the selected day. Please edit or remove that class and try again.')->withInput();
            }
            $classes->updateClass($input, $class_id);
        }catch(\Exception $e){
            Session::flash('error-text', 'Operation Not Completed');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Completed');
        return redirect()->back()->with('success-msg', 'Class updated successfully');

    }
}


