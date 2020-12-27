<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('user-login', 'LoginController@userLogin');

// Route::get('session-check', 'LoginController@checkSession');

Route::group(['middleware' => 'auth:api'], function() {
    
    //all other api routes goes here 

    

    Route::group(['prefix'=>'admin'], function()
    {
    	Route::post('logout', 'LoginController@logOut');

    	Route::get('users', 'UserController@index');	
    	
    	Route::group(['prefix' => 'users'], function()
    	{
    		Route::get('{id}', 'UserController@edit');	
	    	Route::post('create', 'UserController@create');	
	    	Route::post('{id}/update', 'UserController@update');
	    	Route::delete('{id}', 'UserController@delete');
    	});

        Route::apiResource('service', 'ServiceController');
    	Route::apiResource('booking', 'BookingController');

        Route::get('customers', 'BookingController@customers');
        Route::get('services', 'BookingController@services');

    });



});
