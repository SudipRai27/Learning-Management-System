<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Carbon\Carbon;

class HelperController extends Controller
{
    public function getTotalStudents()
    {
    	$total_students = \Modules\Student\Entities\Student::count();
    	return $total_students;
    }

    public function getTotalTeachers()
    {
    	$total_teachers = \Modules\Teacher\Entities\Teacher::count();
    	return $total_teachers;
    }

    public function getTotalSubjects()
    {
    	$total_subjects = \Modules\Subject\Entities\Subject::count();
    	return $total_subjects;
    }

    public function getTotalCourses()
    {
    	$total_courses = \Modules\Course\Entities\Course::count();
    	return $total_courses;
    }

    public function postPublishNotice(Request $request)
    {        
        $input = request()->all();    
        try {
           $this->updateNoticeContents($input['notice'], Carbon::now()->format('Y-M-d h:i:a'));
        }   
        catch (\Exception $e)
        {
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        return redirect()->back()->with('success-msg', 'Notice updated successfully');        
    }

    public function getPublishedNotice()
    {
        try 
        {
            if(File::exists(base_path().'/Modules/notice.json'))
            {
                $file = File::get(base_path().'/Modules/notice.json');
            }
            else
            {   
                return [];
            }
            $file = File::get(base_path().'/Modules/notice.json');
            $contents = json_decode($file, true);

        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }

        return $contents;     
    }

    public function postdeleteNotice()
    {
        try {
            $this->updateNoticeContents('','');
        }   
        catch (\Exception $e)
        {
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
        return redirect()->back()->with('success-msg', 'Notice removed successfully');        
    }

    public function updateNoticeContents($contents = '', $created_at = '')
    {        
        if(File::exists(base_path().'/Modules/notice.json'))
        {
            $file = File::get(base_path().'/Modules/notice.json');
        }
        else
        {   
            $file = fopen(base_path().'/Modules/notice.json',"w");
        }        
        $notice = [];
        $notice['notice'] = $contents;
        $notice['created_at'] = $created_at;        
        return File::put(base_path().'/Modules/notice.json', json_encode($notice, JSON_PRETTY_PRINT));    
    }

    public function getFrontendEvents($events_for)
    {
        return (new \Modules\Event\Entities\Event)->getFrontendEvents($events_for);
    }

    public function getEnrolledStudentCountBySession()
    {
        return (new \Modules\Enrollment\Entities\Enrollment)->getEnrolledStudentCountBySession();
    }
}
