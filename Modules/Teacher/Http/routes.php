<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'teacher', 'namespace' => 'Modules\Teacher\Http\Controllers'], function()
{

    Route::get('/list-teachers', [
    	'as' => 'list-teacher', 
    	'uses' => 'TeacherController@getListTeachers', 
        'module' => 'Teacher', 
        'permission_type' => 'can_view_teacher_list'
    ]);

    Route::get('/create-teacher', [
    	'as' => 'create-teacher', 
    	'uses' => 'TeacherController@getCreateTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_create_teacher'
    ]);

    Route::get('/view-teacher/{id}',[
        'as' => 'view-teacher', 
        'uses' => 'TeacherController@getViewTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_view_individual_teacher'
    ]);

    Route::get('/edit-teacher/{id}', [
        'as' => 'edit-teacher', 
        'uses' => 'TeacherController@getEditTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_edit_teacher'
    ]);

    Route::get('/assign-teacher/',  [
        'as' => 'assign-teacher', 
        'uses' => 'TeacherController@getAssignTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_assign_teacher'
    ]);

    Route::get('/list-assigned-teacher', [
        'as' => 'list-assigned-teacher', 
        'uses' => 'TeacherController@getListAssignedTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_view_assigned_teacher_list'
    ]);

    Route::get('/assign-teacher-and-room/{subject_id}', [
        'as' => 'assign-teacher-and-room', 
        'uses' => 'TeacherController@getAssignTeacherAndRoom', 
        'module' => 'Teacher', 
        'permission_type' => 'can_assign_teacher'
    ]);
    Route::post('/create-teacher', [
    	'as' => 'create-teacher', 
    	'uses' => 'TeacherController@postCreateTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_create_teacher'
    ]);

    Route::post('/update-teacher/{id}', [
        'as' => 'update-teacher', 
        'uses' => 'TeacherController@postUdpateTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_edit_teacher'
    ]);

    Route::post('/delete-teacher/{id}', [
        'as' => 'delete-teacher', 
        'uses' => 'TeacherController@postDeleteTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_delete_teacher'
    ]);
   
    Route::post('/delete-assigned-teacher/{id}', [
        'as' => 'delete-assigned-teacher', 
        'uses' => 'TeacherController@postDeleteAssignedTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_delete_assigned_teacher'
    ]);

    Route::post('update-assigned-teacher', [
        'as' => 'update-assigned-teacher', 
        'uses' => 'TeacherController@postUpdateAssignedTeacher', 
        'module' => 'Teacher', 
        'permission_type' => 'can_edit_assigned_teacher'
    ]);

});
