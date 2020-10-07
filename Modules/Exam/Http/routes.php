<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'exam', 'namespace' => 'Modules\Exam\Http\Controllers'], function()
{
    Route::get('/list-exam', [
    	'as' => 'list-exam', 
    	'uses' => 'ExamController@getExamList', 
        'module' => 'Exam', 
        'permission_type' => 'can_view_exam_list'
    ]);

    Route::get('/create-exam', [
    	'as' => 'create-exam', 
    	'uses' => 'ExamController@getCreateExam', 
        'module' => 'Exam', 
        'permission_type' => 'can_create_exam'
    ]);

    Route::get('/edit-exam/{exam_id}', [
    	'as' => 'edit-exam', 
    	'uses' => 'ExamController@getEditExam', 
        'module' => 'Exam', 
        'permission_type' => 'can_edit_exam'
    ]);

    Route::get('/list-exam-marks', [
        'as' => 'list-exam-marks', 
        'uses' => 'ExamMarksController@getExamMarksList', 
        'module' => 'Exam', 
        'permission_type' => 'can_view_exam_marks_list'
    ]);

    Route::get('/upload-exam-marks', [
        'as' => 'upload-exam-marks', 
        'uses' => 'ExamMarksController@getUploadExamMarks', 
        'module' => 'Exam', 
        'permission_type' => 'can_upload_exam_marks'
    ]);

    Route::post('/create-exam-post', [
    	'as' => 'create-exam-post', 
    	'uses' => 'ExamController@postCreateExam', 
        'module' => 'Exam', 
        'permission_type' => 'can_create_exam'
    ]);

    Route::post('/update-exam-post/{exam_id}', [
    	'as' => 'update-exam-post', 
    	'uses' => 'ExamController@postUpdateExam', 
        'module' => 'Exam', 
        'permission_type' => 'can_edit_exam'
    ]);

    Route::post('/delete-exam/{exam_id}', [
    	'as' => 'delete-exam', 
    	'uses' => 'ExamController@postDeleteExam', 
        'module' => 'Exam', 
        'permission_type' => 'can_delete_exam'
    ]);

    Route::post('/upload-exam-marks-post', [
        'as' => 'upload-exam-marks-post', 
        'uses' => 'ExamMarksController@postUploadExamMarks', 
        'module' => 'Exam', 
        'permission_type' => 'can_upload_exam_marks'
    ]); 

});
