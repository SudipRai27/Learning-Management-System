<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'classes', 'namespace' => 'Modules\Classes\Http\Controllers'], function()
{
    Route::get('/list-class',[
    	'as' => 'list-class', 
    	'uses' => 'ClassesController@getListClass', 
        'module' => 'Classes', 
        'permission_type' => 'can_view_class_list'
    ]);

    Route::get('/create-classs', [
    	'as' => 'create-class', 
    	'uses' => 'ClassesController@getCreateClass', 
        'module' => 'Classes', 
        'permission_type' => 'can_create_class'
    ]);

    Route::get('/edit-classes/{id}',[
        'as' => 'edit-classes', 
        'uses' => 'ClassesController@getEditClasses', 
        'module' => 'Classes', 
        'permission_type' => 'can_edit_class'    
    ]);


    Route::post('/create-class-post', [
    	'as' => 'create-class-post', 
    	'uses' => 'ClassesController@postCreateClass', 
        'module' => 'Classes', 
        'permission_type' => 'can_create_class'
    ]);

    Route::post('/delete-class/{id}', [
        'as' => 'delete-class',
        'uses' => 'ClassesController@postDeleteClass', 
        'module' => 'Classes', 
        'permission_type' => 'can_view_individual_class'
    ]);

    Route::post('/post-update-class/{id}', [
        'as' => 'post-update-class',  
        'uses' => 'ClassesController@postUpdateClass', 
        'module' => 'Classes', 
        'permission_type' => 'can_delete_class'
    ]);


});
