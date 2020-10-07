<?php

namespace Modules\AcademicSession\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;

class AcademicSession extends Model
{
    protected $fillable = ['session_name', 'is_current', 'start_date', 'end_date'];

    protected $table  = 'academic_session';

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static $createRules = [
            'session_name' => 'required|unique:academic_session',    		
    		'start_date' => 'required|date_format:"Y-m-d', 
    		'end_date' => 'required|date_format:Y-m-d|after:start_date', 
            'is_current' => 'required',
            'end_date' => 'after:start_date',             
    ];

    public static function validateAcademicSession($data) 
    {
        
        $result = array();
        $result['status'] = 'success';
        $current_session = AcademicSession::where('is_current', 'yes')->first();
        if($data['is_current'] == 'yes')
        {           
            if((bool) $current_session)
            {
                if(!isset($data['id']) || $current_session->id != $data['id'] )
                {
                    $result['status'] = 'error';
                    $result['current_session_name']  = $current_session->session_name;
                    $result['current_session_id'] = $current_session->id;
                }
            }
            
        }

        return $result;
        
    }

    public static function getCurrentSession()
    {    
        return AcademicSession::where('is_current', 'yes')->first();   
    }


    public function getSessionNameFromID($session_id)
    {
        try{

            $session = AcademicSession::where('id', $session_id)->first();    
            return $session->session_name;
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getAcademicSession()
    {
        return AcademicSession::orderBy('created_at', 'DESC')->get();
    }

    public function getIndividualAcademicSession($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getAcademicSessionAccordingtoUserRoles($role_name)
    {
        if($role_name == 'student')
        {
            $student_id = (new \Modules\Student\Entities\Student)->getStudentId(Auth::id());
            $academic_session = (new \Modules\Enrollment\Entities\Enrollment)->getStudentEnrollmentYears($student_id);
        }  
        elseif($role_name == 'lecturer' || $role_name == 'tutor')
        {
            $teacher_id = (new \Modules\Teacher\Entities\Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);    
        }
        else
        {
            $academic_session = $this->getAcademicSession();
        }
        return $academic_session;
    }
}
