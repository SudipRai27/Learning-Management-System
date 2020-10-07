<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['email', 'token'];

    protected $table = 'password_resets';

    public static function resetPasswordRules()
    {
        $rules = [
        'email'    => 'required|email',
        'password' => [
            'required',
            'string',
            'min:8',             // must be at least 8 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit            
            ],
        'confirm_password' => 'required|same:password'
        ];

        return $rules;
    }

    public function createPasswordResetToken($email)
    {
    	return $this->updateOrCreate(
    		['email' => $email ], 
    		['token' => str_random(60)]
    	);
    }

    public function checkValidPasswordResetCredentials($email, $token)
    {	
    	return $this->where('email', $email)
    				->where('token', $token)
    				->first();
    }


    public function deletePasswordReset($email)
    {
    	return $this->where('email', $email)->delete();
    }

}
