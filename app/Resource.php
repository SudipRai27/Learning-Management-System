<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources'; 

    protected $fillable = ['resource_id', 'filename', 's3_url', 'resource_table', 'unique_id'];

    protected $guarded = ['created_at', 'updated_at'];

    public static function getTableName()
    {
    	return with(new Static)->getTable();
    }

    public function createResource($resource_id, $filename, $s3_url, $resource_table, $unique_id)
    {
    	return Resource::create([
    		'resource_id' => $resource_id, 
    		'filename' => $filename, 
    		's3_url' => $s3_url, 
    		'resource_table' => $resource_table,
            'unique_id' => $unique_id
    	]);
    }

    public function getResource($resource_id, $resource_table)
    {
        return Resource::where('resource_id',$resource_id)
                                    ->select('id as resource_id', 'filename', 's3_url', 'unique_id')
                                    ->where('resource_table',$resource_table)
                                    ->get()
                                    ->toArray();
    }

    public function deleteResource($id)
    {
        return Resource::where('id', $id)->delete();
    }


    public function getResourceTableParentDetails($table, $resource_id)
    {
        return \DB::table($this->table)
                ->join($table, $table.'.id','=',$this->table.'.resource_id')
                ->where($this->table.'.id',$resource_id)
                ->where($this->table.'.resource_table', $table)
                ->select($table.'.*')
                ->first();
        
    }

    public function getSingleResource($resource_id)
    {
        return Resource::where('id', $resource_id)
                ->select('id as resource_id', 'filename', 's3_url', 'unique_id')
                ->first();
    }

    public function getResourceFromParentTableId($parent_table_id, $parent_table)
    {
        return Resource::where('resource_id', $parent_table_id)
                        ->where('resource_table', $parent_table)
                        ->select('id', 'unique_id', 'filename', 's3_url', 'resource_table')
                        ->get()
                        ->toArray();
    }    

    public function uploadResources($files, $folder, $resource_id, $resource_model)
    {
        foreach ($files as $file) 
        {  
            $response = (\App\Http\Controllers\FileController::uploadFileToS3($folder, $file));
            
            if(!is_array($response))
            {
                return redirect()->back()->with('error-msg', $response);
            }  
            
            $this->createResource($resource_id, $response['filename'], $response['s3_url'], $resource_model, $response['unique_id']);
        }
        return;
    }
}
