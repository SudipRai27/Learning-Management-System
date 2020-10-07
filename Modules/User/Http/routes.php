<?php

Route::group(['middleware' => 'web', 'prefix' => 'user', 'namespace' => 'Modules\User\Http\Controllers'], function()
{

	//GET ROUTES
    Route::get('/register', [
    	'as' => 'user-register', 
    	'uses' => 'UserController@getRegister'
    	]);

    Route::get('/login', [
    	'as' => 'user-login', 
    	'uses' => 'UserController@getUserLogin'
    	])->middleware('RedirectIfUserAuthenticated');

    Route::get('/home', [
    	'as' => 'user-home', 
    	'uses' => 'UserController@getUserHome'
    	])->middleware('UserCheck');

    Route::get('/user-logout',[
    	'as' => 'user-logout', 
    	'uses' => 'UserController@getLogout'
    	])->middleware('UserCheck');

    Route::get('/user-profile', [
        'as' => 'user-profile', 
        'uses' => 'UserController@getUserProfile'
    ])->middleware('UserCheck');



    Route::post('/change-profile-picture', [
        'as' => 'change-profile-picture', 
        'uses' => 'UserController@postChangeProfilePicture'
    ])->middleware('UserCheck');

    Route::post('/remove-profile-picture', [
        'as' => 'remove-profile-picture', 
        'uses' => 'UserController@postRemoveProfilePicture'
    ])->middleware('UserCheck');

    //POST ROUTES

    Route::post('/user-create-post', [
    	'as' => 'user-create-post', 
    	'uses' => 'UserController@postRegister'
    	]);

    Route::post('/user-login-post', [
    	'as' => 'user-login-post', 
    	'uses' => 'UserController@postUserLogin'
    	])->middleware('RedirectIfUserAuthenticated');

    Route::post('/change-password', [
        'as' => 'change-password', 
        'uses' => 'UserController@postChangePassword'
    ])->middleware('UserCheck');


});

Route::group(['middleware' => 'web', 'prefix' => 'account', 'namespace' => 'Modules\User\Http\Controllers'], function()
{  

    Route::get('/forgot-password', [
        'as' => 'forgot-password', 
        'uses' => 'PasswordResetController@getForgotPassword'
    ])->middleware('RedirectIfUserAuthenticated');

    Route::post('/send-email-link', [
        'as' => 'send-email-link', 
        'uses' => 'PasswordResetController@sendEmailLink'
    ])->middleware('RedirectIfUserAuthenticated');

    Route::get('/password/reset/{token}', [
        'as' => 'account-password-reset', 
        'uses' => 'PasswordResetController@getResetPasword'
    ])->middleware('RedirectIfUserAuthenticated');

    Route::post('/change-password-from-link',[
        'as' => 'change-password-from-link',  
        'uses' => 'PasswordResetController@postResetPassword'
    ])->middleware('RedirectIfUserAuthenticated');

});