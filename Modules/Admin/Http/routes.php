<?php

Route::group(['middleware' => ['web', 'UserCheck', 'Permission'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::get('/create-admin', [
    	'as' => 'create-admin', 
    	'uses' => 'AdminController@getCreateAdmin', 
    	'module' => 'Admin', 
        'permission_type' => 'can_create_admin'
    ]);

    Route::post('/create-admin-post', [
    	'as' => 'create-admin-post', 
    	'uses' => 'AdminController@postCreateAdmin', 
    	'module' => 'Admin', 
    	'permission_type' => 'can_create_admin'
    ]);

    Route::get('/list-admin', [
    	'as' => 'list-admin', 
    	'uses' => 'AdminController@getListAdmin', 
    	'module' => 'Admin', 
        'permission_type' => 'can_view_admin_list'
    ]);

    Route::get('/edit-admin/{user_id}', [
    	'as' => 'edit-admin', 
    	'uses' => 'AdminController@getEditAdmin', 
    	'module' => 'Admin', 
        'permission_type' => 'can_edit_admin'
    ]);

    Route::post('/delete-admin/{user_id}', [
    	'as' => 'delete-admin', 
    	'uses' => 'AdminController@postDeleteAdmin', 
    	'module' => 'Admin', 
    	'permission_type' => 'can_delete_admin'
    ]);

    Route::post('/update-admin/{user_id}', [
    	'as' => 'update-admin', 
    	'uses' => 'AdminController@postEditAdmin', 
    	'module' => 'Admin', 
        'permission_type' => 'can_edit_admin'
    ]);
});
