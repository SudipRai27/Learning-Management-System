<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Event\Http\Requests\EventRequest;
use Modules\Event\Entities\Event;
use Modules\User\Entities\UserRole;
use Auth;
use App\Resource;
use Session;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getListEvent()
    {        
        return view('event::list-event');
    }

    public function getCreateEvent()
    {
        return view('event::create-event');
    }

    public function postCreateEvent(EventRequest $request)
    {        
        $input = $request->validated();
        try {
            \DB::beginTransaction();
            $event = (new Event)->createEvent($input);            
            if(isset($input['featured_image']))
            {
                $event_folder = 'events/';
                (new Resource)->uploadResources($input['featured_image'], $event_folder, $event->id, 'events');                     
            }
            \DB::commit();

        } catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()
                ->route('list-event')
                ->with('success-msg', 'Event created succesfully');
    }

    public function getSearchEventList(Request $request)
    {   
        $input = request()->all();
        try {
            $event_list = (new Event)->getEventsFor($input['event_for'], $input['date_range']);           
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Some errors occured while processing this request');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        return view('event::searched-event-list')->with('event_list', $event_list)
                                                 ->with('event_for', $input['event_for'])
                                                 ->with('date_range', $input['date_range']);
    }

    public function getViewEvent($view_type, $event_id)
    {   
        try     
        {
            $event =(new Event)->getSingleEvent($event_id);   
            
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Some errors occured');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        if($view_type == "backend")
        {
            return view('event::view-event')->with('event', $event);
        }

        return view('event::view-frontend-event')->with('event', $event);
    }

    public function getEditEvent($event_id)
    {
        try     
        {
            $event =(new Event)->getSingleEvent($event_id);   
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Some errors occured');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }        
        return view('event::edit-event')->with('event', $event);
    }

    public function postDeleteEvent($event_id) 
    {
        try {
            \DB::beginTransaction();
            $event_class = new Event;
            $event = $event_class->getSingleEvent($event_id);           
            $event_class->deleteEvent($event_id);
            if(count($event->resources) > 0)
            {
                $event_folder = 'events/';
                foreach($event->resources as $index => $resource)
                {
                    (\App\Http\Controllers\FileController::removeFileFromS3($event_folder, $resource));
                    (new Resource)->deleteResource($resource['resource_id']);
                }
            }
            \DB::commit();

        }catch(\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Event deleted successfully');
    }

    public function postEditEvent(EventRequest $request, $event_id)
    {
        $input = $request->validated();
        try {
            (new Event)->updateEvent($input, $event_id);                        

        } catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()
                ->back()
                ->with('success-msg', 'Event updated succesfully');
    }

    public function getManageEventImages($event_id)
    {
        try     
        {
            $resources = (new \App\Resource)->getResource($event_id, 'events');
        }
        catch(\Exception $e)
        {
            Session::flash('error-text', 'Some errors occured');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        return view('event::manage-event-images')
                    ->with('event_id', $event_id)
                    ->with('resources', $resources);
    }

    public function postRemoveEventImage($resource_id)
    {
        try {
            $resource = new Resource;
            $file = $resource->getSingleResource($resource_id);
            $parent_table_details = $resource->getResourceTableParentDetails('events', $resource_id);
            $event_folder = 'events/';
            (\App\Http\Controllers\FileController::removeFileFromS3($event_folder, $file));
            $resource->deleteResource($resource_id);

        }catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Image removed successfully');
    }

    public function postUploadEventImage(Request $request)
    {
        $input = request()->all();
        $request->validate(Event::getImageRules($input));
        try {
            $event_folder = 'events/';                
            (new Resource)->uploadResources($input['featured_image'], $event_folder, $input['event_id'], 'events');            
        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Image uploaded successfully');
    }


}
