<?php

namespace Modules\Subject\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\CourseType;
use Modules\Subject\Entities\Subject;
use Illuminate\Pagination\Paginator;
use Modules\Lecture\Entities\Lecture;
use Modules\Assignment\Entities\Assignment;
use Modules\Teacher\Entities\AssignTeacher;
use Modules\User\Entities\UserRole;
use \Modules\AcademicSession\Entities\AcademicSession;
use Session;
use Auth;


class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */    

    public function getListSubject(Request $request)
    {
        
        $input = request()->all();                        
        $course_type = CourseType::pluck('course_type', 'id');
        if(isset($input['course_type_id']) && isset($input['course_id']) && isset($input['page']))
        ///////////// FOR WINDOW LOCATION CHANGE
        {
            
            $subjects = Subject::getSubjectsListFromCourseTypeAndCourse($input['course_type_id'], $input['course_id'])->paginate(10); 

            return view('subject::list-subject')
                    ->with('subjects',$subjects)
                    ->with('course_type', $course_type)
                    ->with('course_type_id', $input['course_type_id'])
                    ->with('course_id',$input['course_id'])
                    ->with('page', $input['page']);
        } 
        else
        {                 
            $page = 1;  
            $subjects = Subject::getAllSubjects()
                    ->paginate(10);   

            return view('subject::list-subject')->with('subjects',$subjects)
                                            ->with('course_type', $course_type)
                                            ->with('page', $page);                                           
        }
    }

    public function getCreateSubject()
    {
        $course_type = CourseType::all();
        return view('subject::create-subject')->with('course_type', $course_type); 
    }

    public function postCreateSubject(Request $request)
    {
        $validatedResult = $request->validate(Subject::$rules);

        if($validatedResult)
        {
            Subject::create(request()->all()); 
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->route('list-subject')->with('success-msg', 'Subject Created Successfully'); 
        }        
        else
        {
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg', 'Some error occured during validation.'); 
        }
    }

    public function postDeleteSubject($id)
    {   
        $subject =Subject::findOrFail($id);
        if(!$subject)
        {
            Session::flash('error-text', 'Sorry, Please check your errors and try again');
            return redirect()->back()->with('error-msg', 'The selected subject is not available'); 
        }
        $subject->delete();
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Deleted Successfully');
    }

    public function getEditSubjects($id)
    {
        $subject = \DB::table('subjects')
                    ->join('courses', 'courses.id','=', 'subjects.course_id')
                    ->join('course_type', 'course_type.id','=', 'subjects.course_type_id')
                    ->select('course_title', 'course_type','subjects.*')
                    ->where('subjects.id', $id)
                    ->first(); 
        if(!$subject)
        {
            return redirect()->route('list-subject')
                            ->with('error-msg', 'Some errors have occured.');
        }

        $course_type = CourseType::all();

        return view('subject::edit-subject')->with('course_type', $course_type)
                                             ->with('subject', $subject);
    }

    public function postUpdateSubject(Request $request, $id)
    {
        $validatedResult = $request->validate(Subject::$rules);
        if($validatedResult)
        {
            $subject = Subject::findOrFail($id);            
            $subject->update(request()->all()); 
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->route('list-subject')->with('success-msg','Updated Successfully'); 

        }
        else
        {
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg', 'Some error occured during validation.'); 
        }
        
    }

    public function getFrontendSubjectDetails($session_id, $subject_id)
    {
        try {
            $lectures = (new Lecture)->getLectureListFromSessionAndSubject($session_id, $subject_id);
            $assignment_list = (new Assignment)->getAssignmentListFromSessionAndSubject($session_id, $subject_id);
            $subject = Subject::where('id', $subject_id)->select('id', 'subject_name', 'description', 'credit_points', 'is_graded')->first();
            $subject->teachers = (new AssignTeacher)->getAllAssignedTeachers($session_id, $subject_id);
            
        } catch(\Exception $e)
        {
            Session::flash('error-text', 'Please check your errors');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        
        return view('subject::frontend-subject-details')
                    ->with('lectures', $lectures)
                    ->with('subject', $subject)
                    ->with('assignment_list', $assignment_list)
                    ->with('session_id', $session_id);

    }

    public function getViewSubjectsAccordingToUserType()
    {        
        return view('subject::assigned-enrolled-subjects');           
    }
    
}
