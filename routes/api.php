<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

	//User and Rider functionalities;

    Route::post('register-rider', 'Auth\RiderController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('logout', 'Auth\LogoutController@logout');
    Route::post('refresh', 'Auth\LoginController@refresh');
    Route::post('update-password', 'ProfileController@update_password');
    Route::post('add-card','ProfileController@add_bank_card' );
	

});	
	//start and end trips;

    Route::post('start-trip', 'TripsController@start_trip');
    Route::put('end-trip', 'TripsController@end_trip');
   
   //make a moove request, contact a rider:
    Route::post('request-rider', 'TripsController@make_moove_request');

    //rider active ride:
    Route::post('active-ride', 'TripsController@rider_active_ride');


