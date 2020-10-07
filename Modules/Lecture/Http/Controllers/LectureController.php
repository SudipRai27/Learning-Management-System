<?php

namespace Modules\Lecture\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\AcademicSession\Entities\AcademicSession;
use Modules\Subject\Entities\Subject;
use Modules\Lecture\Entities\Lecture;
use Modules\User\Entities\UserRole;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Entities\AssignTeacher;
use App\Resource;
use Session;
use Auth;

class LectureController extends Controller
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

    public function getListLecture(Request $request)
    {
        if($request->has('session_id') && $request->has('subject_id'))
        {
            $selected_session_id = $request->input('session_id');
            $selected_subject_id = $request->input('subject_id');
            $lecture_list = (new Lecture)->getLectureListFromSessionAndSubject($selected_session_id, $selected_subject_id);  
        }
        
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
                
        
        return view('lecture::list-lecture')
            ->with('academic_session', $academic_session)
            ->with('subjects', isset($subjects) ? $subjects : []) 
            ->with('selected_session_id', isset($selected_session_id) ? $selected_session_id : '')
            ->with('selected_subject_id', isset($selected_subject_id) ? $selected_subject_id : '')
            ->with('lecture_list', isset($lecture_list) ? $lecture_list : null)
            ->with('teacher_id', isset($teacher_id) ? $teacher_id : '');
    }   

    public function getCreateLecture(Request $request)
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
        return view('lecture::create-lecture')        
        ->with('subjects', isset($subjects) ? $subjects : [])
        ->with('academic_session', isset($academic_session) ? $academic_session : [])
        ->with('teacher_id', isset($teacher_id) ? $teacher_id : []);                
    }

    public function postCreateLecture(Request $request)
    {                        
        $input = request()->all();        
        $files = isset($input['files']) ? $input['files'] : [];
        $request->validate(Lecture::getCreateValidationRules($files, $input['session_id'], $input['subject_id'], $input['sort_order']), Lecture::getValidationMessages());
        try
        {  
            \DB::beginTransaction();
            $lecture = (new Lecture)->createLecture($input['lecture_name'], $input['lecture_description'], $input['subject_id'], $input['session_id'], $input['sort_order']);

            if(isset($input['files']))
            {
                $lecture_folder = 'lectures/session-'. $input['session_id'] .'/'.'subject-'.$input['subject_id'].'/';
                
                $this->uploadLectureFiles($input['files'], $lecture_folder, $lecture->id);

            }   

            \DB::commit();            
        }
        catch(\Exception $e) {
            \DB::rollback();
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Lecture created successfully');
    }

    public function getEditLecture($lecture_id)
    {        
        try {

            $lecture = (new Lecture)->getIndividualLectureAndResources($lecture_id)->toArray();
            if($this->current_user_role[0]->role_name == 'lecturer' || $this->current_user_role[0]->role_name == 'tutor')
            {                 
                $teacher_id = (new Teacher)->getTeacherIdFromUserId(Auth::id());
                $assign_teacher = new AssignTeacher;
                $teacher_subjects = $assign_teacher->getAssignedTeacherDistinctSubjectBySessionAndTeacherId($lecture['session_id'], $teacher_id);
                $academic_session = $assign_teacher->getTeacherAssignedYears($teacher_id);
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
        return view('lecture::edit-lecture')
                ->with('lecture', $lecture)
                ->with('subjects', isset($subjects) ? $subjects : [])
                ->with('academic_session', isset($academic_session) ? $academic_session : [])
                ->with('teacher_subjects', isset($teacher_subjects) ? $teacher_subjects : [])
                ->with('teacher_id', isset($teacher_id) ? $teacher_id : []);               
    }

    public function postUpdateLecture(Request $request, $lecture_id)
    {
        $input = request()->all();
        $files = isset($input['files']) ? $input['files'] : [];
        $request->validate(Lecture::getUpdateValidationRules($files, $input['session_id'], $input['subject_id'], $input['sort_order'], $lecture_id), Lecture::getValidationMessages());

        try {  

            $lecture = (new Lecture)->getLectureOnly($lecture_id);
            (new Lecture)->updateLecture($lecture_id, $input['lecture_name'], $input['lecture_description'], $input['session_id'], $input['subject_id'], $input['sort_order']);
            $current_lecture_resources = (new Resource)->getResource($lecture_id, 'lectures');
            //Move files only if files not selected and different parameters are provided
            if(!isset($input['files']) && count($current_lecture_resources) > 0)
            {                
                if($input['session_id'] != $lecture->session_id || $input['subject_id'] != $lecture->subject_id)
                {
                    $old_lecture_folder = 'lectures/session-'.$lecture->session_id.'/'.'subject-'.$lecture->subject_id.'/';
                    $new_lecture_folder = 'lectures/session-'.$input['session_id'].'/'.'subject-'.$input['subject_id'].'/';

                    foreach($current_lecture_resources as $key => $file)
                    {
                        (\App\Http\Controllers\FileController::moveFileInS3($old_lecture_folder,$new_lecture_folder, $file));
                    }

                }
            }

            if(isset($input['files']))
            {
                $lecture_folder = 'lectures/session-'.$lecture->session_id.'/'.'subject-'.$lecture->subject_id.'/';
                
                //////DELETE CURRENT RESOURCES
                if(count($current_lecture_resources) > 0)
                {
                    foreach($current_lecture_resources as $index => $resource)
                    {                        
                        (\App\Http\Controllers\FileController::removeFileFromS3($lecture_folder, $resource));
                        (new Resource)->deleteResource($resource['resource_id']);
                    }

                }
                //////ADD NEW SELECTED RESOURCES
                if($input['session_id'] != $lecture->session_id || $input['subject_id'] != $lecture->subject_id)
                {
                    $lecture_folder = 'lectures/session-'.$input['session_id'].'/'.'subject-'.$input['subject_id'].'/';
                }
                $this->uploadLectureFiles($input['files'], $lecture_folder, $lecture_id);
            }   


        }
        catch(\Exception $e)
        {            
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Lecture updated successfully');
    }

    public function uploadLectureFiles($files, $folder, $lecture_id)
    {   
        (new Resource)->uploadResources($files, $folder, $lecture_id, 'lectures');           
        
        return;
    }

    public function postRemoveLectureResource($resource_id)
    {
        try {
            $resource = new Resource;
            $file = $resource->getSingleResource($resource_id);
            $parent_table_details = $resource->getResourceTableParentDetails('lectures', $resource_id);
            $lecture_folder = 'lectures/session-'.$parent_table_details->session_id.'/'.'subject-'.$parent_table_details->subject_id.'/';
            (\App\Http\Controllers\FileController::removeFileFromS3($lecture_folder, $file));
            $resource->deleteResource($resource_id);
        }catch (\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Resource removed successfully');
    }

    public function postDeleteLecture($lecture_id)
    {   
        try {
            $lecture_class = new Lecture;
            $lecture = $lecture_class->getIndividualLectureAndResources($lecture_id);
            $lecture_class->deleteLecture($lecture->id);
            if(count($lecture->resources) > 0)
            {
                $lecture_folder = 'lectures/session-'.$lecture->session_id.'/'.'subject-'.$lecture->subject_id.'/';
                foreach($lecture->resources as $index => $resource)
                {
                    (\App\Http\Controllers\FileController::removeFileFromS3($lecture_folder, $resource));
                    (new Resource)->deleteResource($resource['resource_id']);
                }
            }

        }catch(\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Lecture deleted successfully');
    }

    public function getManageResources($lecture_id)
    {
        try {
            $lecture = (new Lecture)->getIndividualLectureAndResources($lecture_id);            
        }
        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        return view('lecture::manage-resources')->with('lecture', $lecture);
    }

    public function postUploadLectureResources(Request $request)
    {
        try {
            $input = request()->all();
            if(!isset($input['files']))
            {
                return redirect()->back()->with('error-msg', 'Please provide at least one resource file to upload');
            }

            $lecture_folder = 'lectures/session-'. $input['session_id'] .'/'.'subject-'.$input['subject_id'].'/';
                
            (new Resource)->uploadResources($input['files'], $lecture_folder, $input['lecture_id'], 'lectures');            
        }

        catch (\Exception $e)
        {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Resource uploaded successfully');
    }

}
