<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\CourseType;
use Session;
use Modules\Student\Entities\Student;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCreateCourseType()
    {
        return view('course::course-type.create-course-type');
    }

    public function postCreateCourseType(Request $request)
    {        
        $validatedResult = $request->validate(CourseType::$rules);
        if($validatedResult)
        {
            CourseType::create(request()->all());
        }
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Created Successfully');
    }

    public function getListCourseType()
    {
        $course_type = CourseType::orderBy('created_at','DESC')->get();
        return view('course::course-type.list-course-type')->with('course_type', $course_type);
    }

    public function getEditCourseType($id)
    {
        $course_type = CourseType::findorFail($id); 
        return view('course::course-type.edit-course-type')->with('course_type', $course_type);
    }

    public function postUpdateCourseType(Request $request, $id)
    {
        $validatedResult = $request->validate(CourseType::$rules); 
        if($validatedResult)
        {   
            $course_type = CourseType::findOrFail($id); 
            $course_type->update(request()->all()); 
        }
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->route('list-course-type')
                         ->with('success-msg', 'Course type updated successfully');
    }

    public function postCourseTypeDelete($id)
    {
        $course_type = CourseType::findorFail($id); 
        if(!$course_type)
        {
            Session::flash('warning-text', 'Please check the error-msg'); 
            return redirect()->back()
                             ->with('errror-msg', 'Selected Course Type is not available'); 
        }

        $students = Student::where('current_course_type_id', $id)
                            ->get(); 
        if(count($students))
        {
            Session::flash('warning-text', 'Please check the error-msg'); 
            return redirect()->back()->with('error-msg', 'There are students who are currently registered in this course type. Please delete those students and try again.'); 
        }

        $course_type->delete(); 
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Deleted Successfully'); 
    }
}
