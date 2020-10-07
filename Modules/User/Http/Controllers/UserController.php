<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use Modules\User\Entities\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    protected function guard()
    {
        return auth()->guard('user');
    }

    public function getRegister()
    {
        return view('user::register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required', 
            'address' => 'required', 
            'contact' => 'required', 
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
        ]);


        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->address = $request->address;
        $user->contact = $request->contact;
        $user->api_token = str_random(60);


        if($request->hasFile('photo')) 
        {
            $name = uniqid() . $request->photo->getClientOriginalName();
            $ext = $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path().'/images/user_photos',$name,$ext);
            $user->photo = $name;
        }

        $user->save();
        Session::flash('success-msg', 'Created Successfully');
        return redirect()->back();

    }

    public function getUserLogin()
    {
        return view('user::login');
    }

    public function postUserLogin(Request $request)
    {
        $auth = auth()->guard('user')->attempt(['email' => $request->email, 'password' => $request->password]);

        if($auth) 
        {
            return redirect()->route('user-home')->with('success-msg', 'Successfully Logged in');
        }
        else 
        {
            Session::flash('error-msg','Incorrect Credentials');
            return redirect()->back();
        }

    }

    public function getUserHome()
    {        
        return view('user::dashboard');
    }

    public function getLogout()
    {
        auth()->guard('user')->logout();
        return redirect()->route('user-login')->with('success-msg', 'Logged Out Successfully');
    }

    public function getUserProfile()
    {
        $auth_id = auth()->guard('user')->id();        
        return view('user::user-profile');
    }

    public function postChangePassword(Request $request)
    {        
        $request->validate(User::checkPassword());
        try {
            $input = request()->all();
            (new User)->changePassword($input['user_id'], $input['password']);
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsusccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Password updated successfully. Next time login with the new password');
    }

    public function postChangeProfilePicture(Request $request)
    {
        $request->validate([            
            'photo'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        try
        {
            $input = request()->all();
            $user = new User;
            $image_path = $user->getUserImagePath($input['role_name']);

            if($request->hasFile('photo'))
            {
                $controller = new \App\Http\Controllers\FileController;            
                $file_name = $controller->uploadFile($image_path, $input['photo']);
                $input['photo'] = $file_name; 
                $user->updatePhoto($input['user_id'], $input['photo']);
            }

        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Photo updated Successfully');
    }

    public function postRemoveProfilePicture(Request $request)
    {
        try
        {
            $input = request()->all();            
            $image_path = (new User)->getUserImagePath($input['role_name']);
            $user = (new User)->getCurrentUser($input['user_id']);
            if(!$user)
            {
                Session::flash('error-text', 'Operation Unsuccessful');
                return redirect()->back()->with('error-msg', 'Selected ID is not available');
            }
            if($user->photo)
            {   
                \App\Http\Controllers\FileController::deleteFile($image_path, $user->photo);
                (new User)->updatePhoto($input['user_id'], NULL);
            }

        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Photo removed Successfully');
    }

}
