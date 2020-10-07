<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Teacher\Entities\Teacher; 

class UserRole extends Authenticatable
{
    protected $table = "user_roles";

    protected $fillable = ['user_id', 'role_id'];

    public static function createUserRole($user_id, $role_id)
    {
    	return UserRole::create([
    		'user_id' => $user_id, 
    		'role_id' => $role_id
    	]);
    }

    public static function getCurrentUserRole($user_id)
    {
    	$userRole = \DB::table('user_roles')
    					->join('users','users.id','=', 'user_roles.user_id')
    					->join('roles', 'roles.id','=','user_roles.role_id')
    					->select('role_name', 'role_id')    					
    					->where('users.id', $user_id)
    					->where('user_roles.user_id', $user_id)
    					->get(); 
    					
    	return $userRole;

    }

}

