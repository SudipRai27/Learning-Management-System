<?php

namespace Modules\Enrollment\Entities;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
	protected $table = 'enrollment'; 

    protected $fillable = ['student_id', 'session_id', 'course_id', 'course_type_id'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static function checkCurrentStudentEnrollment($input)
    {
        //no need to check course_id and course_type_id as there is only one enrollment record per student per session. Logic has been carried out to do so.
    	return	\DB::table('enrollment')
    			->join('courses', 'courses.id','=','enrollment.course_id')
    			->join('course_type', 'course_type.id','=','enrollment.course_type_id')
    			->where('student_id', $input['student_id'])
    			->where('session_id', $input['session_id'] )
    			->select('enrollment.*', 'course_title', 'course_type')
    			->first();
    }

    public static function createEnrollment($input)
    {    	
        
    	$enrollment = Enrollment::create([
                'student_id' => $input['student_id'],
                'session_id' => $input['session_id'],
                'course_id' => $input['course_id'], 
                'course_type_id' => $input['course_type_id']
                ]);

    	return $enrollment->id;
    }

    public static function deleteEnrollment($id)
    {
        return Enrollment::where('id', $id)->delete();
                                               
    }


    public function getEnrolledStudentsFromCourseAndCourseType($session_id, $course_type_id, $course_id)
    {
        $student_table = \Modules\Student\Entities\Student::getTableName();
        $users_table = \Modules\User\Entities\User::getTableName();
        $course_type_table = \Modules\Course\Entities\CourseType::getTableName();
        $course_table =\Modules\Course\Entities\Course::getTableName();
        return \DB::table($this->table)
                ->join($student_table, $student_table.'.id','=',$this->table.'.student_id')
                ->join($users_table, $users_table.'.id','=',$student_table.'.user_id')
                ->join($course_type_table,$course_type_table.'.id','=',$this->table.'.course_type_id')
                ->join($course_table, $course_table.'.id','=',$this->table.'.course_id')
                ->where($this->table.'.session_id', $session_id)
                ->where($this->table.'.course_type_id', $course_type_id)
                ->where($this->table.'.course_id', $course_id)
                ->select($this->table.'.*')
                ->select($this->table.'.id as enrollmentId', $this->table.'.student_id', $student_table.'.student_id as uniqueId',$users_table.'.name' )
                ->get()
                ->toArray();

    }

    public function getStudentEnrollmentYears($student_id)
    {
        $academic_session = \Modules\AcademicSession\Entities\AcademicSession::getTableName();
        $enrollment_years = \DB::table($this->table)
                        ->join($academic_session, $academic_session.'.id','=',$this->table.'.session_id')
                        ->where('student_id', $student_id)
                        ->select('session_id as id', 'session_name', 'is_current', $academic_session.'.created_at')
                        ->orderBy($academic_session.'.created_at', 'DESC')
                        ->get()
                        ->toArray();

        return $enrollment_years;
    }

    public function getEnrolledStudentCountBySession()
    {
        $enrollment = [];
        $academic_session = \Modules\AcademicSession\Entities\AcademicSession::all();
        foreach($academic_session as $index => $session)
        {            
            $enrollment[$session->id]['y'] = $session->session_name;
            $enrollment[$session->id]['a'] = \Modules\Enrollment\Entities\Enrollment::where('session_id', $session->id)->count();
        }
        $enrollment = array_values($enrollment);
        $enrollment = json_encode($enrollment);                
        return $enrollment;
        
    }
}
