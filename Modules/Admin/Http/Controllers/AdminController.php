<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AdminRequest;
use Modules\Admin\Http\Requests\AdminUpdateRequest;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;
use Modules\Admin\Entities\Admin;
use Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $image_path = 'Modules/Admin/Resources/assets/images/';  

    public function getCreateAdmin()
    {
        return view('admin::create-admin');
    }

    public function getListAdmin() 
    {
        $admins = (new Admin)->getAdminList();        
        return view('admin::list-admin')->with('admins', $admins);

    }

    public function postCreateAdmin(AdminRequest $request)
    {
        $input = $request->validated();
        try{

            \DB::beginTransaction();            
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
            \DB::commit();

            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->route('list-admin')->with('success-msg', 'Admin Created Successfully');                            
        }catch(\Exception $e){

            \DB::rollback();
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
    }

    public function postDeleteAdmin(Request $request, $user_id)
    {
        try{

            \DB::beginTransaction();
            $user = (new User)->getCurrentUser($user_id);                           
            if(!$user)
            {
                Session::flash('error-text', 'Sorry for the inconvenience.');
                return redirect()->back()
                            ->with('error-msg', 'Sorry the data is not available at the moment');
            }            
            if($user->photo)
            {   
                \App\Http\Controllers\FileController::deleteFile($this->image_path, $user->photo);
            }                    
            (new Admin)->deleteAdmin($user_id);
            \DB::commit();
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Deleted Successfully');  

        } catch(\Exception $e) {
            \DB::rollback();
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()       
                             ->with('error-msg', $e->getMessage());
        }
    }

    public function getEditAdmin($user_id)
    {
        $admin = (new Admin)->getAdmin($user_id);
        if(!$admin)
        {
            Session::flash('error-text', 'Sorry for the inconvenience.');
            return redirect()->back()
                            ->with('error-msg', 'Sorry the data is not available at the moment');
        }
        return view('admin::edit-admin')
                    ->with('admin', $admin);
       
    }

    public function postEditAdmin(AdminUpdateRequest $request, $user_id)
    {
        $input = $request->validated();
        try {

            $admin = (new Admin)->getAdmin($user_id);
            if(!$admin)
            {
                return redirect()->back()
                        ->with('error-msg', 'There has been some error. Please try again');
            }                                
            if($request->hasFile('photo'))
            {
                $controller = new \App\Http\Controllers\FileController;
                $controller->deleteFile($this->image_path, $admin->photo); 
                $file_name = $controller->uploadFile($this->image_path, $input['photo']);
                $input['photo'] = $file_name; 
            }
            $user = User::findorFail($user_id); 
            $user->update($input);             
            Session::flash('info-text', 'Hurray operation completed'); 
            return redirect()->back()->with('success-msg', 'Updated Successfully'); 

        }catch(\Exception $e){
            Session::flash('warning-text', 'Sorry, Please check your errors'); 
            return redirect()->back()->with('error-msg', $e->getMessage())
                                     ->withInput();
        }
    }

}
