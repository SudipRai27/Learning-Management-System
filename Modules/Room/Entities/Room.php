<?php

namespace Modules\Room\Entities;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_code','description','room_type'];

    protected $guarded = ['created_at', 'updated_at']; 

    protected $table = 'room';

    public static $createRules = [
		'room_code' => 'required|numeric|unique:room',
		'description' => 'required', 
		'room_type' => 'required'
    ];

    public static function updateValidationRules($id) {

        return [            
                'room_code' => 'required|numeric|unique:room,room_code,'.$id,
                'description' => 'required', 
                'room_type' => 'required' 
        ];
    }

    public static function getTableName()
    {
    	return with(new static)->getTable();
    }

    public static function getRoomsByType($type)
    {   
    	if($type == "all")
    	{
    		return Room::orderBy('created_at', 'DESC')->get();
    	}
    	elseif($type == "lecture_room")
    	{
    		return Room::where('room_type', 'lecture_room')->orderBy('created_at', 'DESC')->get();
    	}
    	else
    	{
    		return Room::where('room_type', 'lab_room')->orderBy('created_at', 'DESC')->get();
    	}
    }

    public function createRoom($input)
    {
    	return Room::create([
                'room_code' => $input['room_code'], 
                'description' => $input['description'], 
                'room_type' => $input['room_type'] 
            ]);
    }

    public function getRoomById($id)
    {
    	return Room::findorFail($id);
    }

    public function updateRoom($input, $id)
    {
    	return Room::where('id', $id)
                    ->update([
                        'room_code' => $input['room_code'], 
                        'description' => $input['description'], 
                        'room_type' => $input['room_type']   
                    ]);
    }

    public function deleteRoom($id)
    {
        return Room::where('id', $id)->delete(); 
    }

    public function getRoomNameFromId($id)
    {
        try {
            $room = $this->getRoomById($id);

        }catch (\Exception $e)
        {
            return $e->getMessage();
        }
        return $room->room_code;
    }
}
