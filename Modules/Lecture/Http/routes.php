<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'lecture', 'namespace' => 'Modules\Lecture\Http\Controllers'], function()
{
     Route::get('/list-lecture', [
    	'as' => 'list-lecture', 
    	'uses' => 'LectureController@getListLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_view_lecture_list'
    ]);

    Route::get('/create-lecture', [
    	'as' => 'create-lecture', 
    	'uses' => 'LectureController@getCreateLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_create_lecture'
    ]);


    Route::get('/edit-lecture/{lecture_id}', [
        'as' => 'edit-lecture', 
        'uses' => 'LectureController@getEditLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_edit_lecture'
    ]);

    Route::get('/manage-resources/{lecture_id}', [
        'as' => 'manage-resources', 
        'uses' => 'LectureController@getManageResources', 
        'module' => 'Lecture', 
        'permission_type' => 'can_manage_lecture_resources'
    ]);

    Route::post('/create-lecture', [
    	'as' => 'create-lecture', 
    	'uses' => 'LectureController@postCreateLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_create_lecture'
    ]);

    Route::post('/update-lecture/{lecture_id}', [
        'as' => 'update-lecture', 
        'uses' => 'LectureController@postUpdateLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_edit_lecture'
    ]);
    

    Route::post('/delete-lecture/{lecture_id}', [
        'as' => 'delete-lecture', 
        'uses' => 'LectureController@postDeleteLecture', 
        'module' => 'Lecture', 
        'permission_type' => 'can_delete_lecture'
    ]);

    Route::post('/remove-lecture-resource/{resource_id}', [
        'as' => 'remove-lecture-resource', 
        'uses' => 'LectureController@postRemoveLectureResource', 
        'module' => 'Lecture', 
        'permission_type' => 'can_remove_lecture_resources'
    ]);

    Route::post('/upload-lecture-resources', [
        'as' => 'upload-lecture-resources', 
        'uses' => 'LectureController@postUploadLectureResources', 
        'module' => 'Lecture', 
        'permission_type' => 'can_upload_lecture_resources'
    ]);


});
