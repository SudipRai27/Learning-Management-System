<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Student;
use DB;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;
use Session;
use Modules\Course\Entities\CourseType;
use Modules\Course\Entities\Course; 


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $image_path = 'Modules/Student/Resources/assets/images/';    

    public function getListStudent()
    {
        $students = Student::getStudentList();                       
        return view('student::list-student')->with('students', $students)
                                            ->with('course_type', CourseType::all())
                                            ->with('selected_course_id', 0)
                                            ->with('selected_course_type_id', 0);
    }

    public function getCreateStudent()
    {
        $course_type = CourseType::select('id', 'course_type')->get();         
        return view('student::create-student')->with('course_type', $course_type);
    }

    public function postCreateStudent(Request $request)
    {
        
        $validatedResult = $request->validate(Student::$createRules);  
        if($validatedResult)
        {   
            \DB::beginTransaction();

        try{

            $config = \Config::get('constants.options');
            $student_id = $config['PREFIX_IN_STUDENT_ID'] . str_pad(rand(0, pow(10, $config['DIGITS_IN_STUDENT_ID'])-1), $config['DIGITS_IN_STUDENT_ID'], '0', STR_PAD_LEFT);
            
            $input = request()->all();                        
            $input['password'] = bcrypt($input['dob']);                        
                      
            if($request->hasFile('photo')) 
            {

                $file_upload_controller = new \App\Http\Controllers\FileController;
                $file_name =  $file_upload_controller->uploadFile($this->image_path, $input['photo']);
                $input['photo'] = $file_name;
                
            }   
       
            $user = User::create($input);
            UserRole::createUserRole($user->id, $input['role_id']);
            $student = new Student;
            $student->student_id = $student_id;        
            $student->user_id = $user->id;      
            $student->current_course_id = $input['course_id'];
            $student->current_course_type_id = $input['course_type_id'];
            $student->save();
            

            \DB::commit();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->route('list-student')->with('success-msg', 'Student Created Successfully');                              

            }catch(\Exception $e){

            \DB::rollback();
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
            }
        }     
    
    }

    public function getEditStudent($id)
    {
        $student = Student::getStudent($id);         
        if(!$student)
        {
            Session::flash('error-text', 'Sorry for the inconvenience.');
            return redirect()->back()
                            ->with('error-msg', 'Sorry the data is not available at the moment');
        }
        $course_type = CourseType::select('id', 'course_type')->get();  
        $courses = Course::where('course_type_id', $student->current_course_type_id)->get();         
        return view('student::edit-student')->with('student', $student)
                                            ->with('course_type', $course_type)
                                            ->with('courses', $courses);

    }

    public function postUpdateStudent(Request $request, $id, $user_id)
    {   
        $request->validate([

            'name' => 'required', 
            'email' => 'required|unique:users,email,'.$user_id,            
            'address' =>'required', 
            'phone' => 'required|numeric|unique:users,phone,'.$user_id,             
            'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required|numeric',  
            'dob' => 'required|date_format:"Y-m-d', 
            'course_id' => 'required', 
            'course_type_id' => 'required'

        ]);
        
        try {
            
            \DB::beginTransaction();

            $student = Student::getStudent($id);            
            if(!$student)
            {
                Session::flash('error-text', 'Sorry for the inconvenience.');
                return redirect()->back()
                            ->with('error-msg', 'Sorry the data is not available at the moment');
            }            
            $input = request()->all();         
            if($request->hasFile('photo'))
            {
                $controller = new \App\Http\Controllers\FileController;
                $controller->deleteFile($this->image_path, $student->photo); 
                $file_name = $controller->uploadFile($this->image_path, $input['photo']);
                $input['photo'] = $file_name; 
            }

            $user = User::findorFail($user_id); 
            $user->update($input);  
            Student::where('id', $id)
                    ->update([                        
                        'current_course_id' => $input['course_id'],
                        'current_course_type_id' => $input['course_type_id']

                    ]);            

            \DB::commit();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Updated Successfully'); 

        }catch(\Exception $e){
            \DB::rollback();
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()->with('error-msg', $e->getMessage())
                                     ->withInput();
        }
        

    }

    public function postDeleteStudent($id)
    {
        try{
            \DB::beginTransaction();
            $student = Student::getStudent($id);   
            $user = \DB::table('users')->join('student', 'student.user_id', '=', 'users.id')
                                       ->where('student.id', $id)
                                       ->select('users.photo', 'users.id')
                                       ->first();                                          
            if(!$student && !$user)
            {
                Session::flash('error-text', 'Sorry for the inconvenience.');
                return redirect()->back()
                            ->with('error-msg', 'Sorry the data is not available at the moment');
            }            
            if($user->photo)
            {   
                \App\Http\Controllers\FileController::deleteFile($this->image_path, $user->photo);
            }
            \DB::commit();
            Student::where('id', $id)->delete();     
            User::where('id', $user->id)->delete();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Deleted Successfully');  

        } catch(\Exception $e) {
            \DB::rollback();
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()       
                             ->with('error-msg', $e->getMessage());

        }
        
    }

    public function getViewStudent($id)
    {
        $student = Student::getStudent($id); 
        if(!$student)
        {
            Session::flash('warning-text', 'Sorry for the inconvenience'); 
            return redirect()->route('list-student')
                            ->with('error-msg', 'Some errors have occured. Selected ID is not available'); 
        }       
        
        return view('student::view-student')->with('student', $student);
    }
}
