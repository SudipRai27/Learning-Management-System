<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'slider', 'namespace' => 'Modules\Slider\Http\Controllers'], function()
{
    Route::get('list-slider', [
		'as' => 'list-slider', 
		'uses' => 'SliderController@getListSlider', 
		'module' => 'Slider', 
		'permission_type' => 'can_view_slider_list'
    ]);

    Route::get('create-slider', [
		'as' => 'create-slider', 
		'uses' => 'SliderController@getCreateSlider', 
		'module' => 'Slider', 
		'permission_type' => 'can_create_slider'
    ]);

    Route::get('view-slider/{slider_id}', [
		'as' => 'view-slider', 
		'uses' => 'SliderController@getViewSlider', 
		'module' => 'Slider', 
		'permission_type' => 'can_view_slider'
    ]);

    Route::get('edit-slider/{slider_id}', [
    	'as' => 'edit-slider', 
    	'uses' => 'SliderController@getEditSlider', 
    	'module' => 'Slider', 
    	'permission_type' => 'can_edit_slider'
    ]);

    Route::post('edit-slider-post/{slider_id}', [
    	'as' => 'edit-slider-post', 
    	'uses' => 'SliderController@postEditSlider', 
    	'module' => 'Slider', 
    	'permission_type' => 'can_edit_slider'
    ]);

    Route::post('create-slider-post', [
    	'as' => 'create-slider-post',
    	'uses' => 'SliderController@postCreateSlider',  
    	'module' => 'Slider', 
    	'permission_type' => 'can_create_slider'
    ]);

    Route::post('delete-slider/{slider_id}',[
    	'as' => 'delete-slider', 
    	'uses' => 'SliderController@postDeleteSlider', 
    	'module' => 'Slider',
    	'permission_type' => 'can_delete_slider'
    ]);	
   
});
