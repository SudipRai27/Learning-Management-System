<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\CourseType;
use Modules\Course\Entities\Course;
use Session;
use Modules\Student\Entities\Student;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCreateCourse()
    {
        $course_type = CourseType::all();
        return view('course::course.create-course')->with('course_type', $course_type); 
    }

    public function postCreateCourse(Request $request)
    {
        $validatedResult = $request->validate(Course::$rules);
        if($validatedResult)
        {
            Course::create(request()->all()); 
        }
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Created Successfully'); 
    }
    public function getListCourse()
    {
        $course = \DB::table('courses')
                    ->join('course_type','course_type.id','=','courses.course_type_id')
                    ->select('course_type.course_type','courses.*')
                    ->orderBy('created_at','DESC')
                    ->get();
        return view('course::course.list-course')->with('course', $course);
    }

    public function getEditCourse($id)
    {
        $course = Course::findOrFail($id);                    
        $course_type = CourseType::all();
        return view('course::course.edit-course')->with('course', $course)
                                                ->with('course_type', $course_type);
    }

    public function postUpdateCourse(Request $request, $id)
    {
        $validatedResult = $request->validate(Course::$rules);
        $course = Course::findOrFail($id);                    
        $course->update(request()->all());
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->route('list-course')->with('success-msg', 'Updated Successfully'); 
    }

    public function postDeleteCourse($id)
    {
        $course = Course::findOrFail($id); 
        if(!$course)
        {
            Session::flash('warning-text', 'Please check the error-msg'); 
            return redirect()->back()
                             ->with('errror-msg', 'Selected Course is not available'); 
        }

        $students = Student::where('current_course_id', $id)
                            ->get(); 
        if(count($students))
        {
            Session::flash('warning-text', 'Please check the error-msg'); 
            return redirect()->back()->with('error-msg', 'There are students who are currently registered in this course type. Please delete those students and try again.'); 
        }

        $course->delete(); 
        Session::flash('warning-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Deleted Successfully'); 
    }
}
