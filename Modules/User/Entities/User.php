<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Teacher\Entities\Teacher; 

class User extends Authenticatable
 
{
	use Notifiable;
	

    protected $fillable = ['name', 'email', 'password','address', 'phone', 'photo', 'remember_token','api_token', 'emergency_contact_number', 'emergency_contact_name', 'dob'];

    protected $table = "users";

    protected $hidden = ['password', 'remember_token', 'api_token'];	
    
    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public function getCurrentUser($user_id)
    {
    	return $this->where('id', $user_id)
    				->select('id', 'name', 'email', 'address', 'phone', 'photo', 'emergency_contact_name', 'emergency_contact_number', 	'dob', 'created_at')
    				->first();
    }

    public static function checkPassword()
    {
        //Password requires minimum eight characters, at least one letter and one number regex
        return [
            'password' =>
                [
                    'required', 
                    'min:8', 
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', 
               ], 
            'confirm_password' => 'required|same:password'
        ];
    }

    public function changePassword($user_id, $new_password)
    {
        return $this->where('id', $user_id)
             ->update([
                'password' => bcrypt($new_password)
             ]);
    }

    public function getUserImagePath($role_name)
    {   
        if($role_name == "admin")
        {
            $image_path = 'Modules/Admin/Resources/assets/images/';
        }
        else if($role_name == "teacher")
        {
            $image_path = 'Modules/Teacher/Resources/assets/images/';
        }
        else
        {
            $image_path = 'Modules/Student/Resources/assets/images/';
        }
        return $image_path;
    }

    public function updatePhoto($user_id, $photo_name)
    {   
        return $this->where('id', $user_id)
                     ->update([
                            'photo' => $photo_name
                    ]);
    }

    public function checkUserEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}

