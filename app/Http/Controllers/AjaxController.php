<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getCoursesFromCourseType()
    {
    	    	    	
    	$input = request()->all();
                
    	$course = \DB::table('courses')
    				->where('course_type_id', $input['course_type_id'])
    				->pluck('course_title', 'id');
    				 

    	$select = '';
    	$select .= '<option value="0"> Select  </option>';

    	foreach($course as $index => $d)
    	{
    		if($index == $input['selected_course_id'])
    		{
    			$select.= '<option value = '.$index.' selected>'.$d.'</option>';
    		}
    		else
    		{
    			$select.= '<option value = '.$index.'>'.$d.'</option>';
    		}
    	}
    	$select.= '</select>';
    	return $select;

    	

    }

    public function getSearchResults()
    {
        $input = request()->all();   
        if($input['primary_table'] == 'subjects')
        {
            $subjects = \DB::table('subjects')
                    ->join('courses', 'courses.id','=', 'subjects.course_id')
                    ->join('course_type', 'course_type.id','=', 'subjects.course_type_id')
                    ->select('course_title', 'course_type','subjects.*')
                    ->orderBy('created_at', 'DESC')
                    ->where('subject_name','LIKE','%'.$input['search_term'].'%') 
                    ->get();                  

            return view('subject::ajax-list-subject')
                    ->with('subjects', $subjects);

        }
    }

    public function getAutoCompleteStudent(request $request)
    {
        $data = [];
        if($request->has('q'))
        {    
            $search = $request->q;
            $data = \DB::table('student')
                    ->join('users', 'users.id', '=', 'student.user_id')
                    ->select('users.name', 'student.student_id','student.id')
                    ->where('name','LIKE',"%$search%")
                    ->orWhere('student_id','LIKE',"%$search%")
                    ->get();
        }
        
        return response()->json($data);
    }

    public function getSubjectFromStudentIdCurrentCourseAndCourseType(Request $request)
    {
        $input = request()->all();         
        $student = \Modules\Student\Entities\Student::where('id', $input['student_id'])->first();
        $subjects = \Modules\Subject\Entities\Subject::where('course_id', $student->current_course_id)
                            ->where('course_type_id', $student->current_course_type_id)
                            ->select('*')
                            ->get();
                                    
        $enrollment_subject_ids = \Modules\Enrollment\Entities\EnrollmentSubject::getEnrolledSubjects($input);

        $enrolled_subject_ids = [];
        foreach($enrollment_subject_ids as $index => $d)
        {
            $enrolled_subject_ids[] = $d->subject_id;
        }        
        
        return view('enrollment::ajax-list-enrollment-subject')
                ->with('subjects', $subjects)
                ->with('enrolled_subject_ids', $enrolled_subject_ids);
    }

    public function getCourseAndCourseTypeFromStudentID(Request $request)
    {
        $input = request()->all(); 
        $data = \DB::table('student')
                    ->join('courses', 'courses.id','=','student.current_course_id')
                    ->join('course_type','course_type.id','=','student.current_course_type_id')
                    ->where('student.id', $input['student_id'])
                    ->select('courses.course_title', 'course_type.course_type', 'courses.id as course_id', 'course_type.id as course_type_id')
                    ->first(); 

        return response()->json($data);
    }

    public function getCheckEnrolledSubjects(Request $request)
    {
        $status = "not-enrolled";
        $input = request()->all();
        $enrollment_subject_ids = \Modules\Enrollment\Entities\EnrollmentSubject::getEnrolledSubjects($input);

        foreach($enrollment_subject_ids as $index => $d)
        {            
            if($d->subject_id == $input['subject_id'])
            {
                $status = "enrolled";
                break;
            }
        }

        return $status;
    }

    public function getCourseTypeSelectBox()
    {
        $input = request()->all();
        $course_type = \Modules\Course\Entities\CourseType::select('id', 'course_type')->get(); 
        $select = '';
        $select .= '<option value="0">Select</option>';
        foreach($course_type as $index => $d)
        {
            if($d->id == $input['selected_course_type_id'])
            {
                $select .= '<option value='.$d->id.' selected>'.$d->course_type.'</option>';
            }
            else
            {
                $select .= '<option value='.$d->id.'>'.$d->course_type.'</option>';   
            }
            
        }

        return $select;
    }

    public function getEnrollmentRecordsFromStudentIdSessionId()
    {
        $input = request()->all();
        $enrollment_records = \DB::table('enrollment')
                                ->join('student', 'student.id','=','enrollment.student_id')
                                ->join('users', 'users.id','=','student.user_id')                               
                                ->join('academic_session', 'academic_session.id','=','enrollment.session_id')
                                ->join('courses', 'courses.id','=','enrollment.course_id')
                                ->join('course_type', 'course_type.id','=','enrollment.course_type_id')
                                ->where('enrollment.student_id', $input['student_id']);
        if($input['session_id'] != 0)
        {
            $enrollment_records = $enrollment_records->where('enrollment.session_id', $input['session_id']);
        }
        $enrollment_records = $enrollment_records->select('session_name','is_current', 
                                    'enrollment.*', 'academic_session.id as session_id', 'student.student_id as uniqueStudentID', 'course_title', 'course_type', 'name')
                                ->orderBy('enrollment.created_at', 'DESC')
                                ->get(); 


        $enrollment_subject_records = [];
        foreach($enrollment_records as $index => $record)
        {
            
            $enrollment_subject_records[$record->session_id]['session_name'] = $record->session_name;
            $enrollment_subject_records[$record->session_id]['session_id'] = 
                    $record->session_id;
            $enrollment_subject_records[$record->session_id]['student_id'] = 
                    $record->student_id;
            $enrollment_subject_records[$record->session_id]['student_name'] = 
                    $record->name;
            $enrollment_subject_records[$record->session_id]['uniqueID'] = $record->uniqueStudentID;
            $enrollment_subject_records[$record->session_id]['course_type'] = 
                    $record->course_type;
                                                                    
            $enrollment_subject_records[$record->session_id]['course_title'] = $record->course_title;
            $enrollment_subject_records[$record->session_id]['enrollment_id'] = $record->id;                                                      
            $enrollment_subject_records[$record->session_id]['enrolled_subjects'] = 
                                                        \DB::table('enrollment_subjects')
                                                        ->join('enrollment', 'enrollment.id','=','enrollment_subjects.enrollment_id')  
                                                        ->join('subjects', 'subjects.id','=','enrollment_subjects.subject_id')
                                                        ->join('courses', 'courses.id', '=', 'subjects.course_id')
                                                        ->join('course_type', 'course_type.id', '=', 'subjects.course_type_id')    
                                                        ->where('enrollment_subjects.enrollment_id', $record->id)
                                                        ->select('subject_name','is_graded', 'credit_points','course_type', 'course_title', 'subject_id')
                                                        ->orderBy('enrollment_subjects.created_at', 'DESC')
                                                        ->get()
                                                        ->toJson();                                                        
                                                                    
        }

        return view('enrollment::ajax-enrollment-student-list')
                    ->with('enrollment_subject_records', $enrollment_subject_records);
    }

    public function postDeleteEnrollmentRecordsFromIds(Request $request)
    {
        $status = "error";
        $input = request()->all();
        $enrolled_subject = \Modules\Enrollment\Entities\EnrollmentSubject::getEnrolledIndividualSubject($input);        
        if($enrolled_subject)
        {
            $enrolled_subject->delete();
            $status = "success";

        }
        //if there is no subject in the enrollment_subject table remove enrollment table record
        //because there can be only one enrollment record per student per session.
        $data = []; 
        $data['student_id'] = $input['student_id'];
        $data['session_id'] = $input['session_id'];
        $enrolled_subject_ids = \Modules\Enrollment\Entities\EnrollmentSubject::getEnrolledSubjects($data);                
        if($enrolled_subject_ids->isEmpty())
        {
            \Modules\Enrollment\Entities\Enrollment::deleteEnrollment($input['enrollment_id']);            
        }
        return $status;

    }

  
    public function getTeacherbyTypeAutoComplete(Request $request)
    {
        $data = [];
        if($request->has('type'))
        {    
            $search = $request->q;
            $type = $request->input('type');

            if($type == "all")
            {
                $data = \Modules\Teacher\Entities\Teacher::getSearchTeacher($search);

            }
            else if($type == 'lecturer')      
            {
                $data = \Modules\Teacher\Entities\Teacher::getSearchTeachersFromId($search, 2);
            }
            else
            {
                $data = \Modules\Teacher\Entities\Teacher::getSearchTeachersFromId($search, 4);
            }
            
        }        
        return response()->json($data);
    }

    public function getCheckAssignedTeacher(Request $request)
    {
        $status = "not-assigned";
        $input = request()->all();
        $is_Assigned = \Modules\Teacher\Entities\AssignTeacher::checkIsTeacherAssigned($input['session_id'],$input['subject_id'],$input['teacher_id'], $input['type']);
        if(count($is_Assigned))
        {
            $status = "assigned"; 
        }
        return response()->json($status);
    }

    public function postAjaxAssignTeacher(Request $request)
    {        
        $input = request()->all();  
        $status['msg'] = "error";
        
        if(\Modules\Teacher\Entities\AssignTeacher::assignTeacher($input['session_id'],$input['subject_id'],$input['teacher_id'], $input['type']))
        {
            $status['msg'] = "success";        
        }      
        $status['type'] = $input['type'];
        return response()->json($status);
    }

    public function getSubjectFromCourseTypeAndCourse(Request $request)
    {
        $input = request()->all();
        $subjects = \Modules\Subject\Entities\Subject::getSubjectsListFromCourseTypeAndCourse($input['course_type_id'], $input['course_id'])->get();
        $select = '';
        $select .= '<option value="0"> Select  </option>';

        foreach($subjects as $index => $subject)
        {
            if($subject->id == $input['selected_subject_id'])
            {
                $select.= '<option value = '.$subject->id.' selected>'.$subject->subject_name.'</option>';
            }
            else
            {
                $select.= '<option value = '.$subject->id.'>'.$subject->subject_name.'</option>';
            }
        }
        $select.= '</select>';
        return $select;        
    }

    public function getAssignedTeachersFromSessionAndTeacherID(Request $request)
    {
        $input = request()->all();
        $data = \Modules\Teacher\Entities\AssignTeacher::getAssignedTeacherSubjectBySessionAndTeacherId($input['session_id'], $input['teacher_id']);
                            
        return view('teacher::ajax-list-assigned-teacher-from-session-and-teacher-id')->with('assigned_teachers', $data);
        
    }

    public function postAjaxDeleteAssignedTeacher(Request $request)
    {
        $data['msg'] = ''; 
        $data['status'] = 'error';
        $assigned_teacher = \Modules\Teacher\Entities\AssignTeacher::findorFail($request->input('assignedId'));
        if(!$assigned_teacher)
        {   
            $data['msg'] = 'Selected ID is not available';
            return response()->json($status);
        }
        $assigned_teacher->delete(); 
        $data['msg'] = 'Removed Successfully'; 
        $data['status'] = 'success';
        return response()->json($data);
    }


    public function getStudentFromCourseTypeAndCourse(Request $request)
    {
        $input = request()->all(); 
        $students = (new \Modules\Student\Entities\Student)->getStudentListFromIds($input['course_type_id'], $input['course_id']);
        return view('student::ajax-student-list')->with('students', $students);
    }

    public function getAssignedTeacherByClassType(Request $request)
    {
        $data = (new \Modules\Teacher\Entities\AssignTeacher)->getTeachersAssignedFromClassTypeAndSubject($request->input('session_id'), $request->input('subject_id'), $request->input('type'));
        $selected_id = $request->input('selected_teacher_id');        
        $select_list = (new \App\SelectList)->generateDynamicSelectListWithOneAdditionalParam($data, $selected_id ,'id','name', 'teacher_id');

        return $select_list;
    }   

    public function getAjaxRoomByRoomType(Request $request)
    {
        $data = (new \Modules\Room\Entities\Room)->getRoomsByType($request->input('room_type'));
        $selected_id = $request->input('selected_room_id');
        $select_list = (new \App\SelectList)->generateDynamicSelectList($data, $selected_id ,'id','room_code');
        return $select_list;
    }
    
    public function getAjaxCheckTeacherClasses(Request $request)
    {
        $data = request()->all();
        $teacher_classes = (new \Modules\Classes\Entities\Classes)->checkTeacherClasses($data['session_id'], $data['subject_id'], $data['teacher_id'], $data['type']);
        if($teacher_classes)
        {
            return "has-classes";
        }
        return "no-classes";

    }

    public function getCheckCurrentSession()
    {
        $current_session = \Modules\AcademicSession\Entities\AcademicSession::getCurrentSession();
        if($current_session)
        {
            return "found";
        }
        return "not-found";
    }

    public function getEnrolledStudentsFromSession(Request $request)
    {   
        $session_id = $request->input('session_id');
        $selected_student_id = $request->input('selected_student_id');

        $enrolled_student_list = (new \Modules\Student\Entities\Student)->getEnrolledStudentList($session_id);
        return (new \App\SelectList)->generateDynamicSelectListWithOneAdditionalParam($enrolled_student_list, $selected_student_id, 'id', 'name','uniqueID');
    
    }

    public function getAjaxTeacherAssignedSubjectsFromSession(Request $request)
    {
        $input = request()->all();         
        $teacher_subjects = (new \Modules\Teacher\Entities\AssignTeacher)->getAssignedTeacherDistinctSubjectBySessionAndTeacherId($input['session_id'], $input['teacher_id']);
        $params = ['course_type', 'course_title']; 
        $input['selected_subject_id'] = isset($input['selected_subject_id']) ? $input['selected_subject_id'] : '';
        return (new \App\SelectList)->generateDynamicSelectListWithMultipleParams($teacher_subjects, $input['selected_subject_id'], 'id', 'subject_name', $params);

    }

    public function getAjaxExamSelectListFromSession(Request $request)
    {   
        $session_id = $request->input('session_id');
        $selected_id = $request->input('selected_exam_id');
        $exam_list = (new \Modules\Exam\Entities\Exam)->getExamsFromSession($session_id);         
        $select_list = (new \App\SelectList)->generateDynamicSelectList($exam_list, $selected_id ,'id','exam_name');
        return $select_list;
    }

    public function postUpdateExamMarks(Request $request)
    {   
        $status = [];
        $input = request()->all();
        try {
            (new \Modules\Exam\Entities\ExamMarks)->updateSingleStudentExamMarks($input);
            $status['status'] = 'success'; 
            $status['msg'] = 'Marks Updated Successfully';

        }catch(\Exception $e)
        {
            $status['status'] = 'error'; 
            $status['msg'] = $e->getMessage();
        }
        return $status;
    }

    public function getAjaxAssignmentSelectList(Request $request)
    {   
        $input = request()->all();
        echo '<pre>';
        print_r($input);
        
        $selected_id = $input['selected_assignment_id'];
        $assignment_list = (new \Modules\Assignment\Entities\Assignment)->getAssignmentSelectListOnly($input['session_id'], $input['subject_id']);
        $select_list = (new \App\SelectList)->generateDynamicSelectList($assignment_list, $selected_id ,'id','title');
        return $select_list;
    }

    public function postUpdateAssignmentMarks()
    {
        $status = [];
        $input = request()->all();
        try {
            (new \Modules\Assignment\Entities\AssignmentMarks)->updateSingleStudentAssignmentMarks($input);
            $status['status'] = 'success'; 
            $status['msg'] = 'Marks Updated Successfully';

        }catch(\Exception $e)
        {
            $status['status'] = 'error'; 
            $status['msg'] = $e->getMessage();
        }
        return $status;   
    }

    public function getAjaxIndividualStudentResult(Request $request)
    {
        $input = request()->all();
        $result = (new \Modules\Result\Entities\Result)->getResult($input['session_id'], $input['student_id']);
        
        return view('result::ajax-view-student-result')
                ->with('result', $result);
    }

    public function getEnrolledSubjectsDashboard(Request $request)
    {
        $input = request()->all();
        if($input['role'] == "student")
        {
            $data = (new \Modules\Enrollment\Entities\EnrollmentSubject)->getEnrolledSubjectsForDashboard($input['session_id'], $input['student_id']);
            return view('enrollment::frontend-ajax-student-enrolled-subject')->with('data', $data);
        }
        else if ($input['role'] == "teacher")
        {
            $data = (new \Modules\Teacher\Entities\AssignTeacher)->getAssignedTeacherDistinctSubjectBySessionAndTeacherId($input['session_id'], $input['teacher_id']);
            return view('teacher::frontend-ajax-assigned-subjects')->with('data', $data);
        }
        else
        {
            return [];    
        }        
    }

    public function getCheckSessionSettings(Request $request)
    {   
        $input = request()->all(); 
        $access = (new \Modules\AcademicSession\Entities\AcademicSessionSettings)->checkAccess($input['session_id'], $input['access_type']);
        return $access;
    }
}
