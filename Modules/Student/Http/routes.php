<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'student', 'namespace' => 'Modules\Student\Http\Controllers'], function()
{
    Route::get('create-student', [
    	'as' => 'create-student', 
    	'uses' => 'StudentController@getCreateStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_create_student'
    ]);

    Route::get('list-student', [
    	'as' => 'list-student', 
    	'uses' => 'StudentController@getListStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_view_student_list'
    ]);

    Route::post('delete-student/{id}', [
        'as' => 'delete-student', 
        'uses' => 'StudentController@postDeleteStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_delete_student'
    ]);

    Route::get('view-student/{id}', [
        'as' => 'view-student', 
        'uses' => 'StudentController@getViewStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_view_individual_student'
    ]);


    Route::get('edit-student/{id}',[
        'as' => 'edit-student', 
        'uses' => 'StudentController@getEditStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_edit_student'
    ]);


    Route::post('create-student', [
    	'as' => 'create-student' , 
    	'uses' => 'StudentController@postCreateStudent' , 
        'module' => 'Student', 
        'permission_type' => 'can_create_student'
    ]);	

    Route::post('update-student/{student_id}/{user_id}', [
        'as' => 'update-student', 
        'uses' => 'StudentController@postUpdateStudent', 
        'module' => 'Student', 
        'permission_type' => 'can_edit_student'
    ]);
});
