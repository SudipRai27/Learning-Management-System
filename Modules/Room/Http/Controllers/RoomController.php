<?php

namespace Modules\Room\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Room\Entities\Room;
use Session;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */    

    public function getListRoom() 
    {
        $rooms = (new Room)->getRoomsByType('all');
        return view('room::list-room')->with('rooms', $rooms);
    }

    public function getCreateRoom()
    {
        return view('room::create-room');
    }

    public function postCreateRoom(Request $request)
    {
        $validatedResult = $request->validate(Room::$createRules);  
        try {
            (new Room)->createRoom(request()->all());                
        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Sorry, Please check your errors and try again'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }                                      
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Room Created Successfully');
    }

    public function getEditRoom($id)
    {
        try {
            $room = (new Room)->getRoomById($id);
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Sorry, Selected ID is not available at the moment'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
        return view('room::edit-room')->with('room', $room);
    }

    public function postEditRoom(Request $request, $id)
    {
        $request->validate(Room::updateValidationRules($id));
        try {            

            $input = request()->all();
            (new Room)->updateRoom($input, $id);
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Sorry, Some erros occured while processing this request'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }
        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Room Updated Successfully');
    }

    public function postDeleteRoom($id)
    {
        try
        {
            (new Room)->deleteRoom($id);
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Sorry, Some erros occured while processing this request'); 
            return redirect()->back()->with('error-msg',$e->getMessage())
                                     ->withInput();
        }

        Session::flash('info-text', 'Hurray operation completed'); 
        return redirect()->back()->with('success-msg', 'Room Updated Successfully');
    }
}
