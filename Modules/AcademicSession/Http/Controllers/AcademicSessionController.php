<?php

namespace Modules\AcademicSession\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\AcademicSession\Entities\AcademicSessionSettings;
use Session; 

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCreateAcademicSession()
    {
        return view('academicsession::create-academic-session');
    }

    public function getListAcademicSession()
    {

        $academic_session = AcademicSession::orderBy('created_at','DESC')->get();
        return view('academicsession::list-academic-session')->with('academic_session',$academic_session);
    }

    public function postCreateAcademicSession(Request $request)
    {
        $validatedData = $request->validate(AcademicSession::$createRules);
        try {
            \DB::beginTransaction();
            $result = AcademicSession::validateAcademicSession(request()->all());
            if($result['status'] == 'error')
            {            
                return redirect()->back()->withInput()
                                         ->with('error-msg', $result['current_session_name'].' is set to current academic session, Please make changes to that first ! ' );
            }        
            $session = AcademicSession::create(request()->all());
            (new AcademicSessionSettings)->createSessionSettings($session->id);
            \DB::commit();            
        }catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsusccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }        
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->route('list-academic-session')->with('success-msg','Created Successfully');
        
    }

    public function getEditAcademicSession($id)
    {
        $academic_session = AcademicSession::findOrFail($id); 
        return view('academicsession::edit-academic-session')->with('academic_session', $academic_session);
    }

    public function postUpdateAcademicSession(Request $request, $id)
    {
        $request->validate([   
                'session_name' => 'required|unique:academic_session,session_name,'.$id,                            
                'start_date' => 'required|date_format:"Y-m-d', 
                'end_date' => 'required|date_format:Y-m-d|after:start_date', 
                'is_current' => 'required',                
                'end_date' => 'after:start_date', 
        ]);

        $input = request()->all();    
        $result = AcademicSession::validateAcademicSession(request()->all());  
        if($result['status'] == 'error')
        {
            Session::flash('warning-text', 'Sorry, Please check your errors');
            return redirect()->back()->withInput()
                                     ->with('error-msg', $result['current_session_name'].' is set to current academic session, Please make changes to that first ! ' );
        } 

        $academic_session = AcademicSession::findOrFail($id);
        $academic_session->update($input);     
        Session::flash('info-text', 'Hurray operation completed');
        return redirect()->route('list-academic-session')->with('success-msg','Updated Successfully');
    

    }

    public function postDeleteAcademicSession($id)
    {
        $academic_session = AcademicSession::findOrFail($id); 
        if($academic_session->is_current == "yes")
        {
            Session::flash('warning-text', 'Operation failed. Please check msg');
            return redirect()->back()->with('error-msg', $academic_session->session_name . ' is set to current session and cannot be deleted. Please unset it to not current and try again later');
        }
        $academic_session->delete();
        Session::flash('info-text', 'Hurray operation completed');
        return redirect()->route('list-academic-session')->with('success-msg', 'Deleted Successfully');

    }

    public function getAcademicSessionSettings(Request $request)
    {
        if($request->has('session_id'))
        {
            $selected_session_id = $request->input('session_id');
            if($selected_session_id == 0)
            {
                $session_settings = NULL;    
            }
            else
            {
                $session_settings = (new AcademicSessionSettings)->getSessionSettings($selected_session_id);
            }

        }

        $academic_session = (new AcademicSession)->getAcademicSession();
        return view('academicsession::settings')->with('academic_session', $academic_session)
                                                ->with('session_settings', isset($session_settings) ? $session_settings : null)
                                                ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '');
    }

    public function postUpdateAcademicSessionSettings(Request $request)
    {        
        $input = request()->all();
        try {
            (new AcademicSessionSettings)->updateSessionSettings($input);
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsusccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Settings updated Successfully');
    }
}
