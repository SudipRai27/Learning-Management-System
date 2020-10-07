<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'timetable', 'namespace' => 'Modules\TimeTable\Http\Controllers'], function()
{
    Route::get('list-timetable', [
    	'as' => 'list-timetable', 
    	'uses' => 'TimeTableController@getListTimeTable', 
        'module' => 'TimeTable', 
        'permission_type' => 'can_view_timetable_list'
    ]);

    Route::get('create-timetable',  [
    	'as' => 'create-timetable', 
    	'uses' => 'TimeTableController@getCreateTimeTable', 
        'module' => 'TimeTable', 
        'permission_type' => 'can_create_update_timetable'
    ]);

    Route::get('update-timetable/{session_id}/{subject_id}/{student_id}', [
    	'as' => 'update-timetable', 
    	'uses' => 'TimeTableController@getUpdateTimeTable', 
        'module' => 'TimeTable', 
        'permission_type' => 'can_create_update_timetable'
    ]);

    Route::post('/update-timetable-post', [
    	'as' => 'update-timetable-post', 
    	'uses' => 'TimeTableController@postUpdateTimeTable', 
        'module' => 'TimeTable', 
        'permission_type' => 'can_create_update_timetable'
    ]);

    Route::post('/delete-timetable/{timetable_id}', [
        'as' => 'delete-timetable', 
        'uses' => 'TimeTableController@postDeleteTimeTable', 
        'module' => 'TimeTable', 
        'permission_type' => 'can_delete_timetable'
    ]);
});

