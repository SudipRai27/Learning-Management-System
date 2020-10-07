<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Teacher\Entities\Teacher;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Course\Entities\CourseType;
use Modules\Subject\Entities\Subject;
use Modules\Teacher\Entities\AssignTeacher;
use Modules\Room\Entities\Room;
use Modules\Classes\Entities\Classes;
use Session; 


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    
    private $image_path = 'Modules/Teacher/Resources/assets/images/';    

    public function getListTeachers()
    {
        $teachers = Teacher::getTeacherList();
        return view('teacher::list-teacher')->with('teachers', $teachers); 
    }

    public function getCreateTeacher()
    {   
        return view('teacher::create-teacher');
    }

    public function postCreateTeacher(Request $request)
    {
        $validatedResult = $request->validate(Teacher::$createRules);
        if($validatedResult)
        {
            \DB::beginTransaction();

        try{            
            $config = \Config::get('constants.options');            
            $teacher_id = $config['PREFIX_IN_TEACHER_ID'] . str_pad(rand(0, pow(10, $config['DIGITS_IN_TEACHER_ID'])-1), $config['DIGITS_IN_TEACHER_ID'], '0', STR_PAD_LEFT);
            
            $input = request()->all();                          
            $input['password'] = bcrypt($input['dob']);                        
            if($request->hasFile('photo')) 
            {
                $file_upload_controller = new \App\Http\Controllers\FileController;
                $file_name =  $file_upload_controller->uploadFile($this->image_path, $input['photo']);
                $input['photo'] = $file_name;
            }   
            
            $user = User::create($input);
            $teacher = new Teacher;
            $teacher->teacher_id = $teacher_id;
            $teacher->user_id = $user->id;
            $teacher->save();
            if(isset($input['teacher_role']))
            {   
                foreach($input['teacher_role'] as $index => $id)
                {
                    UserRole::createUserRole($user->id, $id);
                }
            }
            \DB::commit();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->route('list-teacher')->with('success-msg', 'Teacher Created Successfully');                              

            }catch(\Exception $e){

            \DB::rollback();
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
            }
        }
    }
    
    public function getViewTeacher($id)
    {
        $teacher = Teacher::getTeacher($id);
        if(!count($teacher))
        {
            Session::flash('warning-text', 'Sorry for the inconvenience'); 
            return redirect()->back()
                            ->with('error-msg', 'Some errors have occured. Selected ID is not available');  
        }
        return view('teacher::view-teacher')->with('teacher', $teacher); 
    }

    public function getEditTeacher($id)
    {
        $teacher = Teacher::getTeacher($id);
        if(!$teacher)
        {
            Session::flash('warning-text', 'Sorry for the inconvenience'); 
            return redirect()->back()
                            ->with('error-msg', 'Some errors have occured. Selected ID is not available');  
        }
        $teacher_role_ids = Teacher::getTeacherRoleIds($teacher->id);
        return view('teacher::edit-teacher')->with('teacher', $teacher)
                                            ->with('teacher_role_ids', $teacher_role_ids); 
    }

    

    public function postUdpateTeacher(Request $request, $id)
    {
        $request->validate([

            'name' => 'required', 
            'email' => 'required|unique:users,email,'.$id,            
            'address' =>'required', 
            'phone' => 'required|numeric|unique:users,phone,'.$id,             
            'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required|numeric',  
            'dob' => 'required|date_format:"Y-m-d',  
            'teacher_role' => 'required'

        ]);

        try {

            $teacher = Teacher::getTeacher($id);
            if(!count($teacher))
            {
                return redirect()->back()
                        ->with('error-msg', 'There has been some error. Please try again');
            }            
            $input = request()->all();         
            if($request->hasFile('photo'))
            {
                $controller = new \App\Http\Controllers\FileController;
                $controller->deleteFile($this->image_path, $teacher->photo); 
                $file_name = $controller->uploadFile($this->image_path, $input['photo']);
                $input['photo'] = $file_name; 
            }

            $user = User::findorFail($id); 
            $user->update($input); 
            $teacher_role_ids = Teacher::getTeacherRoleIds($teacher->id);
            $this->updateTeacherRoleIds($input['teacher_role'] , $teacher_role_ids, $teacher->id);
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Updated Successfully'); 

        }catch(\Exception $e){
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()->with('error-msg', $e->getMessage())
                                     ->withInput();
        }
    }

    public function updateTeacherRoleIds($input_teacher_role_ids, $current_teacher_role_ids, $teacher_id)
    {
        foreach($input_teacher_role_ids as $index => $role_id)
        {
            if(!in_array($role_id, $current_teacher_role_ids))
            {
                UserRole::createUserRole($teacher_id, $role_id);
                
            }            

        }
        foreach($current_teacher_role_ids as $index => $role_id)
        {
            if(!in_array($role_id, $input_teacher_role_ids))
            {                
                UserRole::where('user_id', $teacher_id)
                        ->where('role_id', $role_id)
                        ->delete(); 
            }            
        }    
    }

    public function postDeleteTeacher($id)
    {           
        try{
            $teacher = Teacher::where('user_id', $id)->first(); 
            $user = User::where('id', $id)->first();
            if($user->photo)
            {   
                \App\Http\Controllers\FileController::deleteFile($this->image_path, $user->photo);
            }

            $teacher->delete(); 
            $user->delete(); 
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Deleted Successfully');  

        } catch(\Exception $e) {
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()       
                             ->with('error-msg', $e->getMessage());

        }
    }

    public function getAssignTeacher(Request $request)
    {        
        if($request->has('session_id') && $request->has('course_type_id') && $request->has('course_id'))
        {
            $selected_course_type_id = $request->input('course_type_id');
            $selected_course_id = $request->input('course_id'); 
            $selected_session_id = $request->input('session_id');
            $subjects_list = 
            Subject::getSubjectsListFromCourseTypeAndCourse($selected_course_type_id, $selected_course_id)->get();
            $teacher = new Teacher;
            $lecturer = $teacher->getTeacherByType('lecturer');
            $tutor = $teacher->getTeacherByType('tutor');         
        }
        $academic_session = (new AcademicSession)->getAcademicSession();
        return view('teacher::assign-teacher')
            ->with('academic_session', $academic_session)
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id: '')
            ->with('course_type', CourseType::all())
            ->with('selected_course_id', isset($selected_course_id) ? $selected_course_id : 0)
            ->with('selected_course_type_id', isset($selected_course_type_id) ? $selected_course_type_id : 0)
            ->with('course_id', isset($course_id) ? $course_id : 0)
            ->with('subjects_list', isset($subjects_list) ? $subjects_list : '')
            ->with('lecturer', isset($lecturer) ? $lecturer : [])
            ->with('tutor', isset($tutor) ? $tutor: []);
    }


    public function getListAssignedTeacher(Request $request)
    {
        if($request->has('session_id') && $request->has('subject_id'))
        {
            $selected_session_id = $request->input('session_id');             
            $selected_subject_id = $request->input('subject_id'); 
            $assigned_teacher_list = AssignTeacher::getAssignedTeachers($selected_session_id, $selected_subject_id);
            $teacher = new Teacher;
            $lecturer = $teacher->getTeacherByType('lecturer');
            $tutor = $teacher->getTeacherByType('tutor');         
        }
        $subjects = Subject::getAllSubjects()->get();
        $academic_session = (new AcademicSession)->getAcademicSession();
        return view('teacher::list-assigned-teacher')
            ->with('academic_session', $academic_session)
            
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : 0)
            ->with('subjects', $subjects)
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : 0)
            ->with('assigned_teacher_list', isset($assigned_teacher_list) ? $assigned_teacher_list : null)
            ->with('lecturer', isset($lecturer) ? $lecturer : [])
            ->with('tutor', isset($tutor) ? $tutor : []);
    }

    public function postDeleteAssignedTeacher($id)
    {
        $assigned_teacher = AssignTeacher::findorFail($id);
        $teacher_classes = (new Classes)->checkTeacherClasses($assigned_teacher->session_id, $assigned_teacher->subject_id, $assigned_teacher->teacher_id, $assigned_teacher->type);
        //if assigned teacher has classes then donot allow to delete the assigned teacher
        if($teacher_classes)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', 'The assigned teacher you are about to delete has '. $teacher_classes->class_type .' classes for this subject. Please delete those classes and try to remove the assigned teacher again.');
        }        
        if(!$assigned_teacher)
        {
            Session::flash('error-text', 'Please check your errors');
            return redirect()->back()->with('error-msg', 'Selected ID is not available');
        }
        $assigned_teacher->delete();    
        Session::flash('info-text', 'Operation completed');
        return redirect()->back()->with('success-msg', 'Deleted successfully');

    }

    public function postUpdateAssignedTeacher(Request $request)
    {        
        $input = request()->all();
        if(AssignTeacher::where('id', $input['assignedId'])
                        ->update([
                            'teacher_id' => $input['teacher_id']
                        ]))
        {
            Session::flash('info-text', 'Operation completed');
            return redirect()->back()->with('success-msg', 'Updated successfully');   
        }
        
        Session::flash('error-text', 'Please check your errors');
        return redirect()->back()->with('error-msg', 'Some problems has occured while processing this request');
        
    }

    public function getAssignTeacherAndRoom($subject_id)
    {
        $subject = (new Subject)->getIndividualSubject($subject_id);
        $current_academic_session = AcademicSession::getCurrentSession();        
        $days = (new \App\DayAndDateTime)->getDays();         
        return view('teacher::assign-teacher-and-room')->with('subject', $subject)  
                                                       ->with('current_academic_session', $current_academic_session)    
                                                       ->with('days', $days);
    }
}
