<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
    	'event_title', 'description', 'start_time', 'end_time', 'start_date', 'end_date', 'location', 
    	'featured_image', 'event_for'
    ];

    protected $table = 'events';

    public static function getTableName()
    {
    	return with(new Static)->getTable();
    }


    public static function getImageRules($input)
    {
        $files = isset($input['featured_image']) ? $input['featured_image'] : [];           
        if(count($files))
        {                        
            foreach(range(0, count($files)) as $index) 
            {
                $rules['featured_image.' . $index] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            }
        }
        else {
            $rules['featured_image'] = 'required';
        }
        return $rules;
    }

    public function createEvent($input)
    {
    	return $this->create([
    		'event_title' => $input['event_title'],
    		'description' => $input['description'], 
    		'start_time' => $input['start_time'],
    		'end_time' => $input['end_time'],
    		'start_date' => $input['start_date'], 
    		'end_date' => $input['end_date'], 
    		'location' => $input['location'], 
    		'event_for' => json_encode($input['event_for'])
    	]);
    }

    public function getEventList()
    {
        return $this->orderBY('created_at', 'DESC')
                            ->select('id', 'created_at', 'event_title', 'start_date', 'end_date', 'event_for')
                            ->get()
                            ->toArray();   
    }

    public function getEventListFromDate($start_date, $end_date)
    {
        $event_list = $this->whereBetween('start_date', [$start_date, $end_date])
                            ->whereBetween('end_date', [$start_date, $end_date])
                            ->select('id', 'created_at', 'event_title', 'start_date', 'end_date', 'event_for')
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->toArray();
        return $event_list;
    }

    public function getEventsFor($event_for, $date_range)
    {
        $event_list = [];
        $newEventList = [];
        if(strlen($date_range) == 0)
        {
            $event_list = $this->getEventList();
            if($event_for == 'all')
            {
                return $event_list;
            }
            else
            {
                $newEventList = $this->filterEventList($event_list, $event_for);
                return $newEventList;
            }
        }
        
        $date_range = explode('to', $date_range);
        if(isset($date_range[0]) && isset($date_range[1]))
        {
            if(strtotime($date_range[0]) && strtotime($date_range[1]))
            {
                $event_list = $this->getEventListFromDate($date_range[0], $date_range[1]);
                if($event_for == 'all')
                {
                    return $event_list;
                }
                else
                {
                    $newEventList = $this->filterEventList($event_list, $event_for);
                    return $newEventList;
                }
            }
        }
        return []; 
        
    }

    public function filterEventList($event_list, $event_for)
    {       
        $filterEventList = [];
        foreach($event_list as $index => $event)
        {
            $event_for_arr = json_decode($event['event_for']);
            if((isset($event_for_arr[0]) && $event_for_arr[0]  == $event_for) || 
              (isset($event_for_arr[1]) && $event_for_arr[1]  == $event_for))
            {
                $filterEventList[] = $event;
            }   
        }                
        return $filterEventList;
    }

    public function getSingleEvent($event_id)
    {
        $event = $this->findorFail($event_id);
        $event->resources = (new \App\Resource)->getResource($event_id, 'events');
        return $event;
    }

    public function updateEvent($input,$event_id)
    {
        return $this->where('id', $event_id)
                    ->update([
                        'event_title' => $input['event_title'],
                        'description' => $input['description'], 
                        'start_time' => $input['start_time'],
                        'end_time' => $input['end_time'],
                        'start_date' => $input['start_date'], 
                        'end_date' => $input['end_date'], 
                        'location' => $input['location'], 
                        'event_for' => json_encode($input['event_for'])
                    ]);
    }

    public function deleteEvent($event_id)
    {
        return $this->where('id', $event_id)->delete();
    }

    public function getFrontendEvents($event_for)
    {        
        $current_date = Carbon::now()->format('Y-m-d');   
        $current_time = Carbon::now()->format('H:i:A');   
        $events = $this->whereDate('start_date','>', $current_date)
                    ->select('id', 'event_title', 'start_date', 'end_date', 'event_for')
                   ->orWhere(function($query) use ($current_date, $current_time) {
                        return $query->where('start_date','<=',$current_date)
                                     ->where('end_date','>=',$current_date);
                                    
                    })
                    ->orderBY('created_at', 'DESC')                    
                    ->get()
                    ->toArray();
        

        if($event_for == "all")
        {
            return $events;
        }

        $newEventList = $this->filterEventList($events, $event_for);
        return $newEventList;
    }
}
