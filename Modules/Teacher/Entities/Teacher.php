<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User; 
use Modules\User\Entities\UserRole; 

class Teacher extends Model
{
   protected $table = 'teacher';
    
   protected $fillable = ['teacher_id', 'user_id'];

   public static $createRules = [
            'name' => 'required', 
            'email' => 'required|email|unique:users',
            'address' =>'required', 
            'phone' => 'required|numeric|unique:users',             
            'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required|numeric',  
            'dob' => 'required|date_format:"Y-m-d', 
            'teacher_role' => 'required',
   ];
    
   public static function getTableName()
   {
      return with(new Static)->getTable();
   }
   
   public static function getTeacherList()
 	{
 		$teachers_list = \DB::table('users')
                        ->join('teacher', 'teacher.user_id', '=', 'users.id')
                        ->select('teacher_id', 'users.*')
                        ->orderBy('created_at', 'DESC')
                        ->get();
      $teachers = [];
      foreach($teachers_list as $index => $data)
      {
         $teachers[$data->id]['name'] = $data->name; 
         $teachers[$data->id]['id'] = $data->id; 
         $teachers[$data->id]['teacher_id'] = $data->teacher_id;
         $teachers[$data->id]['email'] = $data->email;
         $teachers[$data->id]['address'] = $data->address;
         $teachers[$data->id]['phone'] = $data->phone; 
         $teachers[$data->id]['photo'] = $data->photo; 
         $teachers[$data->id]['emergency_contact_number'] = $data->emergency_contact_number;
         $teachers[$data->id]['emergency_contact_name'] = $data->emergency_contact_name;
         $teachers[$data->id]['dob'] = $data->dob;
         $teachers[$data->id]['role'] = \DB::table('user_roles')
                                          ->join('users','users.id','=','user_roles.user_id')
                                          ->join('roles','roles.id','=','user_roles.role_id')
                                          ->select('role_name')
                                          ->where('user_roles.user_id',$data->id)
                                          ->get()
                                          ->toJson();

      }               
      return $teachers;
   }
     
   public static function getTeacher($id)
 	{
 		$teacher = \DB::table('users')
                        ->join('teacher', 'teacher.user_id', '=', 'users.id')
                        ->where('users.id', $id)
                        ->where('teacher.user_id', $id)
                        ->select('teacher.teacher_id', 'users.*','teacher.user_id')
                        ->first();  
      return $teacher;
 	}   


   public static function getTeacherRoleIds($id)
   {
      $teacher_role = UserRole::getCurrentUserRole($id);
      foreach($teacher_role as $index => $role)
      {
         $teacher_role_ids[] = $role->role_id; 
      }    
      return $teacher_role_ids;
   }

   public static function getSearchTeacher($search)
   {
      $teachers = \DB::table('users')
                     ->join('teacher', 'teacher.user_id', '=', 'users.id')
                     ->select('teacher_id', 'users.name', 'teacher.id')
                     ->where('name','LIKE',"%$search%")
                     ->orWhere('teacher_id','LIKE',"%$search%")
                     ->get();

      return $teachers;

   }

   public static function getSearchTeachersFromId($search, $role_id)
   {
      $data = \DB::table('user_roles')
                        ->join('roles','roles.id','=','user_roles.role_id')
                        ->join('users','users.id','=','user_roles.user_id')
                        ->join('teacher','teacher.user_id','=','users.id')
                        ->where('user_roles.role_id',$role_id)
                        ->where(function($query) use ($search){
                            return $query->where('name','LIKE',"%$search%")
                                         ->orWhere('teacher_id','LIKE',"%$search%");
                        })
                        ->select('teacher_id', 'users.name', 'teacher.id')
                        ->get();                               
      return $data;
   }

   public function getTeacherNameFromId($id)
   {
      try {
         $teacher =  \DB::table('users')
                        ->join('teacher', 'teacher.user_id', '=', 'users.id')
                        ->where('teacher.id', $id)
                        ->select('teacher.teacher_id', 'users.name')
                        ->first();
      }
      catch (\Exception $e)
      {
         return $e->getMessage();
      }
        
      return $teacher->name;
   }

   public function getTeacherIdFromUserId($user_id)
   {
      try {
         $teacher = Teacher::where('user_id', $user_id)->first(); 
      } catch (\Exception $e)
      {
         return redirect()->back()->with('error-msg', $e->getMessage());
      }
      return $teacher->id;
   }

   public function getTeacherByType($type)
   {
      $data = \DB::table('user_roles')
               ->join('roles','roles.id','=','user_roles.role_id')
               ->join('users','users.id','=','user_roles.user_id')
               ->join('teacher','teacher.user_id','=','users.id')
               ->select('teacher_id', 'users.name', 'teacher.id');
      if($type == "lecturer")
      {
         $data = $data->where('user_roles.role_id', 2);
      }
      else
      {
         $data = $data->where('user_roles.role_id', 4);
      }

      return $data->get();
     
   }
   
}
