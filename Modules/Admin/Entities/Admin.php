<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [];

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static function getAdminList()
 	{
 		$admins = \DB::table('users')
                        ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
                        ->select('name', 'email', 'address', 'phone','photo', 'emergency_contact_number', 'emergency_contact_name', 'dob', 'users.created_at', 'users.id')
                        ->where('role_id', 1)
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->toArray();                        
        return $admins;
 	}

    public function deleteAdmin($user_id)
    {
        return \Modules\User\Entities\User::where('id', $user_id)->delete();
    }

    public function getAdmin($user_id)
    {
        return \DB::table('users')
                        ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
                        ->select('name', 'email', 'address', 'phone','photo', 'emergency_contact_number', 'emergency_contact_name', 'dob', 'users.created_at', 'users.id')
                        ->where('role_id', 1)
                        ->where('users.id',$user_id)
                        ->first();

    }
}
