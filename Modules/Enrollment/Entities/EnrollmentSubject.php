<?php

namespace Modules\Enrollment\Entities;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSubject extends Model
{
	protected $table = 'enrollment_subjects'; 

    protected $fillable = ['enrollment_id', 'subject_id'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function createEnrollmentSubjects($input, $enrollment_id)
    {
    	foreach($input['subject_ids'] as $index => $id)
        {
            EnrollmentSubject::create([
                    'enrollment_id' => $enrollment_id, 
                    'subject_id' => $id
                ]);
        }  
        return "success";
    }

    public static function getEnrolledSubjects($input)
    {
        //no need to check course_id and course_type_id as there is only one enrollment record per student per session. Logic has been carried out to do so in EnrollmentController->postCreateEnrollment
        $enrollment_subject_ids = \DB::table('enrollment')
                            ->join('enrollment_subjects', 'enrollment_subjects.enrollment_id','=',
                                'enrollment.id')
                            ->where('student_id', $input['student_id'])
                            ->where('session_id', $input['session_id'])
                            ->select('subject_id')
                            ->get();
                             
        return $enrollment_subject_ids;
    }

    public static function getEnrolledIndividualSubject($input)
    {
        //no need to check course_id and course_type_id as there is only one enrollment record per student per session. Logic has been carried out to do so in EnrollmentController->postCreateEnrollment

        $enrollment_subject = \DB::table('enrollment_subjects')
            ->join('enrollment', 'enrollment.id','=','enrollment_subjects.enrollment_id')
            ->where('enrollment.session_id', $input['session_id'])
            ->where('enrollment.student_id', $input['student_id'])
            ->where('enrollment.id', $input['enrollment_id'])
            ->where('enrollment_subjects.enrollment_id', $input['enrollment_id'])
            ->where('enrollment_subjects.subject_id', $input['subject_id'])
            ->select('enrollment_subjects.*');
            

        return $enrollment_subject;

    }

    public function getEnrolledSubjectsWithName($session_id, $student_id)
    {
        $enrolled_subject_list = \DB::table('enrollment_subjects')
                                    ->join('enrollment','enrollment.id','=','enrollment_subjects.enrollment_id')
                                    ->join('subjects','subjects.id','=','enrollment_subjects.subject_id')
                                    ->where('enrollment.student_id', $student_id)
                                    ->where('enrollment.session_id', $session_id)
                                    ->select('subjects.subject_name', 'subjects.id', 'subjects.is_graded', 'subjects.credit_points', 'description')
                                    ->get(); 

        return $enrolled_subject_list;
    }

    public function getFilteredEnrollmentSubjectRecords($enrollment_records)
    {
        $enrollment_subject_records = [];
        foreach($enrollment_records as $index => $record)                                
        {
            $enrollment_subject_records[$record->student_id]['student_name'] = $record->name;
            $enrollment_subject_records[$record->student_id]['uniqueID'] = $record->uniqueStudentID;
            $enrollment_subject_records[$record->student_id]['student_id'] = $record->student_id;
            $enrollment_subject_records[$record->student_id]['session_name'] = $record->session_name;                                
            $enrollment_subject_records[$record->student_id]['course_title'] = $record->course_title;
            $enrollment_subject_records[$record->student_id]['course_type'] = $record->course_type;
            $enrollment_subject_records[$record->student_id]['session_id'] = $record->session_id; 
            $enrollment_subject_records[$record->student_id]['enrollment_id'] = $record->id;
            $enrollment_subject_records[$record->student_id]['enrolled_subjects'] = 
                            \DB::table('enrollment_subjects')
                            ->join('enrollment', 'enrollment.id','=','enrollment_subjects.enrollment_id')
                            ->join('subjects','subjects.id','=','enrollment_subjects.subject_id')
                            ->join('courses', 'courses.id', '=', 'subjects.course_id')
                            ->join('course_type', 'course_type.id', '=', 'subjects.course_type_id')
                            ->where('enrollment.id', $record->id)
                            ->where('enrollment_subjects.enrollment_id', $record->id)
                            ->select('subject_name','is_graded', 'credit_points','course_type', 'course_title', 'subject_id')
                            ->orderBy('enrollment_subjects.created_at', 'DESC')
                            ->get()
                            ->toJson();
        }

        return $enrollment_subject_records;
    }

    public function getSubjectEnrolledStudents($session_id, $subject_id)
    {
        $enrollment_table = \Modules\Enrollment\Entities\Enrollment::getTableName();
        $student_table = \Modules\Student\Entities\Student::getTableName();
        $users_table = \Modules\User\Entities\User::getTableName();

        $enrolled_students = \DB::table($this->table)
                            ->join($enrollment_table,$enrollment_table.'.id','=', $this->table.'.enrollment_id')
                            ->join($student_table, $student_table.'.id','=',$enrollment_table.'.student_id')
                            ->join($users_table, $users_table.'.id','=',$student_table.'.user_id')
                            ->where($this->table.'.subject_id',$subject_id)
                            ->where($enrollment_table.'.session_id', $session_id)             
                            ->select($users_table.'.name',$student_table.'.student_id as uniqueId',$enrollment_table.'.student_id')
                            ->get()
                            ->toArray();
                            
        return $enrolled_students;
    }

    public function getEnrolledSubjectFromEnrollmentID($enrollment_id)
    {
        $subject_table = \Modules\Subject\Entities\Subject::getTableName();
        return \DB::table($this->table)
                ->join($subject_table, $subject_table.'.id','=',$this->table.'.subject_id') 
                ->where('enrollment_id', $enrollment_id)
                ->select('subject_id', 'subject_name')
                ->get()
                ->toArray();
    }

    public function getEnrolledSubjectsForDashboard($session_id, $student_id)
    {
        $enrolled_subjects = $this->getEnrolledSubjectsWithName($session_id, $student_id);

        return $enrolled_subjects;
    }
}
