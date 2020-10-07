<?php

namespace Modules\AcademicSession\Entities;

use Illuminate\Database\Eloquent\Model;

class AcademicSessionSettings extends Model
{
    protected $fillable = ['session_id', 'can_enroll', 'can_update_timetable', 'can_update_attendance'];

    protected $table  = 'academic_session_settings';

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public function createSessionSettings($session_id)
    {
        return $this->create([
            'session_id' => $session_id, 
            'can_enroll' => 'no', 
            'can_update_timetable' => 'no', 
            'can_update_attendance' => 'no'
        ]);
    }

    public function getSessionSettings($session_id)
    {
        return $this->where('session_id', $session_id)->first();
    }

    public function updateSessionSettings($input)
    {
        return $this->where('session_id', $input['session_id'])
                    ->update([
                        'can_enroll' => $input['can_enroll'], 
                        'can_update_timetable' => $input['can_update_timetable'], 
                        'can_update_attendance' => $input['can_update_attendance']
                    ]);
    }

    public function checkAccess($session_id, $access_type)
    {
        $access =  $this->where('session_id', $session_id)
                    ->select($access_type)
                    ->first();
        if($access)
        {
            return $access->$access_type;
        }
        return 'error';

    }
        
}
