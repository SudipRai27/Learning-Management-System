<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
     
    public function uploadFile($file_path, $file)
    {
      	$file_name = uniqid() . $file->getClientOriginalName();
      	$ext = $file->getClientOriginalExtension();
        $file->move(base_path('/'). '/'. $file_path,$file_name,$ext);
        return $file_name;
    	
    }

    public static function deleteFile($file_path, $file_name)
    {
    	$file_path = base_path('/').'/'.$file_path.'/'.$file_name;
    	if(\File::exists($file_path))
    	{
    		\File::delete($file_path);
    	}
    	return; 
    }


    public static function uploadFileToS3($folder, $file)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit','256M');

        try {
            $unique_id = uniqid();
            $file_full_name = $file->getClientOriginalName(); 
            $filename = pathinfo($file_full_name, PATHINFO_FILENAME);
            $extension = pathinfo($file_full_name, PATHINFO_EXTENSION);
            $s3 = \Storage::disk('s3');
            $filePath = $folder . $filename. '-'.$unique_id.'.'.$extension;           
            $stream = fopen($file->getRealPath(), 'r+');
            $s3->put($filePath, $stream);   
            $response['filename'] = $filename.'.'.$extension; 
            $response['s3_url'] = $s3->url($filePath);
            $response['unique_id'] = $filename. '-'.$unique_id.'.'.$extension;
            return $response;
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
        
    }

    public static function removeFileFromS3($folder, $file)
    {
        try{     
            $s3 = \Storage::disk('s3');
            if($s3->exists($folder. $file['unique_id']))            
            {                
                $s3->delete($folder. $file['unique_id']);  

            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }        
        return; 
    }

    public static function moveFileInS3($old_folder, $new_folder, $file)
    {        
        try {
            $s3 = \Storage::disk('s3');
            if($s3->exists($old_folder. $file['unique_id']))            
            {
                $s3->move($old_folder. $file['unique_id'], $new_folder. $file['unique_id']);
            }            
        } catch (\Exception $e)
        {
            return redirect()->back()->with('error-msg', $e->getMessage());
        }
    }
}
