<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	protected $table = 'student'; 

    protected $fillable = ['student_id', 'user_id','current_course_id', 'current_course_type_id'];
   
    public static $createRules = [

    		'name' => 'required', 
            'email' => 'required|email|unique:users',
            'address' =>'required', 
            'phone' => 'required|numeric|unique:users',             
            'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required|numeric',  
            'dob' => 'required|date_format:"Y-m-d', 
            'course_id' => 'required', 
            'course_type_id' => 'required'

    ];

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

 	public static function getStudent($id)
 	{
 		$student = \DB::table('users')
                        ->join('student', 'student.user_id', '=', 'users.id')                        
                        ->where('student.id', $id)                        
                        ->select('student.student_id', 'users.name','users.email', 
                            'users.dob', 'users.address', 'users.phone','users.photo', 
                            'users.emergency_contact_number', 
                            'users.emergency_contact_name',
                            'student.user_id', 'student.current_course_type_id', 'student.current_course_id', 
                            'student.id', 'student.user_id')
                        ->first();  
        return $student;
 	}   

 	public static function getStudentList()
 	{
 		$students = \DB::table('users')
                        ->join('student', 'student.user_id', '=', 'users.id')
                        ->select('student_id', 'student.id', 'users.name', 'users.email', 'users.phone', 'users.photo', 'users.dob', 'users.address','users.emergency_contact_name', 'users.emergency_contact_number', 'student.current_course_id', 'student.current_course_type_id')
                        ->orderBy('student.created_at', 'DESC')
                        ->get();
                        
        return $students;
 	}

    public function getStudentListFromIds($course_type_id, $course_id)
    {
        return \DB::table('users')
                        ->join('student', 'student.user_id', '=', 'users.id')
                        ->select('student_id', 'student.id', 'users.name', 'users.email', 'users.phone', 'users.photo', 'users.dob', 'users.address','users.emergency_contact_name', 'users.emergency_contact_number', 'student.current_course_id', 'student.current_course_type_id')
                        ->join('courses', 'courses.id', '=', 'student.current_course_id')
                        ->join('course_type', 'course_type.id', '=', 'student.current_course_type_id')
                        ->where('student.current_course_id', $course_id)
                        ->where('student.current_course_type_id', $course_type_id)
                        ->orderBy('student.created_at', 'DESC')
                        ->get();
    }

    public function getEnrolledStudentList($session_id)
    {
        return \DB::table('enrollment')
                    ->join('academic_session', 'academic_session.id','=','enrollment.session_id')
                    ->join('student', 'student.id','=','enrollment.student_id')
                    ->join('users', 'users.id', '=','student.user_id')
                    ->where('enrollment.session_id', $session_id)
                    ->select('users.name', 'student.id as id', 'student.student_id as uniqueID', 'enrollment.id as enrollment_id')
                    ->get();
    }

    public function getStudentId($user_id)
    {
        return Student::where('user_id', $user_id)->select('id')->first()->id;
    }

    public function getStudentDetailsFromStudentId($student_id)
    {
        $user_table = \Modules\User\Entities\User::getTableName();
        $student =  \DB::table($this->table)
                ->join($user_table, $user_table.'.id','=',$this->table.'.user_id')
                ->where($this->table.'.id', '=', $student_id)
                ->select($this->table.'.student_id', $user_table.'.name')
                ->first();
                
        return $student;
    }
}
