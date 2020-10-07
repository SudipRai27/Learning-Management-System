<?php

Route::group(['middleware' => ['web', 'UserCheck','Permission'], 'prefix' => 'event', 'namespace' => 'Modules\Event\Http\Controllers'], function()
{
    Route::get('list-event', [
    	'as' => 'list-event', 
    	'uses' => 'EventController@getListEvent', 
    	'module' => 'Event', 
    	'permission_type' => 'can_view_event_list'
    ]);

    Route::get('create-event', [
    	'as' => 'create-event', 
    	'uses' => 'EventController@getCreateEvent', 
    	'module' => 'Event', 
    	'permission_type' => 'can_create_event'
    ]);

    Route::post('search-for-events', [
    	'as' => 'search-for-events', 
    	'uses' => 'EventController@getSearchEventList', 
    	'module' => 'Event', 
    	'permission_type' => 'can_view_event_list'
    ]);

    Route::get('view-event/{view_type}/{event_id}', [
        'as' => 'view-event', 
        'uses' => 'EventController@getViewEvent', 
        'module' => 'Event', 
        'permission_type' => 'can_view_individual_event'
    ]);

    Route::get('edit-event/{event_id}', [
        'as' => 'edit-event', 
        'uses' => 'EventController@getEditEvent', 
        'module' => 'Event', 
        'permission_type' => 'can_edit_event'
    ]);

    Route::get('manage-and-edit-event-images/{event_id}',[
        'as' => 'manage-and-edit-event-images', 
        'uses' => 'EventController@getManageEventImages', 
        'module' => 'Event', 
        'permission_type' => 'can_edit_event'
    ]);

    Route::post('edit-event-post/{event_id}', [
        'as' => 'edit-event-post', 
        'uses' => 'EventController@postEditEvent', 
        'module' => 'Event', 
        'permission_type' => 'can_edit_event'
    ]);

    Route::post('create-event-post', [
    	'as' => 'create-event-post', 
    	'uses' => 'EventController@postCreateEvent', 
    	'module' => 'Event', 
    	'permission_type' => 'can_create_event'
    ]);

    Route::post('delete-event/{event_id}', [
        'as' => 'delete-event', 
        'uses' => 'EventController@postDeleteEvent', 
        'module' => 'Event', 
        'permission_type' => 'can_delete_event'
    ]);

    Route::post('remove-event-image/{resource_id}', [
        'as' => 'remove-event-image', 
        'uses' => 'EventController@postRemoveEventImage', 
        'module' => 'Event', 
        'permission_type' => 'can_edit_event'
    ]);

    Route::post('upload-event-images',[
        'as' => 'upload-event-images', 
        'uses' => 'EventController@postUploadEventImage', 
        'module' => 'Event', 
        'permission_type' => 'can_edit_event'
    ]);
});
