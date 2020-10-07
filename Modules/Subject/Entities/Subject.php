<?php

namespace Modules\Subject\Entities;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['subject_name', 'description', 
							'is_graded', 
							'credit_points', 
							'course_id', 
							'course_type_id', 
							'full_marks', 
							'pass_marks' 
							];

	protected $table = 'subjects';  


	public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    
	public static $rules = [
			'subject_name' => 'required', 
			'is_graded' => 'required', 
			'credit_points' => 'required|integer', 
			'course_id' => 'required|integer', 
			'course_type_id' => 'required|integer', 
			'full_marks' => 'required|integer|between:1,100', 
			'pass_marks' => 'required|integer|between:1,100'

	];

	public static function getSubjectsListFromCourseTypeAndCourse($course_type_id,$course_id)
	{	
		$subjects = \DB::table('subjects')
                    ->join('courses', 'courses.id','=', 'subjects.course_id')
                    ->join('course_type', 'course_type.id','=', 'subjects.course_type_id')
                    ->select('course_title', 'course_type','subjects.*')
                    ->orderBy('created_at', 'DESC')
                    ->where('subjects.course_type_id', $course_type_id)
                    ->where('subjects.course_id', $course_id); 

        return $subjects;
                    
	}

	public static function getAllSubjects()
	{
		return \DB::table('subjects')
                    ->join('courses', 'courses.id','=', 'subjects.course_id')
                    ->join('course_type', 'course_type.id','=', 'subjects.course_type_id')
                    ->select('course_title', 'course_type','subjects.*')
                    ->orderBy('created_at', 'DESC');
	}

	public static function getSubjectsByGradedType($graded)
	{
		return \DB::table('subjects')
                    ->join('courses', 'courses.id','=', 'subjects.course_id')
                    ->join('course_type', 'course_type.id','=', 'subjects.course_type_id')
                    ->select('course_title', 'course_type','subjects.*')
                    ->where('subjects.is_graded', $graded)
                    ->orderBy('created_at', 'DESC')
                    ->get();
	}

	public function getIndividualSubject($id)
	{
		return Subject::findOrFail($id);
	}	

	public function getSubjectNameFromId($id)
	{
		try{
			$subject = $this->getIndividualSubject($id);
		}
		catch (\Exception $e)
		{
			return $e->getMessage();
		}
		return $subject->subject_name;
	}

	public function getSubjectFromUserRole($role, $session_id='')
	{
		
		if($role[0]->role_name == "lecturer" || $role[0]->role_name == "tutor")
		{
			$teacher_id = (new \Modules\Teacher\Entities\Teacher)->getTeacherIdFromUserId(\Auth::id());
            $assign_teacher = new \Modules\Teacher\Entities\AssignTeacher;
            $subjects = $assign_teacher->getAssignedTeacherDistinctSubjectBySessionAndTeacherId($session_id, $teacher_id);
		}
		elseif($role[0]->role_name == "admin")
		{

			$subjects = Subject::getAllSubjects()->get(); 
			$subject_array = [];
			foreach ($subjects as $index => $subject)
			{	
				$subject_array[$subject->id]['id'] = $subject->id;
			    $subject_array[$subject->id]['subject_name'] = $subject->subject_name;
			    $subject_array[$subject->id]['course_type'] = $subject->course_type;
			    $subject_array[$subject->id]['course_title'] = $subject->course_title;

			}
			$subjects = $subject_array;
		}
		else
		{
			$subjects = [];
		}
		
		
		return $subjects;
	}
	
}
