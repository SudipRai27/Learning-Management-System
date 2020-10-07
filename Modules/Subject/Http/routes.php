<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'subject', 'namespace' => 'Modules\Subject\Http\Controllers'], function()
{
    Route::get('/list-subject', [
    	'as' => 'list-subject', 
    	'uses' => 'SubjectController@getListSubject', 
        'module' => 'Subject', 
        'permission_type' => 'can_view_subject_list'
    ]);

    Route::get('/create-subject', [
    	'as' => 'create-subject', 
    	'uses' => 'SubjectController@getCreateSubject', 
        'module' => 'Subject', 
        'permission_type' => 'can_create_subject'
    ]);
    

    Route::get('edit-subjects/{id}', [
        'as' => 'edit-subjects', 
        'uses' => 'SubjectController@getEditSubjects', 
        'module' => 'Subject', 
        'permission_type' => 'can_edit_subject'
    ]);

    Route::get('frontend-subject-details/{session_id}/{subject_id}', [
        'as' => 'frontend-subject-details',  
        'uses' => 'SubjectController@getFrontendSubjectDetails', 
        'module' => 'Subject', 
        'permission_type' => 'can_view_assigned_and_enrolled_subjects'
    ]);
    
    Route::get('/view-teacher-student-subjects', [
        'as' => 'view-teacher-student-subjects', 
        'uses' => 'SubjectController@getViewSubjectsAccordingToUserType', 
        'module' => 'Subject', 
        'permission_type' => 'can_view_assigned_and_enrolled_subjects'
    ]);

    
    Route::post('delete-subject/{id}', [
        'as' => 'delete-subject',  
        'uses' => 'SubjectController@postDeleteSubject', 
        'module' => 'Subject', 
        'permission_type' => 'can_delete_subject'
    ]);


    Route::post('create-subject', [
    	'as' => 'create-subject', 
    	'uses' => 'SubjectController@postCreateSubject', 
        'module' => 'Subject', 
        'permission_type' => 'can_create_subject'
    ]);

    Route::post('update-subject/{id}',[
        'as' => 'update-subject', 
        'uses' => 'SubjectController@postUpdateSubject', 
        'module' => 'Subject', 
        'permission_type' => 'can_edit_subject'
    ]);
});



