<?php

namespace Modules\Slider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Slider\Http\Requests\SliderRequest;
use Modules\Slider\Entities\Slider;
use App\Resource;
use Session;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getListSlider()
    {
        $slider_list = (new Slider)->getSliderList();
        return view('slider::list-slider')->with('slider_list', $slider_list);
    }

    public function getCreateSlider()
    {
        return view('slider::create-slider');
    }

    public function postCreateSlider(SliderRequest $request)
    {
        $input = request()->all();
        try {
            \DB::beginTransaction();     
                $slider = (new Slider)->createUpdateSlider($input['action'], $input);
                if(isset($input['featured_image']))
                {
                    $slider_folder = 'slider/';
                
                    (new Resource)->uploadResources($input['featured_image'], $slider_folder, $slider->id, 'slider');                     
                }   
            \DB::commit();            

        }catch(\Exception $e)
        {
            \DB::rollback();
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();
        }
        
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Slider created successfully');
    }

    public function getEditSlider($slider_id)
    {   
        try
        {
            $slider = (new Slider)->getSingleSlider($slider_id);  

        }
        catch (\Exception $e)
        {            
            return redirect()->back()->with('error-msg', $e->getMessage());
        }   

        return view('slider::edit-slider')->with('slider', $slider);
    }

    public function postEditSlider(SliderRequest $request, $slider_id)
    {
        $input = request()->all();

        try {
            \DB::beginTransaction();     
                (new Slider)->createUpdateSlider($input['action'], $input);
                $slider_resources = (new Resource)->getResource($slider_id, 'slider');
                if(isset($input['featured_image']))
                {
                    $slider_folder = 'slider/';
                
                    //////DELETE CURRENT RESOURCES
                    if(count($slider_resources) > 0)
                    {
                        foreach($slider_resources as $index => $resource)
                        {                        
                            (\App\Http\Controllers\FileController::removeFileFromS3($slider_folder, $resource));
                            (new Resource)->deleteResource($resource['resource_id']);
                        }

                    }
                    //ADD NEW RESOURCE
                    (new Resource)->uploadResources($input['featured_image'], $slider_folder, $slider_id, 'slider');                                
            }   
            \DB::commit();            

        }catch(\Exception $e)
        {
            \DB::rollback();
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage())->withInput();
        }
        
        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Slider created successfully');
    }


    public function getViewSlider($slider_id)
    {
        try
        {
            $slider = (new Slider)->getSingleSlider($slider_id);  
        }
        catch (\Exception $e)
        {            
            return redirect()->back()->with('error-msg', $e->getMessage());
        }   
    
        return view('slider::view-slider')->with('slider', $slider);   
    }

    public function postDeleteSlider($slider_id)
    {
        try {
            \DB::beginTransaction();
            $slider_class = new Slider;
            $slider = $slider_class->getSingleSlider($slider_id);           
            $slider_class->deleteslider($slider_id);
            if(count($slider->resource) > 0)
            {
                $slider_folder = 'slider/';
                foreach($slider->resource as $index => $resource)
                {
                    (\App\Http\Controllers\FileController::removeFileFromS3($slider_folder, $resource));
                    (new Resource)->deleteResource($resource['resource_id']);
                }
            }
            \DB::commit();

        }catch(\Exception $e) {
            Session::flash('error-text', 'Operation Unsuccessful');
            return redirect()->back()->with('error-msg', $e->getMessage());
        }

        Session::flash('info-text', 'Operation Successful');
        return redirect()->back()->with('success-msg', 'Slider deleted successfully');   
    }
}
