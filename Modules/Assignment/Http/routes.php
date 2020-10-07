<?php

Route::group(['middleware' => ['web', 'UserCheck', 'Permission'], 'prefix' => 'assignment', 'namespace' => 'Modules\Assignment\Http\Controllers'], function()
{
    Route::get('/list-assignment',  [
    	'as' => 'list-assignment', 
    	'uses' => 'AssignmentController@getListAssignment', 
        'module' => 'Assignment', 
        'permission_type' => 'can_view_assignment_list'
    ]);

    Route::get('/create-assignment', [
    	'as' => 'create-assignment', 
    	'uses' => 'AssignmentController@getCreateAssignment', 
        'module' => 'Assignment', 
        'permission_type' => 'can_create_assignment'
    ]);

    Route::get('/edit-assignment/{assignment_id}', [
        'as' => 'edit-assignment', 
        'uses' => 'AssignmentController@getEditAssignment', 
        'module' => 'Assignment', 
        'permission_type' => 'can_edit_assignment'
    ]);

    Route::get('/manage-assignment-resources/{assignment_id}', [
        'as' => 'manage-assignment-resources', 
        'uses' => 'AssignmentController@getManageAssignmentResources', 
        'module' => 'Assignment', 
        'permission_type' => 'can_manage_assignment_files'

    ]);

    Route::get('/list-assignment-marks', [
        'as' => 'list-assignment-marks', 
        'uses' => 'AssignmentMarksController@getListAssignmentMarks', 
        'module' => 'Assignment', 
        'permission_type' => 'can_view_assignment_marks_list'

    ]);

    Route::get('/upload-assignment-marks', [
        'as' => 'upload-assignment-marks', 
        'uses' => 'AssignmentMarksController@getUploadAssignmentMarks', 
        'module' => 'Assignment', 
        'permission_type' => 'can_upload_assignment_marks'
    ]);

    Route::get('/submit-assignment/{session_id}/{subject_id}/{assignment_id}', [
        'as' => 'submit-assignment', 
        'uses' => 'AssignmentController@getSubmitAssignment', 
        'module' => 'Assignment', 
        'permission_type' => 'can_submit_assignment'
    ]);

    Route::get('/add-submission/{assignment_id}', [
        'as' => 'add-submission', 
        'uses' => 'AssignmentController@getAddSubmissionFiles', 
        'module' => 'Assignment', 
        'permission_type' => 'can_submit_assignment'
    ]);

    Route::get('/frontend-view-assignment-submissions/{session_id}/{subject_id}/{assignment_id}', [
        'as' => 'frontend-view-assignment-submissions', 
        'uses' => 'AssignmentController@getViewSubmissionAssignments', 
        'module' => 'Assignment', 
        'permission_type' => 'can_view_submission'
    ]);

    Route::get('/view-assignment-submission-backend', [
        'as' => 'view-assignment-submission-backend', 
        'uses' => 'AssignmentController@getBackendViewSubmissionAssignments', 
        'module' => 'Assignment', 
        'permission_type' => 'can_view_submission'
    ]); 

    Route::post('/remove-assignment-resource/{resource_id}', [
        'as' => 'remove-assignment-resource', 
        'uses' => 'AssignmentController@postRemoveAssignmentResource', 
        'module' => 'Assignment', 
        'permission_type' => 'can_manage_assignment_files'
    ]); 

    Route::post('/remove-assignment-submission-resource/{resource_id}', [
        'as' => 'remove-assignment-submission-resource', 
        'uses' => 'AssignmentController@postRemoveAssignmentSubmissionResource', 
        'module' => 'Assignment', 
        'permission_type' => 'can_remove_assignment_submission_files'
    ]);

    Route::post('/create-assignment-post', [
    	'as' => 'create-assignment-post', 
    	'uses' => 'AssignmentController@postCreateAssignment',
        'module' => 'Assignment', 
        'permission_type' => 'can_create_assignment'
    ]);

    Route::post('/update-assignment/{assignment_id}', [
        'as' => 'update-assignment', 
        'uses' => 'AssignmentController@postUpdateAssignment',  
        'module' => 'Assignment', 
        'permission_type' => 'can_edit_assignment'
    ]);

    Route::post('/delete-assignment/{assignment_id}', [
        'as' => 'delete-assignment', 
        'uses' => 'AssignmentController@postDeleteAssignment', 
        'module' => 'Assignment', 
        'permission_type' => 'can_delete_assignment'
    ]);

    Route::post('/upload-assignment-resources', [
        'as' => 'upload-assignment-resources',
        'uses' => 'AssignmentController@postUploadAssignmentResources', 
        'module' => 'Assignment', 
        'permission_type' => 'can_manage_assignment_files'

    ]);

    Route::post('/upload-assignment-marks-post', [
        'as' => 'upload-assignment-marks-post', 
        'uses' => 'AssignmentMarksController@postUploadAssignmentMarks', 
        'module' => 'Assignment', 
        'permission_type' => 'can_upload_assignment_marks'
    ]);

    Route::post('/submit-assignment-files/',[
        'as' =>'submit-assignment-files', 
        'uses' => 'AssignmentController@postsubmitAssignmentFiles', 
        'module' => 'Assignment', 
        'permission_type' => 'can_submit_assignment'
    ]);

    Route::post('/delete-assignment-submission/{submission_id}/{student_id}', [
        'as' => 'delete-assignment-submission', 
        'uses' => 'AssignmentController@deleteAssignmentSubmission', 
        'module' => 'Assignment', 
        'permission_type' => 'can_remove_submission'
    ]);

});
