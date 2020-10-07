<?php

namespace Modules\Teacher\Entities;

use Illuminate\Database\Eloquent\Model;

class AssignTeacher extends Model
{
  protected $table = 'assign_teacher';
    
  protected $fillable = ['session_id','subject_id','teacher_id', 'type'];     
 
  public static function getTableName()
  {
    return with(new static)->getTable();
  }


  public static function getValidationRules()
  {
      return [
        'teacher_id' => 'required', 
        'session_id' => 'required', 
        'subject_id' => 'required',         
        'type' => 'required'
      ];
  }

  public static function assignTeacher($session_id, $subject_id, $teacher_id, $type)
  {
      return AssignTeacher::create([
         'session_id' => $session_id, 
         'subject_id' => $subject_id, 
         'teacher_id' => $teacher_id,
         'type' => $type
      ]);
  }
  
  public static function checkIsTeacherAssigned($session_id, $subject_id, $teacher_id, $type)
  {
      return AssignTeacher::where('session_id', $session_id)
                           ->where('subject_id', $subject_id)
                           ->where('teacher_id', $teacher_id)
                           ->where('type',  $type)
                           ->get();
  }

  public static function getTeachersByAssignedType($session_id, $subject_id, $type)
  {
      return \DB::table('assign_teacher')
                        ->join('academic_session', 'academic_session.id','=','assign_teacher.session_id')
                        ->join('subjects','subjects.id','=','assign_teacher.subject_id')
                        ->join('teacher','teacher.id','=','assign_teacher.teacher_id')
                        ->join('users','users.id','=','teacher.user_id')
                        ->where('assign_teacher.session_id',$session_id)
                        ->where('assign_teacher.subject_id', $subject_id)
                        ->where('assign_teacher.type',$type)
                        ->select('users.name', 'users.email','teacher.teacher_id', 'assign_teacher.id','assign_teacher.session_id','assign_teacher.subject_id', 'assign_teacher.teacher_id as currentTeacherID', 'assign_teacher.created_at')
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->toArray(); 

  }

  public static function getAssignedTeachers($session_id, $subject_id)
  {
      $tutor = AssignTeacher::getTeachersByAssignedType($session_id, $subject_id, 'lab');
      $lecturer = AssignTeacher::getTeachersByAssignedType($session_id, $subject_id, 'lecture');      
      $teachers = [];
      $teachers['tutor'] = $tutor;
      $teachers['lecturer'] = $lecturer;                       
      return $teachers;
  }

  public function getAllAssignedTeachers($session_id, $subject_id)
  {
      $teachers = \DB::table('assign_teacher')
                        ->join('academic_session', 'academic_session.id','=','assign_teacher.session_id')
                        ->join('subjects','subjects.id','=','assign_teacher.subject_id')
                        ->join('teacher','teacher.id','=','assign_teacher.teacher_id')
                        ->join('users','users.id','=','teacher.user_id')
                        ->where('assign_teacher.session_id',$session_id)
                        ->where('assign_teacher.subject_id', $subject_id)                        
                        ->select('users.name', 'users.email','teacher.teacher_id', 'teacher.id')    
                        ->distinct('teacher.id')
                        ->get()
                        ->toArray();  
      return $teachers;
  }


  public static function getAssignedTeacherSubjectBySessionAndTeacherId($session_id, $teacher_id)
  {
      $assign_teacher_table = \Modules\Teacher\Entities\AssignTeacher::getTableName();
      $subject_table = \Modules\Subject\Entities\Subject::getTableName(); 
      $courses_table = \Modules\Course\Entities\Course::getTableName();
      $course_type_table = \Modules\Course\Entities\CourseType::getTableName();
      $input = request()->all(); 
      $assigned_teacher = \DB::table($assign_teacher_table)                            
                         ->join($subject_table, $subject_table.'.id','=',$assign_teacher_table.'.subject_id')
                         ->join($course_type_table, $course_type_table.'.id','=',$subject_table.'.course_type_id')
                         ->join($courses_table, $courses_table.'.id','=',$subject_table.'.course_id')           
                         ->where('assign_teacher.session_id',$session_id)
                         ->where('assign_teacher.teacher_id',$teacher_id)
                         ->select('course_type','course_title', $assign_teacher_table.'.type', $assign_teacher_table.'.created_at', $subject_table.'.subject_name', $assign_teacher_table.'.id', $assign_teacher_table.'.subject_id')
                         ->orderBy('created_at', 'DESC')
                         ->get();

      return $assigned_teacher;
  }

  public static function getAssignedTeacherDistinctSubjectBySessionAndTeacherId($session_id, $teacher_id)
  {
    $teacher_subjects = AssignTeacher::getAssignedTeacherSubjectBySessionAndTeacherId($session_id, $teacher_id);
    $subjects = [];
    
    foreach($teacher_subjects as $index => $subject)
    {
      $subjects[$subject->subject_id]['id'] = $subject->subject_id;
      $subjects[$subject->subject_id]['subject_name'] = $subject->subject_name;
      $subjects[$subject->subject_id]['course_type'] = $subject->course_type;
      $subjects[$subject->subject_id]['course_title'] = $subject->course_title;

    }
    return $subjects;
  }

  public function getTeachersAssignedFromClassTypeAndSubject($session_id, $subject_id, $type)
  {
      $data = \DB::table('assign_teacher')
                  ->join('teacher', 'teacher.id','=','assign_teacher.teacher_id')
                  ->join('users', 'users.id', '=','teacher.user_id')
                  ->join('academic_session', 'academic_session.id','=','assign_teacher.session_id')
                  ->select('users.name', 'teacher.id','teacher.teacher_id')
                  ->where('assign_teacher.type', $type)
                  ->where('assign_teacher.session_id', $session_id)
                  ->where('assign_teacher.subject_id', $subject_id)
                  ->get();        

      return $data;      
  }
   
  public function getTeacherAssignedYears($teacher_id)
  {
    $academic_session = \Modules\AcademicSession\Entities\AcademicSession::getTableName();
    $assigned_years = \DB::table($this->table)
                        ->join($academic_session, $academic_session.'.id','=',$this->table.'.session_id'
                          )
                        ->select('session_id as id', 'session_name', 'is_current')
                        ->where($this->table.'.teacher_id', $teacher_id)
                        ->distinct($this->table.'.session_id')
                        ->get()
                        ->toArray();
    
    return $assigned_years;
  }

}
