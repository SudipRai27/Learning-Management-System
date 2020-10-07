<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['class_id', 'student_id', 'week_id', 'remarks'];

    protected $table = 'attendance';

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static function getAttendanceWeeks()
    {
    	return [
    		1 => 'Week 1', 
    		2 => 'Week 2', 
    		3 => 'Week 3', 
    		4 => 'Week 4', 
    		5 => 'Week 5', 
    		6 => 'Week 6', 
    		7 => 'Week 7', 
    		8 => 'Week 8', 
    		9 => 'Week 9', 
    		10 => 'Week 10', 
    		11 => 'Week 11', 
    		12 => 'Week 12'
    	];
    }

    public static function getWeekNameFromWeekID($week_id)
    {
        $weeks = Attendance::getAttendanceWeeks();
        if(array_key_exists($week_id, $weeks))
        {
            return $weeks[$week_id];
        }
        return 'Week not available';
    }

    public static function getValidationRules()
    {
        return [
            'class_id' => 'required', 
            'attendance' => 'required',
            'week_id' => 'required'
        ];
    }

    public static function getcustomValidationMessages()
    {
        return [
            'class_id.required' => 'The class is required', 
            'attendance.required' => 'Please select at least one student whose attendance is to be updated', 
            'week_id.required' => 'Week Id is not specified'
        ];
    }

    public function createOrUpdateAttendance($attendance_to_update,$current_attendance_ids)
    {
        if(count($current_attendance_ids))
        {
            foreach($current_attendance_ids as  $index => $student_id)
            {
                if(!in_array($student_id, $attendance_to_update['student_id']))
                {
                    $this->removeAttendance($attendance_to_update['class_id'], $attendance_to_update['week_id'], $current_attendance_ids[$index]);
                }
            }
        }

        foreach($attendance_to_update['student_id'] as $index => $student_id)
        {
            if($this->checkAttendanceByStudentId($attendance_to_update['class_id'], $attendance_to_update['week_id'], $student_id))
            {
                Attendance::where('class_id', $attendance_to_update['class_id'])
                          ->where('week_id', $attendance_to_update['week_id'])
                          ->where('student_id', $student_id)
                          ->update([
                            'remarks' => $attendance_to_update['remarks'][$index]
                          ]);
            }
            else
            {
                $attendance =Attendance::firstOrNew([
                'student_id' => $student_id,
                'remarks' => $attendance_to_update['remarks'][$index], 
                'week_id' => $attendance_to_update['week_id'], 
                'class_id' => $attendance_to_update['class_id']
                ]);
                $attendance->save();
            }           
        }
        return;
    }

    public function getCurrentAttendanceStudentIds($class_id, $week_id)
    {
        $data = [];
        $student_ids = Attendance::where('class_id', $class_id)
                        ->where('week_id', $week_id)
                        ->select('student_id')
                        ->get()
                        ->toArray();

        foreach($student_ids as $index => $record)
        {
            $data[] = $record['student_id']; 
        }
        return $data;
    }

    public function removeAttendance($class_id, $week_id, $student_id)
    {
        return Attendance::where('class_id', $class_id)
                    ->where('student_id', $student_id)
                    ->where('week_id', $week_id)
                    ->delete();
    }

    public function checkAttendanceByStudentId($class_id, $week_id, $student_id)
    {
        $has_Attendance = Attendance::where('class_id', $class_id)
                                    ->where('student_id', $student_id)
                                    ->where('week_id', $week_id)
                                    ->first();         
        return $has_Attendance;
    }

    public function getStudentAttendance($students, $class_id)
    {
        foreach($students as $index => $student)
        {

            $student_attendance = Attendance::where('class_id', $class_id) 
                                            ->where('student_id', $student->student_id)
                                            ->select('week_id', 'id as attendance_id', 'remarks', 'class_id', 'student_id')
                                            ->get()
                                            ->toArray();

            $attendance =[];
            foreach($student_attendance as $key => $record)
            {
                $attendance[] = $record['week_id'];
            }
            $student->attendance_weeks = $attendance;
            unset($attendance);
        }        

        return $students;
    }
}
