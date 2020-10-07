<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\User\Entities\User;
use Modules\User\Entities\PasswordReset;
use App\Mail\PasswordResetMail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{

    public function getForgotPassword()
    {
        return view('user::mail.forgot-password');
    }

    public function sendEmailLink(Request $request)
    {   
        $request->validate([
            'email' => 'required|email'
        ]);

        try 
        {
            $email = $request->input('email');
            $user = (new User)->checkUserEmail($email);
            if(!$user)
            {
                return redirect()->back()->with('error-msg', 'The email you entered does not exist.');
            }
            $password_reset = (new PasswordReset)->createPasswordResetToken($email);
            $status = $this->sendResetEmail($user->email, $user->name, $password_reset->token);
            if($status != 'success')
            {
                return redirect()->back()->with('error-msg', $status);
            }

        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error-msg', $e->getMessage());
        }   
        return redirect()->back()->with('success-msg', 'An email verification link has been sent to our account.');
    }

    public function sendResetEmail($email, $name, $token)
    {
        $link = url('/') .'/account/password/reset/'.$token. '?email='.$email;        
        $data = [
            'link' => $link, 
            'name' => $name, 
            'token' => $token
        ];

        try {            
            Mail::to($email)->queue(new PasswordResetMail($data));
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
        return "success";
    }

    public function getResetPasword(Request $request, $token)
    {
        if(!$request->has('email'))
        {
            return view('user::mail.mail-error-message')->with('error', 'Invalid Request');
            
        }
        $email = $request->input('email');        
        $password_reset = (new PasswordReset)->checkValidPasswordResetCredentials($email, $token);
        if(!$password_reset)
        {
            return view('user::mail.mail-error-message')
                    ->with('error', 'Password link request you provided is invalid');
            
        }
        $current_time = Carbon::now();
        $timediff = $current_time->diffInMinutes($password_reset->updated_at);                
        if($timediff > 59)
        {
            (new PasswordReset)->deletePasswordReset($email);
            return view('user::mail.mail-error-message')
                    ->with('error', 'Token has expired. Please request a new password forgot link');            
        }
    
        return view('user::mail.password-reset-form')->with('email', $email);
    }

    public function postResetPassword(Request $request)
    {
        $request->validate(PasswordReset::resetPasswordRules());
        $input = request()->all();
        try {
            $user = (new User)->checkUserEmail($input['email']);
            if($user)
            {
                (new User)->changePassword($user->id, $input['password']);
                (new PasswordReset)->deletePasswordReset($input['email']);
            }

        } catch (\Exception $e)
        {
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        return redirect()->route('user-login')->with('success-msg', 'Password Changed Successfully. Please login to proceed further.');
    }
}
