<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'result', 'namespace' => 'Modules\Result\Http\Controllers'], function()
{
    Route::get('/list-result', [
    	'as' => 'list-result', 
    	'uses' => 'ResultController@getListResult', 
        'module' => 'Result', 
        'permission_type' => 'can_view_result_list'
    ]);

    Route::get('/generate-result', [
    	'as' => 'generate-result', 
    	'uses' => 'ResultController@getGenerateResult', 
        'module' => 'Result', 
        'permission_type' => 'can_generate_result'
    ]);

    Route::get('/view-results/{session_id}/{student_id}', [
    	'as' => 'view-results', 
    	'uses' => 'ResultController@getViewResult', 
        'module' => 'Result', 
        'permission_type' => 'can_view_individual_result'
    ]);

    Route::get('/view-grades', [
        'as' => 'view-grades', 
        'uses' => 'ResultController@getViewStudentGrades', 
        'module' => 'Result', 
        'permission_type' => 'can_view_grades'
    ]); 

    Route::post('/generate-results-post', [
    	'as' => 'generate-results-post', 
    	'uses' => 'ResultController@postGenerateResults', 
        'module' => 'Result', 
        'permission_type' => 'can_generate_result'
    ]);

    Route::post('/delete-result', [
    	'as' => 'delete-result', 
    	'uses' => 'ResultController@postDeleteResult', 
        'module' => 'Result', 
        'permission_type' => 'can_delete_result'
    ]);
});
