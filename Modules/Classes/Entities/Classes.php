<?php

namespace Modules\Classes\Entities;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = ['session_id','teacher_id', 'subject_id', 'room_id', 'day_id', 'start_time', 'end_time', 'type'];

    protected $table = 'classes'; 

    public static function getTableName()
    {
    	return with(new Static)->getTable();
    }

    public static function getValidationRules()
    {
    	return [
    		'subject_id' => 'required', 
	    	'room_id' => 'required', 
	        'day_id' => 'required', 
	        'start_time' => 'required',
	        'end_time' => 'required|after:start_time',	        
	        'session_id' => 'required', 
	        'teacher_id' => 'required', 
            'type' => 'required'
    	];
    }

    public function createClass($input)
    {
    	return Classes::create($input);
    }

    public function getClassList($session_id = "", $subject_id="")
    {	

    	$classes_table = $this->table;
    	$session_table = \Modules\AcademicSession\Entities\AcademicSession::getTableName();
    	$subject_table = \Modules\Subject\Entities\Subject::getTableName();
    	$teacher_table = \Modules\Teacher\Entities\Teacher::getTableName();
    	$room_table = \Modules\Room\Entities\Room::getTableName();
    	$users_table = \Modules\User\Entities\User::getTableName();

    	$class_list = \DB::table($classes_table)
    				->join($session_table, $session_table.'.id','=',$classes_table.'.session_id')
    				->join($subject_table, $subject_table.'.id','=',$classes_table.'.subject_id')
    				->join($teacher_table, $teacher_table.'.id','=',$classes_table.'.teacher_id')
    				->join($users_table, $users_table.'.id','=',$teacher_table.'.user_id')
    				->join($room_table, $room_table.'.id','=',$classes_table.'.room_id')
    				->select($classes_table.'.id','session_name','subject_name','users.name as teacher_name','room_code', 'day_id','start_time', 'end_time','type')
    				->where($classes_table.'.session_id', $session_id);
        if($subject_id)
        {
            $class_list->where($classes_table.'.subject_id', $subject_id);
        }

    	$class_list = $class_list->get();        
    	return $class_list;

    }

    public function checkClassesRunning($session_id, $room_id, $day_id, $start_time, $end_time, $action = '' , $class_id = '') 
    {

        $classes =  \DB::table($this->table)
                    ->where('session_id', $session_id)
                    ->where('room_id', $room_id)
                    ->where('day_id', $day_id);

        if($action == 'create')
        {               
            $classes = $classes->get();
        }
        else
        {
            $classes = $classes->where('id','!=',$class_id)
                                ->get();
        }

    	foreach($classes as $index => $class)
    	{
    		if((new \App\DayAndDateTime)
    		->checkTimeRanges($class->start_time, $class->end_time, $start_time, $end_time))
    		{
    			return true;
    		}	
    	}

    	return false;
    }

    public function deleteClass($class_id)
    {
        return Classes::where('id', $class_id)->delete();
    }   

    public function checkTeacherClasses($session_id, $subject_id, $teacher_id, $type) 
    {
        return Classes::where('teacher_id', $teacher_id)
                    ->where('subject_id', $subject_id)
                    ->where('session_id', $session_id)
                    ->where('type', $type)                    
                    ->first();
    }

    public function getIndividualClass($class_id)
    {
        return Classes::where('id', $class_id)->first();
    }

    public function updateClass($input, $class_id)
    {
        return Classes::where('id', $class_id)
                        ->update([
                            'session_id' => $input['session_id'], 
                            'subject_id' => $input['subject_id'], 
                            'teacher_id' => $input['teacher_id'], 
                            'room_id' => $input['room_id'], 
                            'day_id' => $input['day_id'], 
                            'start_time' => $input['start_time'], 
                            'end_time' => $input['end_time'], 
                            'type' => $input['type']
                        ]);
    }

    public function getSubjectClassesFromSubjectList($subjects, $session_id)
    {
        $teacher_table = \Modules\Teacher\Entities\Teacher::getTableName();
        $room_table = \Modules\Room\Entities\Room::getTableName();        
        $users_table = \Modules\User\Entities\User::getTableName();

        $classes = [];
        foreach($subjects as $index => $subject)
        {   
            $subject->classes = 
            \DB::table($this->table)
            ->join($room_table, $room_table.'.id','=',$this->table.'.room_id')
            ->join($teacher_table, $teacher_table.'.id','=',$this->table.'.teacher_id')
            ->join($users_table, $users_table.'.id','=',$teacher_table.'.user_id')
            ->where($this->table.'.session_id',$session_id) 
            ->where($this->table.'.subject_id', $subject->id)           
            ->select($users_table.'.name',$teacher_table.'.teacher_id',$room_table.'.room_code',$this->table.'.day_id',$this->table.'.start_time', $this->table.'.end_time',$this->table.'.type', $this->table.'.id')
            ->get()
            ->toArray();
        }
        
        $subject_classes = $subjects;
        return $subject_classes;
    }

    public function getClassesFromSessionAndSubject($session_id, $subject_id, $type) 
    {
        $teacher_table = \Modules\Teacher\Entities\Teacher::getTableName();
        $room_table = \Modules\Room\Entities\Room::getTableName();        
        $users_table = \Modules\User\Entities\User::getTableName();   

        $classes = \DB::table($this->table)
            ->join($room_table, $room_table.'.id','=',$this->table.'.room_id')
            ->join($teacher_table, $teacher_table.'.id','=',$this->table.'.teacher_id')
            ->join($users_table, $users_table.'.id','=',$teacher_table.'.user_id')
            ->where($this->table.'.session_id',$session_id) 
            ->where($this->table.'.subject_id', $subject_id);

            if($type == "lecture")
            {
                $classes = $classes->where($this->table.'.type', 'lecture');
            }
            if($type == "lab")
            {
                $classes = $classes->where($this->table.'.type', 'lab');
            }

            $classes = $classes->select($users_table.'.name',$teacher_table.'.teacher_id',$room_table.'.room_code',$this->table.'.day_id',$this->table.'.start_time', $this->table.'.end_time',$this->table.'.type', $this->table.'.id as class_id')
            ->get()
            ->toArray();

        return $classes;
    }

    public function getTeacherClasses($session_id, $teacher_id)
    {
        $session_table = \Modules\AcademicSession\Entities\AcademicSession::getTableName();
        $teacher_table = \Modules\Teacher\Entities\Teacher::getTableName();
        $subjects_table = \Modules\Subject\Entities\Subject::getTableName();
        $room_table = \Modules\Room\Entities\Room::getTableName();        

        $teacher_classes = \DB::table($this->table)
                            ->join($session_table, $session_table.'.id','=',$this->table.'.session_id')
                            ->join($subjects_table, $subjects_table.'.id','=',$this->table.'.subject_id')
                            ->join($room_table, $room_table.'.id','=',$this->table.'.room_id')
                            ->where($this->table.'.teacher_id', $teacher_id)
                            ->where($this->table.'.session_id', $session_id)
                            ->select($room_table.'.room_code', $subjects_table.'.subject_name', $this->table.'.day_id',$this->table.'.start_time', $this->table.'.end_time',$this->table.'.type', $this->table.'.id as class_id', $this->table.'.subject_id')
                            ->get();

        return $teacher_classes;
    }

   
}
