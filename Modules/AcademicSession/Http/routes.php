<?php

Route::group(['middleware' => ['web','UserCheck', 'Permission'], 'prefix' => 'academicsession', 'namespace' => 'Modules\AcademicSession\Http\Controllers'], function()
{

	Route::get('list-academic-session', [
		'as' => 'list-academic-session', 
		'uses' => 'AcademicSessionController@getListAcademicSession', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_list_academic_session'
	]);

    Route::get('create-academic-session',[
    	'as' => 'create-academic-session', 
    	'uses' =>'AcademicSessionController@getCreateAcademicSession', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_create_academic_session'
    ]);

    Route::get('edit-academic-session/{id}',[
        'as' => 'edit-academic-session',  
        'uses' => 'AcademicSessionController@getEditAcademicSession',
        'module' => 'AcademicSession', 
        'permission_type' => 'can_edit_academic_session'
    ]);

    Route::get('academic-session-settings', [
        'as' => 'academic-session-settings', 
        'uses' => 'AcademicSessionController@getAcademicSessionSettings', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_update_academic_session_settings'
    ]);

    Route::post('delete-academic-session/{id}',[
        'as' => 'delete-academic-session', 
        'uses' => 'AcademicSessionController@postDeleteAcademicSession', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_delete_academic_session'
    ]);

    Route::post('create-academic-session', [
    	'as' => 'create-academic-session', 
    	'uses' => 'AcademicSessionController@postCreateAcademicSession', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_create_academic_session'
    ]);

    Route::post('update-academic-session/{id}',[
        'as' => 'update-academic-session', 
        'uses' => 'AcademicSessionController@postUpdateAcademicSession', 
        'module' => 'AcademicSession', 
        'permission_type' => 'can_edit_academic_session'
    ]);

    Route::post('update-settings', [
        'as' => 'update-settings', 
        'uses' => 'AcademicSessionController@postUpdateAcademicSessionSettings',
        'module' => 'AcademicSession', 
        'permission_type' => 'can_update_academic_session_settings'
    ]);
    
});

