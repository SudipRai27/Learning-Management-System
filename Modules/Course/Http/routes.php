<?php

//Course 
Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'course', 'namespace' => 'Modules\Course\Http\Controllers'], function()
{
    Route::get('create-course', [
    	'as' => 'create-course', 
    	'uses' => 'CourseController@getCreateCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_create_course'
    ]);

    Route::get('list-course', [
    	'as' => 'list-course', 
    	'uses' => 'CourseController@getListCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_view_course_list'
    ]);

    Route::get('edit-course/{id}',[
    	'as' => 'edit-course', 
    	'uses' => 'CourseController@getEditCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_edit_course'
    ]);

    Route::post('delete-course/{id}', [
    	'as' => 'delete-course', 
    	'uses' => 'CourseController@postDeleteCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_delete_course'
    ]);


    Route::post('create-course', [
    	'as' => 'create-course', 
    	'uses' => 'CourseController@postCreateCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_create_course'
    ]);

    Route::post('edit-course/{id}',[
    	'as' => 'edit-course', 
    	'uses' => 'CourseController@postUpdateCourse', 
        'module' => 'Course', 
        'permission_type' => 'can_edit_course'
    ]);
});




//Course Types
Route::group(['middleware' => ['web', 'UserCheck'], 'prefix' => 'course-type', 'namespace' => 'Modules\Course\Http\Controllers'], function()
{
    Route::get('/list-course-type',[
    	'as' => 'list-course-type', 
    	'uses' => 'CourseTypeController@getListCourseType', 
        'module' => 'Course', 
        'permission_type' => 'can_view_course_type_list'

    ]);

    Route::get('/create-course-type', [
    	'as' => 'create-course-type', 
    	'uses' => 'CourseTypeController@getCreateCourseType', 
        'module' => 'Course', 
        'permission_type' => 'can_create_course_type'
    ]);

    Route::get('edit-course-type/{id}',[
    	'as' => 'edit-course-type', 
    	'uses' => 'CourseTypeController@getEditCourseType', 
        'module' => 'Course', 
        'permission_type' => 'can_edit_course_type'
    ]);

    Route::post('create-course-work', [
    	'as' => 'create-course-work', 
    	'uses' => 'CourseTypeController@postCreateCourseType', 
        'module' => 'Course', 
        'permission_type' => 'can_create_course_type'
    ]);

    Route::post('update-course-work/{id}', [
    	'as' => 'update-course-work', 
    	'uses' => 'CourseTypeController@postUpdateCourseType', 
        'module' => 'Course', 
        'permission_type' => 'can_edit_course_type'
    ]);

    Route::post('/delete-course-type/{id}',[
        'as' => 'delete-course-type', 
        'uses' => 'CourseTypeController@postCourseTypeDelete', 
        'module' => 'Course', 
        'permission_type' => 'can_delete_course_type'
    ]);

});
