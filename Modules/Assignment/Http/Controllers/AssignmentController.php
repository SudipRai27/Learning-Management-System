<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Subject\Entities\Subject;
use Modules\User\Entities\UserRole;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentMarks;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Entities\AssignTeacher;
use Modules\Student\Entities\Student;
use App\Resource;
use Auth;
use Session;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public $current_user_role;

    public function __construct() {

        $this->middleware(function ($request, $next) {
            $this->current_user_role = UserRole::getCurrentUserRole(Auth::id());
        return $next($request);
        });       
    }


    public function getListAssignment(Request $request) {        
        
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor' )
        {
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
            $subjects = [];
        }
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            $subjects = Subject::getAllSubjects()->get();
        }
        
        if($request->has('session_id') && $request->has('subject_id'))
        {
            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');
            $assignment_list = (new Assignment)->getAssignmentListFromSessionAndSubject($selected_session_id, $selected_subject_id);  

        }

        return view('assignment::list-assignment')
            ->with('subjects', isset($subjects) ? $subjects : [])
            ->with('academic_session', isset($academic_session) ? $academic_session : [])        
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('assignment_list', isset($assignment_list) ? $assignment_list : null)
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');
        

    }

    public function getCreateAssignment(Request $request) {
        
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor' )
        {
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
            $subjects = [];
        }
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            $subjects = Subject::getAllSubjects()->get();
        }
    
        return view('assignment::create-assignment')
                ->with('subjects', isset($subjects) ? $subjects : [])
                ->with('academic_session', isset($academic_session) ? $academic_session : [])
                ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');
                
                
    }

    public function postCreateAssignment(Request $request)
    {
        $input = request()->all();             
        $files = isset($input['files']) ? $input['files'] : [];
        $request->validate(Assignment::getCreateValidationRules($files, $input['session_id'], $input['subject_id'], $input['sort_order']), Assignment::getValidationMessages());

        try {
            \DB::beginTransaction();
                $formattedDate = (new \App\DayAndDateTime)->formatDateTime($input['submission_date']);
                $input['submission_date'] = $formattedDate;
                $assignment = (new Assignment)->createAssignment($input);

                if(isset($input['files']))
                {
                    $assignment_folder = 'assignment/session-'. $input['session_id'] .'/'.'subject-'.$input['subject_id'].'/';
                
                    (new Resource)->uploadResources($input['files'], $assignment_folder, $assignment->id, 'assignment');                     
                }   
            \DB::commit();            

        }catch(\Exception $e)
        {
            \DB::rollback();
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();
        }
        
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Assignment created successfully');
    }

    public function getEditAssignment(Request $request, $assignment_id)
    {
        try {
            $assignment = (new Assignment)->getIndividualAssignmentAndResources($assignment_id)->toArray();  
            if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
            {                 
                $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
                $assign_teacher = new AssignTeacher;
                $subjects = $assign_teacher->getAssignedTeacherDistinctSubjectBySessionAndTeacherId($assignment['session_id'], $teacher_id);
                $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
            }
            else
            {   
                $academic_session = (new AcademicSession)->getAcademicSession();          
                $subjects = Subject::getAllSubjects()->get();
            }   
            

        }
        catch(\Exception $e)
        {            
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        return view('assignment::edit-assignment')
                ->with('assignment', $assignment)
                ->with('subjects', isset($subjects) ? $subjects : [])
                ->with('academic_session', isset($academic_session) ? $academic_session : [])  
                ->with('assignment_academic_session', isset($assignment_academic_session) ? $assignment_academic_session : '')
                ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');

    }

    public function postUpdateAssignment(Request $request, $assignment_id)
    {
        $input = request()->all();
        $files = isset($input['files']) ? $input['files'] : [];
        $request->validate(Assignment::getUpdateValidationRules($files, $input['session_id'], $input['subject_id'], $input['sort_order'], $assignment_id), Assignment::getValidationMessages());

        try {  

            $assignment = (new Assignment)->getIndividualAssignmentOnly($assignment_id);
            (new Assignment)->updateAssignment($assignment_id, $input);
            $assignment_resources = (new Resource)->getResource($assignment_id, 'assignment');
            //Move files only if files not selected and different parameters are provided
            if(!isset($input['files']) && count($assignment_resources) > 0)
            {                
                if($input['session_id'] != $assignment->session_id || $input['subject_id'] != $assignment->subject_id)
                {
                    $old_assignment_folder = 'assignment/session-'.$assignment->session_id.'/'.'subject-'.$assignment->subject_id.'/';
                    $new_assignment_folder = 'assignment/session-'.$input['session_id'].'/'.'subject-'.$input['subject_id'].'/';

                    foreach($assignment_resources as $key => $file)
                    {
                        (\App\Http\Controllers\FileController::moveFileInS3($old_assignment_folder,$new_assignment_folder, $file));
                    }

                }
            }

            if(isset($input['files']))
            {
                $assignment_folder = 'assignment/session-'.$assignment->session_id.'/'.'subject-'.$assignment->subject_id.'/';
                
                //////DELETE CURRENT RESOURCES
                if(count($assignment_resources) > 0)
                {
                    foreach($assignment_resources as $index => $resource)
                    {                        
                        (\App\Http\Controllers\FileController::removeFileFromS3($assignment_folder, $resource));
                        (new Resource)->deleteResource($resource['resource_id']);
                    }

                }
                //////ADD NEW SELECTED RESOURCES
                if($input['session_id'] != $assignment->session_id || $input['subject_id'] != $assignment->subject_id)
                {
                    $assignment_folder = 'assignment/session-'.$input['session_id'].'/'.'subject-'.$input['subject_id'].'/';
                }
                
                (new Resource)->uploadResources($input['files'], $assignment_folder, $assignment_id, 'assignment');           
            }   
        }
        catch(\Exception $e)
        {            
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Assignment updated successfully');
    }

    public function postDeleteAssignment($assignment_id)
    {
        try {
            $assignment_class = new Assignment;
            $assignment = $assignment_class->getIndividualAssignmentAndResources($assignment_id);           
            $assignment_class->deleteAssignment($assignment->id);
            if(count($assignment->resources) > 0)
            {
                $assignment_folder = 'assignment/session-'.$assignment->session_id.'/'.'subject-'.$assignment->subject_id.'/';
                foreach($assignment->resources as $index => $resource)
                {
                    (\App\Http\Controllers\FileController::removeFileFromS3($assignment_folder, $resource));
                    (new Resource)->deleteResource($resource['resource_id']);
                }
            }

        }catch(\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Assignment deleted successfully');
    }

    public function getManageAssignmentResources($assignment_id)
    {
        try {
            $assignment = (new Assignment)->getIndividualAssignmentAndResources($assignment_id);        
        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        return view('assignment::manage-resources')->with('assignment', $assignment);
    }


    public function postRemoveAssignmentResource($resource_id)
    {
        try {

            $resource = new Resource;
            $file = $resource->getSingleResource($resource_id);
            $parent_table_details = $resource->getResourceTableParentDetails('assignment', $resource_id);
            $assignment_folder = 'assignment/session-'.$parent_table_details->session_id.'/'.'subject-'.$parent_table_details->subject_id.'/';
            (\App\Http\Controllers\FileController::removeFileFromS3($assignment_folder, $file));
            $resource->deleteResource($resource_id);

        }catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Resource removed successfully');
    }

    public function postUploadAssignmentResources(Request $request)
    {
        try {
            $input = request()->all();

            if(!isset($input['files']))
            {
                return redirect()->back()->with('error-msg', 'Please provide at least one resource file to upload');
            }

            $assignment_folder = 'assignment/session-'. $input['session_id'] .'/'.'subject-'.$input['subject_id'].'/';
                
            (new Resource)->uploadResources($input['files'], $assignment_folder, $input['assignment_id'], 'assignment');            
        }

        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Resource uploaded successfully');
    }

    public function getSubmitAssignment($session_id, $subject_id, $assignment_id)
    {
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            $unique_student_id = Student::where('id', $student_id)->first()->student_id;
            
        }
        else
        {
            Session::flash('error-msg', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', 'Only students are allowed to submit assignment.');
        }

        try {
            $assignment_submission = new AssignmentSubmission;
            $assignment = (new Assignment)->getIndividualAssignmentOnly($assignment_id);
            $status = [];
            $status['submission_status'] = $assignment_submission->checkStudentSubmission($assignment_id, $student_id); 
            $submission = $assignment_submission->getAssignmentSubmission($assignment_id, $student_id);
            if($submission)
            {
                $status['modified_at'] = $submission->updated_at;
            }
            $grading = (new AssignmentMarks)->getIndividualStudentAssignmentMarks($assignment_id, $student_id);
            if($grading)
            {
                $status['grading_status'] = 'Graded / and obtained Marks :'. $grading->obtained_marks;       
            }
              
        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage()); 
        }

        return view('assignment::submit-assignment')->with('assignment', $assignment)
                                                    ->with('status', $status);
            
    }

    public function getAddSubmissionFiles($assignment_id)
    {        

        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            $unique_student_id = Student::where('id', $student_id)->first()->student_id;
            
        }
        else
        {
            Session::flash('error-msg', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', 'Only students are allowed to submit assignment.');
        }

        $submission = (new AssignmentSubmission)->getAssignmentSubmission($assignment_id, $student_id);
        if($submission)
        {
            $submission_resources = (new \App\Resource)
                                        ->getResource($submission->id, 'assignment_submission');                                        
        }


        return view('assignment::add-submission-files')->with('assignment_id', $assignment_id)
                                                       ->with('submission_resources', isset($submission_resources) ? $submission_resources : []);    
    }

    public function postsubmitAssignmentFiles(Request $request)
    {   
        if($this->current_user_role[0]->role_name == 'student')
        {
            $student_id = (new Student)->getStudentId(Auth::id());
            $unique_student_id = Student::where('id', $student_id)->first()->student_id;
            
        }
        else
        {
            Session::flash('error-msg', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', 'Only students are allowed to submit assignment.');
        }

        $input = request()->all();
        if(!isset($input['files']))
        {
            Session::flash('error-msg', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', 'Please provide at least one assignment file to upload.');
        }

        $files = isset($input['files']) ? $input['files'] : [];
        $request->validate(AssignmentSubmission::getValidationRules($files));

        try {   

            \DB::beginTransaction();
            $assignment = (new Assignment)->getIndividualAssignmentOnly($input['assignment_id']);
            $submission = (new AssignmentSubmission)->createUpdateSubmission($input['assignment_id'], $student_id);
            $assignment_submission_folder = 
            'assignment_submission/assignment-'.$assignment->id. '/student-id-'.$student_id.'/';                
            (new Resource)->uploadResources($files, $assignment_submission_folder, $submission->id, 'assignment_submission'); 
            \DB::commit();

        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('success-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Assignment submitted successfully. Please wait for grading'); 

    }

    public function postRemoveAssignmentSubmissionResource($resource_id)
    {
        try {
            if($this->current_user_role[0]->role_name == 'student')
            {
                $student_id = (new Student)->getStudentId(Auth::id());
                $unique_student_id = Student::where('id', $student_id)->first()->student_id;
                
            }
            else
            {
                Session::flash('error-msg', 'Operation Unsuccessful');
                return redirect()->back()->with('error-msg', 'Only students are allowed to remove assignment files.');
            }
            
            $resource = new Resource;
            $file = $resource->getSingleResource($resource_id);

            $parent_table_details = $resource->getResourceTableParentDetails('assignment_submission', $resource_id);  
            

            $assignment_submission_folder = 
            'assignment_submission/assignment-'.$parent_table_details->assignment_id. '/student-id-'.$student_id.'/';
            (\App\Http\Controllers\FileController::removeFileFromS3($assignment_submission_folder, $file));
            $resource->deleteResource($resource_id);
            if(!count((new \App\Resource)->getResource($parent_table_details->id, 'assignment_submission')))
            {
                (new AssignmentSubmission)->deleteSubmission($parent_table_details->id);
            }

        }catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Submission file removed successfully');

    }

    public function getViewSubmissionAssignments($session_id, $subject_id, $assignment_id)
    {
        try {
            $subject = (new Subject)->getIndividualSubject($subject_id);
            $student_submission = (new AssignmentSubmission)->getStudentSubmissionDetails($session_id, $subject_id, $assignment_id);     
            $assignment = (new Assignment)->getIndividualAssignmentOnly($assignment_id);            

        } catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        return view('assignment::view-submission-files')->with('student_submission', $student_submission)
                                                        ->with('subject', $subject)
                                                        ->with('session_id', $session_id)
                                                        ->with('assignment_id', $assignment_id)
                                                        ->with('assignment', $assignment);

    }

    public function deleteAssignmentSubmission($submission_id, $student_id)
    {
        try {

            $submission_class = new AssignmentSubmission;
            $submission = $submission_class->getIndividualSubmissionAndResources($submission_id);
            if(count($submission->resources) > 0)
            {
                $assignment_submission_folder = 
                'assignment_submission/assignment-'.$submission->assignment_id. '/student-id-'.$student_id.'/';
                foreach($submission->resources as $index => $resource)
                {
                    (\App\Http\Controllers\FileController::removeFileFromS3($assignment_submission_folder, $resource));
                    (new Resource)->deleteResource($resource['resource_id']);
                }
            }
            $submission_class->deleteSubmission($submission_id);

        }catch(\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Submission removed successfully');
    }

    public function getBackendViewSubmissionAssignments(Request $request)
    {
        if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
        {
            $subjects = [];
            $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
            $academic_session = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
     
        } 
        else
        {
            $academic_session = (new \Modules\AcademicSession\Entities\AcademicSession)->getAcademicSession();
            $subjects = Subject::getAllSubjects()->get(); 
        }
                
        if($request->has('session_id') && $request->has('subject_id') && $request->has('assignment_id'))
        {

            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');   
            $selected_assignment_id = $request->input('assignment_id');     
            if($selected_session_id && $selected_subject_id && $selected_assignment_id)
            {       
                $student_submission = (new AssignmentSubmission)->getStudentSubmissionDetails($selected_session_id, $selected_subject_id, $selected_assignment_id);     
                $assignment = (new Assignment)->getIndividualAssignmentOnly($selected_assignment_id);
                
            }            
            else
            {
                $student_submission = null;
            }
            
        }
        
        return view('assignment::view-submission-backend')
            ->with('subjects', isset($subjects) ? $subjects : [])
            ->with('academic_session', isset($academic_session) ? $academic_session : [])
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('selected_assignment_id', isset($selected_assignment_id) ? $selected_assignment_id : '')
            ->with('student_submission', isset($student_submission) ? $student_submission : null)
            ->with('assignment', isset($assignment) ? $assignment : '
                ')
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : ''); 
        
    }

}

