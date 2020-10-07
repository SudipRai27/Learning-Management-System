<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [
    'as' => 'login', 
    'uses' => '\Modules\User\Http\Controllers\UserController@getUserLogin'
])->middleware('RedirectIfUserAuthenticated');


Route::post('/remove-global/{type}', array(
	'as'	=>	'remove-global',
	'uses'	=>	'ConfigurationController@removeGlobal'));

Route::post('/publish-notice', [
	'as' => 'publish-notice', 
	'uses' => 'HelperController@postPublishNotice'
]);

Route::post('/get-notice', [
	'as' => 'get-notice', 
	'uses' => 'HelperController@getPublishedNotice'
]);

Route::post('/delete-notice', [
	'as' => 'delete-notice', 
	'uses' => 'HelperController@postDeleteNotice'
]);

include 'ajax-routes.php';