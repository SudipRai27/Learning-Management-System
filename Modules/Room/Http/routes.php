<?php

Route::group(['middleware' => ['web','UserCheck','Permission'], 'prefix' => 'room', 'namespace' => 'Modules\Room\Http\Controllers'], function()
{
    Route::get('/list-room', [
    	'as' => 'list-room', 
    	'uses' => 'RoomController@getListRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_view_room_list'
    ]);	

    Route::get('/create-room', [
    	'as' => 'create-room', 
    	'uses' => 'RoomController@getCreateRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_create_room'
    ]);

    Route::get('/edit-room/{id}', [
        'as' => 'edit-room', 
        'uses' => 'RoomController@getEditRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_edit_room'
    ]);

    Route::post('create-room-post', [
    	'as' => 'create-room-post', 
    	'uses' => 'RoomController@postCreateRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_create_room'
    ]);    

    Route::post('edit-room-post/{id}',[
        'as' => 'edit-room-post', 
        'uses' => 'RoomController@postEditRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_edit_room'
    ]);

    Route::post('delete-room/{id}', [
        'as' => 'delete-room', 
        'uses' => 'RoomController@postDeleteRoom', 
        'module' => 'Room', 
        'permission_type' => 'can_delete_room'
    ]);
});
