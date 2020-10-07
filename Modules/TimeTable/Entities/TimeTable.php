<?php

namespace Modules\TimeTable\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TimeTable extends Model
{
    protected $fillable = ['class_id', 'student_id'];

    protected $table = 'timetable';

    public static function getValidationRules() 
    {
    	return [
    		'student_id' => 'required', 
    		'lecture_class_id' => 'required',
    		'lab_class_id' => 'required'
    	];
    }

    public static function getcustomValidationMessages()
    {
    	return [
    		'lecture_class_id.required' => 'The radio button cannot be empty. Please select at least one lecture class', 
    		'lab_class_id.required' => 'The radio button cannot be empty. Please select at lseast one lab class'
    	];
    }	

    public function createUpdateTimeTable($class_id, $student_id, $current_timetable)
    {
        if($current_timetable)
        {
            if($class_id == $current_timetable->class_id && $student_id == $current_timetable->student_id)
            {
                return;
            }
            return TimeTable::where('id', $current_timetable->id)->update([
                    'class_id' =>  $class_id, 
                    'student_id' => $student_id
            ]);
        }
        else
        {
            $timetable = TimeTable::firstorNew([
                    'class_id' => $class_id, 
                    'student_id' => $student_id
                    ]);
            return $timetable->save();
        }
        
    }

    public function checkStudentTimeTable($class_id, $student_id)
    {
    	$has_timetable = TimeTable::where('class_id',$class_id)
    					  ->where('student_id', $student_id)
    					  ->first();
    	if($has_timetable)
    	{
    		return true;
    	}
    	return false;
    }

    public function getIndividualSubjectClassTimeTable($session_id,  $student_id, $subject_id, $type)
    {
        $classes_table = \Modules\Classes\Entities\Classes::getTableName();
        $student_timetable = \DB::table($this->table)
                                    ->join($classes_table, $classes_table.'.id','=',$this->table.'.class_id')
                                    ->where($this->table.'.student_id', $student_id)  
                                    ->where($classes_table.'.session_id', $session_id)
                                    ->where($classes_table.'.subject_id', $subject_id)
                                    ->where($classes_table.'.type', $type)
                                    ->select($this->table.'.*', $classes_table.'.subject_id', $classes_table.'.type')
                                    ->first();
        return $student_timetable;
    }

    public function getStudentSubjectTimeTable($subjects, $session_id, $student_id)
    {
        $student_classes = [];
        $classes_table = \Modules\Classes\Entities\Classes::getTableName();        
        foreach($subjects as $index => $subject)
        {
            $student_classes[] = \DB::table($this->table)
                                        ->join($classes_table, $classes_table.'.id','=',$this->table.'.class_id')
                                        ->where($this->table.'.student_id',$student_id)
                                        ->where($classes_table.'.session_id', $session_id)
                                        ->where($classes_table.'.subject_id', $subject->id)
                                        ->select($classes_table.'.*', $this->table.'.id as timetable_id')
                                        ->orderby('created_at', 'DESC')
                                        ->get()
                                        ->toArray();
        }
        
        return $student_classes;
    }

    public function getStudentsTimeTableFromSubjectSessionAndClass($session_id, $subject_id, $class_id)
    {   
        $current_user_role = \Modules\User\Entities\UserRole::getCurrentUserRole(Auth::id());
        $classes_table = \Modules\Classes\Entities\Classes::getTableName();        
        $student_table = \Modules\Student\Entities\Student::getTableName();        
        $users_table = \Modules\User\Entities\User::getTableName();       

        $students = \DB::table($this->table) 
                    ->join($classes_table,$classes_table.'.id', '=', $this->table.'.class_id')
                    ->join($student_table, $student_table.'.id','=',$this->table.'.student_id')
                    ->join($users_table, $users_table.'.id', '=',$student_table.'.user_id')
                    ->where($classes_table.'.session_id', $session_id)
                    ->where($classes_table.'.subject_id', $subject_id)
                    ->where($this->table.'.class_id', $class_id);
                   
        if($current_user_role[0]->role_name == "student")
        {            
            $student_id = (new \Modules\Student\Entities\Student)->getStudentId(Auth::id());           
            $students = $students->where($this->table.'.student_id', $student_id);
        }
            $students = $students->select($student_table.'.student_id as uniqueID', $student_table.'.id as student_id', $users_table.'.name', $users_table.'.email')
                    ->get()
                    ->toArray(); 

        return $students;

    }

    public function deleteTimeTable($timetable_id)
    {
        return TimeTable::where('id',$timetable_id)->delete();
    }
}
