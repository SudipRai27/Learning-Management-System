<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'enrollment', 'namespace' => 'Modules\Enrollment\Http\Controllers'], function()
{   

    Route::get('/create-enrollment', [
    	'as' => 'create-enrollment', 
    	'uses' => 'EnrollmentController@getCreateEnrollment', 
        'module' => 'Enrollment', 
        'permission_type' => 'can_create_enrollment'
    ]);

    Route::get('/list-enrollment', [
    	'as' => 'list-enrollment', 
    	'uses' => 'EnrollmentController@getListEnrollment', 
        'module' => 'Enrollment', 
        'permission_type' => 'can_view_enrollment_list'
    ]);

    Route::post('/create-enrollment', [
    	'as' => 'create-enrollment', 
    	'uses' => 'EnrollmentController@postCreateEnrollment', 
        'module' => 'Enrollment', 
        'permission_type' => 'can_create_enrollment'
    ]);

    Route::post('/delete-enrollment/{id}',[
        'as' =>'delete-enrollment', 
        'uses' => 'EnrollmentController@postDeleteEnrollment', 
        'module' => 'Enrollment', 
        'permission_type' => 'can_delete_enrollment'
    ]);
});
