<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'attendance', 'namespace' => 'Modules\Attendance\Http\Controllers'], function()
{    

    Route::get('/list-attendance', [	
    	'as' => 'list-attendance', 
    	'uses' => 'AttendanceController@getListAttendance', 
        'module' => 'Attendance', 
        'permission_type' => 'can_view_attendance_list'
    ]);

    Route::get('/create-attendance', [	
    	'as' => 'create-attendance', 
    	'uses' => 'AttendanceController@getCreateAttendance', 
        'module' => 'Attendance', 
        'permission_type' => 'can_create_update_attendance'
    ]);

    Route::get('/update-attendance/{session_id}/{subject_id}/{class_id}/{week_id}', [
    	'as' => 'update-attendance', 
    	'uses' => 'AttendanceController@getUpdateAttendance', 
        'module' => 'Attendance', 
        'permission_type' => 'can_create_update_attendance'
    ]);

    Route::get('/view-attendance/{session_id}/{subject_id}/{class_id}', [
        'as' => 'view-attendance', 
        'uses' => 'AttendanceController@getViewAttendance', 
        'module' => 'Attendance', 
        'permission_type' => 'can_view_individual_attendance'
    ]);

    Route::post('/update-attendance-post/{class_id}', [
    	'as' => 'update-attendance-post', 
    	'uses' => 'AttendanceController@postUpdateAttendance', 
        'module' => 'Attendance', 
        'permission_type' => 'can_create_update_attendance'
    ]);
});
