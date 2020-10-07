<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['title', 'description', 'sort_order', 'is_active'];

    protected $table = 'slider';

    public static function getTableName()
    {
    	return with(new Static)->getTableName();
    }

    public function createUpdateSlider($action, $input)
    {
    	if($action == 'create')
    	{
    		return $this->create([
    			'title' => $input['title'], 
    			'description' => $input['description'], 
    			'sort_order' => $input['sort_order'], 
    			'is_active' => $input['is_active']
    		]);
    	}
    	return $this->where('id', $input['slider_id'])
    				->update([
    					'title' => $input['title'], 
		    			'description' => $input['description'], 
		    			'sort_order' => $input['sort_order'], 
		    			'is_active' => $input['is_active']
    			]);
    }

    public function getSliderList() 
    {
    	return $this->orderBy('created_at', 'DESC')
    				->get();
    				
    }

    public function getSingleSlider($slider_id)
    {       
        $slider = $this->findOrFail($slider_id);
        $slider->resource = (new \App\Resource)->getResource($slider_id, 'slider');
        return $slider;
    }   

    public function deleteslider($slider_id)
    {
        return $this->where('id', $slider_id)->delete();
    }

    public function getFrontendSlider()
    {
        $resource_table = (new \App\Resource)->getTableName();
        $slider = \DB::table($this->table)
                ->join($resource_table,$resource_table.'.resource_id', '=',$this->table.'.id')
                ->where('resource_table', 'slider')
                ->select($this->table.'.id', 'title', 'description', 'sort_order','s3_url', 'is_active')
                ->orderBy('sort_order','ASC')
                ->where('is_active','yes')                
                ->get();

        return $slider;
    }
}
